<?php

namespace App\Http\Controllers\Mobile\Organization;

use App\Http\Controllers\Controller;
use App\Models\AppMenuCategory;
use App\Models\AppMenuProduct;
use App\Models\Organization;
use App\Models\OrgSettings;
use App\Services\Firebase\FirebaseService;
use Illuminate\Http\Request;

class AppOrganizationController extends Controller
{

    public function global_data_sync(Request $request, $org_id)
    {
        $local_sync_id = $request->input('local_sync_id', 0);

        $org_settings_data = OrgSettings::where('org_id', $org_id)->first();
        $org_settings = $org_settings_data->where('sync_id', '>', $local_sync_id)->first();

        $organization = Organization::where('sync_id', '>', $local_sync_id)
            ->find($org_id);

        $app_menu_category = AppMenuCategory::where('org_id', $org_id)
            ->where('sync_id', '>', $local_sync_id)
            ->get();
        $app_menu_product = AppMenuProduct::where('org_id', $org_id)
            ->where('sync_id', '>', $local_sync_id)
            ->get();

        return response()->json([
            'success' => true,
            'global_sync_id' => $org_settings_data->global_sync_id,
            'data' => [
                'org_settings' => $org_settings,
                'organization' => $organization,
                'app_menu_category' => $app_menu_category,
                'app_menu_product' => $app_menu_product,
            ]
        ]);
    }


    public function organization_info(Request $request)
    {
        $user = $request->user();
        $mainOrg = Organization::find($user->org_id);
        if (!$mainOrg) {
            return response()->json([], 404);
        }
        $multiBranchTypes = [
            'multi_branch_small_cafe',
            'multi_branch_fast_food',
            'multi_branch_restaurant',
            'multi_branch_small_shop',
            'multi_branch_big_shop',
            'multi_branch_market'
        ];

        if (in_array($mainOrg->org_type, $multiBranchTypes) && $mainOrg->branch_id) {
            $data = Organization::with('orgSettings')
                ->where('branch_id', $mainOrg->branch_id)
                ->get();
        } else {
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

        $org_settings = OrgSettings::where('org_id', $id)->first();
        $org_settings->update([
            'global_sync_id' => $org_settings->global_sync_id + 1,
        ]);

        $data['sync_id'] = $org_settings->global_sync_id;

        $data->update($request->all());

        // $firebaseService = app(FirebaseService::class);
        // $firestore = $firebaseService->firestore();

        // $orgDoc = $firestore->collection('org')->document((string) 1);

        // // 1. 'updates' ichiga yangi hujjat qo'shish
        // $orgDoc->collection('updates')->document('update_id_1')->set([
        //     'editor' => "Abdulaziz",
        //     'global_sync_id' => 4,
        //     'last_active' => now()->toDateTimeString(),
        // ]);

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
