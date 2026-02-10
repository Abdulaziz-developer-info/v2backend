<?php

namespace App\Http\Controllers\Mobile\Organization;

use App\Http\Controllers\Controller;
use App\Models\OrgDiscount;
use App\Models\OrgSettings;
use App\Services\Firebase\FirebaseService;
use Illuminate\Http\Request;

class AppOrgDiscountController extends Controller
{
    public function discount_all($org_id)
    {
        $discount = OrgDiscount::where('org_id', $org_id)->get();
        return response()->json($discount);
    }

    public function discount_create(Request $request, $org_id)
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

        $discount = OrgDiscount::create($data);
        return response()->json($discount);
    }

    public function discount_edit(Request $request, $org_id, $discount_id)
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
        $discount = OrgDiscount::where('id', $discount_id)->first();

        if (!$discount) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $discount->update($data); // Bu yerda true/false qaytadi, lekin $discount o'zgaruvchisi yangilanadi

        // 4. Yangilangan obyektni qaytaramiz
        return response()->json($discount);
    }

    public function discount_delete(Request $request, $org_id, $discount_id)
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
        $discount = OrgDiscount::find($discount_id);

        if (!$discount) {
            return response()->json(['message' => 'discount not found'], 404);
        }
        $discount->update(['deleted_at' => now()->toDateTimeString()]);
        $discount->isSoftDeletable();
        return response()->json($discount);
    }
}
