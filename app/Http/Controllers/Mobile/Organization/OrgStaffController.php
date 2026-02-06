<?php

namespace App\Http\Controllers\Mobile\Organization;

use App\Http\Controllers\Controller;
use App\Models\OrgSettings;
use App\Models\User;
use App\Services\Firebase\FirebaseService;
use Illuminate\Http\Request;

class OrgStaffController extends Controller
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
        $data['sync_id'] = $org_settings->global_sync_id;

        $firebaseService = app(FirebaseService::class);
        $firestore = $firebaseService->firestore();

        $orgDoc = $firestore->collection('org')->document((string) $org_id);

        $orgDoc->collection('updates')->document('update_id_' . $org_id)->set([
            'editor' => $user->name,
            'global_sync_id' => $org_settings->global_sync_id,
            'last_active' => now()->toDateTimeString(),
        ]);

        $table = User::create($data);
        return response()->json($table);
    }

    public function staff_edit(Request $request, $org_id, $staff_id)
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

        $staff = User::where('id', $staff_id)->first()->update($data);
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

        $table = User::find($staff_id);
        $table->delete();
        return response()->json($table);
    }
}
