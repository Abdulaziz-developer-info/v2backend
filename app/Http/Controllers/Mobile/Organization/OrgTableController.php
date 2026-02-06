<?php

namespace App\Http\Controllers\Mobile\Organization;

use App\Http\Controllers\Controller;
use App\Models\OrgSettings;
use App\Models\OrgTable;
use App\Models\OrgTableCategories;
use App\Services\Firebase\FirebaseService;
use Illuminate\Http\Request;

class OrgTableController extends Controller
{
    public function table_categories($org_id)
    {
        $categories = OrgTableCategories::where('org_id', $org_id)->get();
        return response()->json($categories);
    }
    public function table_categories_create(Request $request, $org_id)
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

        $category = OrgTableCategories::create($data);
        return response()->json($category);
    }

    public function table_categories_update(Request $request, $org_id, $category_id)
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

        $category = OrgTableCategories::find($category_id);
        $category->update($data);
        return response()->json($category);
    }

    public function table_categories_delete(Request $request, $org_id, $category_id)
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

        $category = OrgTableCategories::find($category_id);
        $category->delete();
        return response()->json($category);
    }



    public function table($org_id)
    {
        $table = OrgTable::where('org_id', $org_id)->get();
        return response()->json($table);
    }

    public function table_create(Request $request, $org_id)
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

        $table = OrgTable::create($data);
        return response()->json($table);
    }

    public function table_update(Request $request, $org_id, $id)
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

        $table = OrgTable::find($id);
        $table->update($data);
        return response()->json($table);
    }

    public function table_list_update(Request $request, $org_id)
    {
        $user = $request->user();
        $tablesData = $request->all(); // Bu yerda stollar massivi keladi

        // 1. Sync ID ni yangilash (Barcha stollar uchun bir xil bo'ladi)
        $org_settings = OrgSettings::where('org_id', $org_id)->first();
        $org_settings->update([
            'global_sync_id' => $org_settings->global_sync_id + 1,
            'editor' => $user->name,
        ]);

        $new_sync_id = $org_settings->global_sync_id;

        // 2. Firebase/Firestore sinxronizatsiyasi
        $firebaseService = app(FirebaseService::class);
        $firestore = $firebaseService->firestore();
        $orgDoc = $firestore->collection('org')->document((string) $org_id);

        $orgDoc->collection('updates')->document('update_id_' . $org_id)->set([
            'editor' => $user->name,
            'global_sync_id' => $new_sync_id,
            'last_active' => now()->toDateTimeString(),
        ]);

        $processedTables = [];

        // 3. Har bir stolni update yoki create qilish
        foreach ($tablesData as $data) {
            // Keraksiz yoki xavfli maydonlarni tozalash (agar bo'lsa)
            $id = $data['id'] ?? 0;

            // Bazada bor bo'lsa yangilaydi, yo'q bo'lsa yaratadi
            $table = OrgTable::updateOrCreate(
                [
                    'id' => $id > 0 ? $id : null, // ID bo'lsa o'sha bo'yicha qidiradi
                    'org_id' => $org_id
                ],
                [
                    'category_id' => $data['category_id'],
                    'name' => $data['name'],
                    'number' => $data['number'],
                    'capacity' => $data['capacity'],
                    'pos_x' => $data['pos_x'],
                    'pos_y' => $data['pos_y'],
                    'width' => $data['width'],
                    'height' => $data['height'],
                    'shape' => $data['shape'],
                    'border_radius' => $data['border_radius'],
                    'color' => $data['color'],
                    'is_active' => $data['is_active'] ?? true,
                    'service_type' => $data['service_type'] ?? 'none',
                    'service_value' => $data['service_value'] ?? 0,
                    'sync_id' => $new_sync_id, 
                ]
            );

            $processedTables[] = $table;
        }

        // 4. Barcha yangilangan/yaratilgan stollarni qaytaramiz
        return response()->json($processedTables);
    }

    public function table_delete(Request $request, $org_id, $id)
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

        $table = OrgTable::find($id);
        $table->delete();
        return response()->json($table);
    }

}
