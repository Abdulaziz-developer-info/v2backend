<?php

namespace App\Http\Controllers\Mobile\Organization;

use App\Http\Controllers\Controller;
use App\Models\OrgPayment;
use App\Models\OrgSettings;
use App\Services\Firebase\FirebaseService;
use Illuminate\Http\Request;

class AppOrgPaymentController extends Controller
{
    public function payment_all($org_id)
    {
        $payment = OrgPayment::where('org_id', $org_id)->get();
        return response()->json($payment);
    }

    public function payment_create(Request $request, $org_id)
    {
        $user = $request->user();
        $data = $request->all();

        $org_settings = OrgSettings::where('org_id', $org_id)->first();
        $org_settings->update([
            'global_sync_id' => $org_settings->global_sync_id + 1,
            'editor' => $user->name,
        ]);
        $data['sync_id'] = $org_settings->global_sync_id;

        $firebaseService = app(FirebaseService::class);
        $firestore = $firebaseService->firestore();

        $orgDoc = $firestore->collection('org')->document((string) $org_id);

        $orgDoc->collection('updates')->document('update_id_' . $org_id)->set([
            'editor' => $user->name,
            'global_sync_id' => $org_settings->global_sync_id,
            'last_active' => now()->toDateTimeString(),
        ]);

        $payment = OrgPayment::create($data);
        return response()->json($payment);
    }

    public function payment_edit(Request $request, $org_id, $payment_id)
    {
        $user = $request->user();
        $data = $request->all();

        // 1. Tashkilot sozlamalarini yangilash
        $org_settings = OrgSettings::where('org_id', $org_id)->first();
        $org_settings->update([
            'global_sync_id' => $org_settings->global_sync_id + 1,
            'editor' => $user->name,
        ]);

        // Yangi sync_id ni ma'lumotlarga qo'shamiz
        $data['sync_id'] = $org_settings->global_sync_id;

        // 2. Firebase/Firestore qismi
        $firebaseService = app(FirebaseService::class);
        $firestore = $firebaseService->firestore();
        $orgDoc = $firestore->collection('org')->document((string) $org_id);

        $orgDoc->collection('updates')->document('update_id_' . $org_id)->set([
            'editor' => $user->name,
            'global_sync_id' => $org_settings->global_sync_id,
            'last_active' => now()->toDateTimeString(),
        ]);

        // 3. MUHIM QISMI: Userni topamiz va yangilaymiz
        $payment = OrgPayment::where('id', $payment_id)->first();

        if (!$payment) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $payment->update($data); // Bu yerda true/false qaytadi, lekin $payment o'zgaruvchisi yangilanadi

        // 4. Yangilangan obyektni qaytaramiz
        return response()->json($payment);
    }

    public function payment_delete(Request $request, $org_id, $payment_id)
    {
        $user = $request->user();
        $org_settings = OrgSettings::where('org_id', $org_id)->first();
        $org_settings->update([
            'global_sync_id' => $org_settings->global_sync_id + 1,
            'editor' => $user->name,
        ]);
        $firebaseService = app(FirebaseService::class);
        $firestore = $firebaseService->firestore();
        $orgDoc = $firestore->collection('org')->document((string) $org_id);
        $orgDoc->collection('updates')->document('update_id_' . $org_id)->set([
            'editor' => $user->name,
            'global_sync_id' => $org_settings->global_sync_id,
            'last_active' => now()->toDateTimeString(),
        ]);
        $payment = OrgPayment::find($payment_id);

        if (!$payment) {
            return response()->json(['message' => 'payment not found'], 404);
        }
        $payment->update(['deleted_at' => now()->toDateTimeString()]);
        $payment->isSoftDeletable();
        return response()->json($payment);
    }
}
