<?php

namespace App\Http\Controllers\Mobile\Organization;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\OrgSettings;
use Illuminate\Http\Request;

class AppOrganizationController extends Controller
{
    public function organization_info(Request $request)
    {
        $user = $request->user();
        $data = Organization::where('id', $user->org_id)->get();
        return response()->json($data);
    }
    public function organization_info_find($id)
    {
        $data = Organization::find($id);
        return response()->json($data);
    }
    public function organization_update(Request $request, $id)
    {
        $data = Organization::find($id);
        $data->update($request->all());
        return response()->json($data);
    }


    // Org sttings ...

    public function organization_settings(Request $request)
    {
        $user = $request->user();
        $data = OrgSettings::where('id', $user->org_id)->get();
        return response()->json($data);
    }

    public function setting_update(Request $request)
    {
        $orgId = $request->user()->org_id;

        $settings = OrgSettings::where('org_id', $orgId)->first();
        $newSyncId = ($settings?->global_sync_id ?? 0) + 1;

        // Faqat kerakli maydonlarni ajratib olamiz
        $inputData = $request->only(['wifi_name', 'wifi_ip', 'sync_id' => $newSyncId]);

        $data = OrgSettings::updateOrCreate(
            ['org_id' => $orgId],
            array_merge($inputData, [
                'global_sync_id' => $newSyncId,
                'org_id' => $orgId
            ])
        );
        return response()->json($data);
    }
}
