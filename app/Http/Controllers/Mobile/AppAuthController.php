<?php

namespace App\Http\Controllers\Mobile;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Str;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Models\AccountSession;
use Google\Client as GoogleClient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Http;
class AppAuthController extends Controller
{

    public function qr_login(Request $request)
    {
        $request->validate([
            'auth_qr' => 'required|string',
            'device_id' => 'required|string',
            'device_name' => 'nullable|string',
            'device_type' => 'nullable|string',
            'location' => 'nullable|string',
        ]);


        return DB::transaction(function () use ($request) {
            // 1. QR Tekshiruvi
            try {
                $decrypted = Crypt::decryptString($request->auth_qr);
                $parts = explode('|', $decrypted);
                if (count($parts) !== 4)
                    throw new \Exception();

                [$orgHash, $userHash, $role, $secret] = $parts;
                if ($secret !== 't_a')
                    throw new \Exception();
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => "Yaroqsiz yoki begona QR-kod"], 400);
            }

            // 2. User & Org validatsiyasi
            $user = User::whereHas('organization', function ($q) use ($orgHash) {
                $q->where('auth', $orgHash)->where('block', 0);
            })->where('auth', $userHash)
                ->where('role', $role)
                ->first();

            if (!$user || $user->block == 1) {
                return response()->json([
                    'status' => "access_denied",
                    'success' => false,
                    'message' => 'Admin topilmadi yoki bloklangan'
                ], 403);
            }


            // 3. SESSIA LIMITI (Jami sessiyalar sonini tekshirish)
            $sessionCount = AccountSession::where('user_id', $user->id)->count();
            if ($sessionCount >= 10) {
                return response()->json([
                    'status' => "session_limit",
                    'success' => false,
                    'message' => "Sessiyalar limiti (10 ta) to'lgan!"
                ], 403);
            }



            // 4. Qurilmani tanish (Avval kirgan va Active bo'lsa)
            $existingDevice = AccountSession::where('user_id', $user->id)
                ->where('device_id', $request->device_id)
                ->whereNotIn('status', ['pending', 'block', 'banned'])
                ->first();
            if ($existingDevice) {
                $existingDevice->update([
                    'last_activity_at' => now(),
                    'ip_address' => $request->ip()
                ]);

                return $this->generateUserToken($user);
            }


            // 5. Asosiy qurilmani aniqlash (Lock for update)
            $activeMainSession = AccountSession::where('user_id', $user->id)
                ->where('main_account', 1)
                ->lockForUpdate()
                ->first();

            if (!$activeMainSession) {
                // Birinchi marta kirish: Asosiy qurilma sifatida ro'yxatga olish
                $session = AccountSession::create([
                    'org_id' => $user->org_id,
                    'user_id' => $user->id,
                    'device_id' => $request->device_id,
                    'device_type' => $request->device_type ?? 'unknown',
                    'device_name' => $request->device_name ?? 'unknown',
                    'ip_address' => $request->ip(),
                    'location' => $request->location,
                    'fcm_token' => $request->fcm_token,
                    'is_active' => 1,
                    'main_account' => 1,
                    'login_at' => now(),
                    'last_activity_at' => now(),
                    'seen' => 1,
                    'status' => 'accepted',
                ]);

                $user->update([
                    'session_id' => $session->id,
                    'logged_in' => 1,
                ]);

                return $this->generateUserToken($user);
            } else {
                // Ikkinchi qurilma: Kutish rejimiga o'tkazish
                $session = AccountSession::updateOrCreate(
                    ['user_id' => $user->id, 'device_id' => $request->device_id],
                    [
                        'org_id' => $user->org_id,
                        'device_type' => $request->device_type ?? 'unknown',
                        'device_name' => $request->device_name ?? 'unknown',
                        'ip_address' => $request->ip(),
                        'location' => $request->location,
                        'fcm_token' => $request->fcm_token,
                        'is_active' => 0,
                        'main_account' => 0,
                        'token' => Str::uuid(),
                        'login_at' => now(),
                        'last_activity_at' => now(),
                        'seen' => 0,
                        'status' => 'pending',
                    ]
                );

                // Firebase Push Notification jo'natish mantiqi
                // Bu yerda $activeMainSession qurilmasiga xabar ketadi.
                // Masalan: "Yangi qurilma (iPhone 13) orqali kirishga ruxsat berasizmi?"

                return response()->json([
                    'status' => "new_device_login",
                    'success' => false,
                    'token' => $session->token,
                    'main_device_name' => $activeMainSession->device_name,
                ], 409);
            }
        });
    }

    private function generateUserToken($user)
    {
        // 1. Sanctum tokenlarini tozalash va yangi yaratish
        $user->tokens()->where('name', 'mobile_token')->delete();
        $sanctumToken = $user->createToken('mobile_token')->plainTextToken;

        // 2. O'zingiz yaratgan FirebaseService orqali Custom Token yaratish
        // Bu erda app(FirebaseService::class) siz yaratgan Service-ni chaqiradi
        $firebaseService = app(\App\Services\Firebase\FirebaseService::class);
        $firebaseToken = $firebaseService->createCustomToken((string) $user->id);

        return response()->json([
            'success' => true,
            'token' => $sanctumToken,           // Sanctum API uchun
            'firebase_token' => $firebaseToken, // Firestore uchun (String formatda)
            'message' => 'Xush kelibsiz!'
        ]);
    }

    public function google_login(Request $request)
    {
        $request->validate([
            'id_token' => 'required|string',
        ]);

        try {
            // ✅ BARCHA RUXSAT ETILGAN CLIENT ID'LAR
            $client = new GoogleClient([
                'client_id' => [
                    config('services.google.web_client_id'),
                    config('services.google.android_client_id'),
                    config('services.google.ios_client_id'),
                ],
            ]);

            $payload = $client->verifyIdToken($request->id_token);

            if (!$payload) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Google token',
                ], 401);
            }

            if (($payload['email_verified'] ?? false) !== true) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email not verified',
                ], 401);
            }

            $user = User::firstOrCreate(
                ['email' => $payload['email']],
                [
                    'name' => $payload['name'] ?? $payload['email'],
                    'role' => 'customer',
                    'provider' => 'google',
                    'provider_id' => $payload['sub'],
                    'active' => 1,
                    'auth' => Str::random(40),
                ]
            );

            $token = $user
                ->createToken('customer-token')
                ->plainTextToken;

            return response()->json([
                'success' => true,
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
            ]);

        } catch (\Throwable $e) {
            Log::error('GOOGLE LOGIN ERROR', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Google login failed',
            ], 500);
        }
    }

    public function check_session(Request $request)
    {
        // Token orqali sessiyani topish
        $session = AccountSession::where('token', $request->bearerToken())->first();

        if (!$session) {
            return response()->json(['status' => 'not_found'], 404);
        }

        if ($session->status === 'accepted') {
            $user = User::find($session->user_id);

            // 1. Sanctum Token yaratish
            $user->tokens()->where('name', 'token_' . $session->device_id)->delete();
            $finalToken = $user->createToken('token_' . $session->device_id)->plainTextToken;

            // 2. 🔥 Firebase Custom Token yaratish (O'zingizning FirebaseService orqali)
            $firebaseService = app(\App\Services\Firebase\FirebaseService::class);
            $firebaseToken = $firebaseService->createCustomToken((string) $user->id);

            // Sessiyani yangilash
            $session->update(['status' => 'active', 'token' => null]);

            return response()->json([
                'status' => 'accepted',
                'token' => $finalToken,           // Sanctum token
                'firebase_token' => $firebaseToken, // Firestore uchun token 🔥
                'message' => 'Muvaffaqiyatli kirdingiz!'
            ]);
        }

        return response()->json([
            'status' => $session->status,
            'message' => 'Status tekshirildi'
        ]);
    }

    public function logout(Request $request)
    {
        // 1. Joriy ishlatilayotgan tokenni o'chirish
        $request->user()->currentAccessToken()->delete();

        dd($request);

        return response()->json(['message' => 'Logged out successfully']);
    }


}
