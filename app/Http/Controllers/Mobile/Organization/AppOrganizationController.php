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

        // 1. Foydalanuvchining asosiy tashkilotini olamiz
        $mainOrg = Organization::find($user->org_id);

        if (!$mainOrg) {
            return response()->json([], 404);
        }

        // 2. "multi_branch" turidagi turlarni tekshiramiz
        $multiBranchTypes = [
            'multi_branch_small_cafe',
            'multi_branch_fast_food',
            'multi_branch_restaurant',
            'multi_branch_small_shop',
            'multi_branch_big_shop',
            'multi_branch_market'
        ];

        // 3. Agar turi tarmoqli bo'lsa va branch_id bo'lsa, hammasini olamiz
        if (in_array($mainOrg->org_type, $multiBranchTypes) && $mainOrg->branch_id) {
            $data = Organization::with('orgSettings')
                ->where('branch_id', $mainOrg->branch_id)
                ->get();
        } else {
            // Agar oddiy yakka tashkilot bo'lsa, faqat o'zini qaytaramiz
            $data = collect([$mainOrg->load('orgSettings')]);
        }

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
