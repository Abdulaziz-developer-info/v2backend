<?php

namespace App\Http\Controllers\Panel\Organization;

use App\Http\Controllers\Controller;
use App\Models\AccountSession;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class OrganizationStaffController extends Controller
{

    // Staff ro'yxati
    public function index($org_id)
    {
        $staffs = User::where('org_id', $org_id)->get();
        $organization = Organization::findOrFail($org_id);

        return view('panel.organization.staff.index', compact('staffs', 'organization', 'org_id'));
    }

    // Yangi staff yaratish
    public function create(Request $request)
    {
        $data = $request->validate([
            'org_id' => 'required|exists:organizations,id',
            'name' => 'required|string|max:255',
            'role' => 'required|string|in:admin,manager,cashier,waiter,cook',
            'phone' => 'required|string|max:20',
            'telegram' => 'nullable|string|max:100', // Majburiy emas deb belgilandi
            'instagram' => 'nullable|string|max:100', // Majburiy emas deb belgilandi
            'note' => 'nullable|string',         // Majburiy emas deb belgilandi
            'message' => 'nullable|string',         // Majburiy emas deb belgilandi
        ]);

        // Avtomatik generatsiya qilinadigan maydonlar
        $data['auth'] = bin2hex(random_bytes(10));
        $data['block'] = 0; // Yangi xodim doim faol bo'ladi

        // Parol yoki boshqa kerakli default qiymatlar bo'lsa shu yerda qo'shasiz
        // $data['password'] = Hash::make('12345678'); 

        User::create($data);

        return redirect()->back()->with('success', 'Yangi xodim muvaffaqiyatli qo\'shildi');
    }

    public function view($org_id, $staff_id)
    {
        $user = User::findOrFail($staff_id);
        $organization = Organization::findOrFail($org_id);
        $account_sessions = AccountSession::where('org_id', $org_id)->get();

        // Bu yerda seanslarni ham chaqirishingiz mumkin
        // $sessions = StaffSession::where('staff_id', $id)->latest()->get();

        return view('panel.organization.staff.view', compact('user', 'organization', 'account_sessions'));
    }

    // Staff tahrirlash
    public function update(Request $request, $org_id, $user_id)
    {
        $user = User::where('id', $user_id)->where('org_id', $org_id)->firstOrFail();

        $data = $request->validate([
            'name' => 'required|string',
            'role' => 'required|string',
            'phone' => 'required|string',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'telegram' => 'nullable|string',
            'instagram' => 'nullable|string',
            'whatsapp' => 'nullable|string',
            'twitter' => 'nullable|string',
            'facebook' => 'nullable|string',
            'note' => 'nullable|string',
            'message' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'password' => 'nullable|string',
        ]);

        // Block holati
        $data['block'] = $request->has('block') ? 1 : 0;
        $data['active'] = $request->has('active') ? 1 : 0;

        // Agar password bo‘lsa
        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        // Avatar upload va optimizatsiya (webp, 300x300) — admin misoliga mos
if ($request->hasFile('avatar')) {
    if ($user->avatar) {
        Storage::disk('public')->delete($user->avatar);
    }

    $image = Image::read($request->file('avatar'))
        ->cover(300, 300)
        ->toWebp(80);

    $fileName = uniqid('staff_') . '.webp';
    $path = 'staffs/' . $fileName;

    Storage::disk('public')->put($path, (string) $image);
    $data['avatar'] = $path;
}


        $plainPassword = null;
        if ($request->filled('password')) {
            $plainPassword = $request->password;
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Xodim ma\'lumotlari yangilandi')->with('staff_credentials', [
            'login' => $user->name,
            'password' => $plainPassword
        ]);
    }

    // Auth kodlarni yangilash (QR kodni o'zgartiradi)
    public function auth_edit($org_id, $staff_id)
    {
        $staff = User::where('id', $staff_id)->where('org_id', $org_id)->firstOrFail();
        $organization = Organization::findOrFail($org_id);

        $new_auth_staff = base_convert(time(), 10, 36) . Str::random(8);
        $new_auth_org = base_convert(time(), 10, 36) . Str::random(5);

        $staff->update(['auth' => $new_auth_staff]);
        $organization->update(['auth' => $new_auth_org]);

        return redirect()->back()->with('success', 'Xavfsizlik kalitlari va QR kod yangilandi');
    }

    // Staffni o'chirish
    public function destroy($org_id, $staff_id)
    {
        $staff = User::findOrFail($staff_id);
        $staff->delete();

        return redirect()->route('organization_staff.org_staff', $org_id);
    }

    /**
     * Sessiya
     */
    public function editSession(Request $request, $session_id)
    {
        // 1. Sessiyani topamiz
        $session = AccountSession::findOrFail($session_id);

        // 2. Statusni yangilaymiz (accepted, blocked, pending, banned)
        // Blade'dan kelayotgan statusga qarab
        $session->update([
            'status' => $request->status, // 'block' emas, migrationdagi 'status' ustuni
            'is_active' => ($request->status == 'accepted') // Faqat accepted bo'lsa active bo'ladi
        ]);

        $message = "Sessiya holati '" . $request->status . "' ga o'zgartirildi.";

        return back()->with('success', $message);
    }

    public function destroySession($session_id)
    {
        $session = AccountSession::findOrFail($session_id);
        $session->delete();

        return back()->with('success', "Sessiya muvaffaqiyatli o'chirildi.");
    }



}

