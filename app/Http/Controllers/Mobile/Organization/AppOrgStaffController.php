<?php

namespace App\Http\Controllers\Mobile\Organization;

use App\Http\Controllers\Controller;
use App\Models\OrgSettings;
use App\Models\User;
use App\Services\Firebase\FirebaseService;
use Illuminate\Http\Request;

class AppOrgStaffController extends Controller
{
    public function staff_all(Request $request, $org_id)
    {
        $staff = User::where('org_id', $org_id)->where('deleted_at', null)->get();
        return response()->json($staff);
    }

    public function staff_create(Request $request, $org_id)
    {
        $user = $request->user();
        $data = $request->all();

        $org_settings = OrgSettings::where('org_id', $org_id)->first();
        $org_settings->update([
            'global_sync_id' => $org_settings->global_sync_id + 1,
            'editor' => $user->name,
        ]);
        $data['auth'] = bin2hex(random_bytes(10));
        $data['sync_id'] = $org_settings->global_sync_id;

        $firebaseService = app(FirebaseService::class);
        $firestore = $firebaseService->firestore();

        $orgDoc = $firestore->collection('org')->document((string) $org_id);

        $orgDoc->collection('updates')->document('update_id_' . $org_id)->set([
            'editor' => $user->name,
            'global_sync_id' => $org_settings->global_sync_id,
            'last_active' => now()->toDateTimeString(),
        ]);

        $staff = User::create($data);
        return response()->json($staff);
    }

    public function staff_edit(Request $request, $org_id, $staff_id)
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
        $staff = User::where('id', $staff_id)->first();

        if (!$staff) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $staff->update($data); // Bu yerda true/false qaytadi, lekin $staff o'zgaruvchisi yangilanadi

        // 4. Yangilangan obyektni qaytaramiz
        return response()->json($staff);
    }

    public function staff_delete(Request $request, $org_id, $staff_id)
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
        $staff = User::find($staff_id);

        if (!$staff) {
            return response()->json(['message' => 'Staff not found'], 404);
        }
        $staff->update(['deleted_at' => now()->toDateTimeString()]);
        $staff->delete();
        return response()->json($staff);
    }
}
