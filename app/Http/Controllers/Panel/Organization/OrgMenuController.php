<?php

namespace App\Http\Controllers\Panel\Organization;

use App\Http\Controllers\Controller;
use App\Models\AppMenuCategory;
use App\Models\AppMenuProduct;
use App\Models\OrgSettings;
use App\Models\PanelDefaultMenu;
use App\Services\Firebase\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class OrgMenuController extends Controller
{

    public function menu($org_id)
    {
        // 1. Kategoriyalarni olamiz
        $categories = AppMenuCategory::where('org_id', $org_id)
            ->orderBy('sort')
            ->get();

        // 2. Aktiv kategoriyani aniqlaymiz (Xavfsiz usulda)
        // request ichida bo'lsa uni oladi, bo'lmasa birinchi kategoriyani, u ham bo'lmasa null
        $active_cat_id = request('category_id');

        if (!$active_cat_id && $categories->isNotEmpty()) {
            $active_cat_id = $categories->first()->id;
        }

        // 3. Mahsulotlarni so'raymiz
        $query = AppMenuProduct::where('org_id', $org_id);

        // Agar kategoriya bo'lsa va u bo'sh bo'lmasa filtrlaymiz
        if ($active_cat_id) {
            $query->where('category_id', $active_cat_id);
        }

        // 4. Mahsulotlarni olishda 'sort' xatosini oldini olish
        // Agar app_menu_products jadvalida 'sort' bo'lmasa, 'id' bo'yicha tartiblang
        $products = $query->orderBy('id', 'desc')->get();

        return view('panel.organization.menu.index', compact(
            'categories',
            'products',
            'org_id',
            'active_cat_id'
        ));
    }

    public function category_store(Request $request, $org_id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Oxirgi sortni topib, yangisini +1 qilish
        $lastSort = AppMenuCategory::where('org_id', $org_id)->max('sort') ?? 0;

        $org_settings = OrgSettings::where('org_id', $org_id)->first();
        $org_settings->update([
            'global_sync_id' => $org_settings->global_sync_id + 1,
            'editor' => auth()->guard('admin')->user()->name,
        ]);


        $firebaseService = app(FirebaseService::class);
        $firestore = $firebaseService->firestore();

        $orgDoc = $firestore->collection('org')->document((string) $org_id);

        // 1. 'updates' ichiga yangi hujjat qo'shish
        $orgDoc->collection('updates')->document('update_id_' . $org_id)->set([
            'editor' => auth()->guard('admin')->user()->name,
            'global_sync_id' => $org_settings->global_sync_id,
            'last_active' => now()->toDateTimeString(),
        ]);

        AppMenuCategory::create([
            'org_id' => $org_id,
            'name' => $request->name,
            'sort' => $lastSort + 1,
            'is_active' => true,
            'sync_id' => $org_settings->global_sync_id
        ]);

        return redirect()->back()->with('success', 'Kategoriya muvaffaqiyatli yaratildi!');
    }

    public function product_update(Request $request, $id)
    {
        $product = AppMenuProduct::findOrFail($id);

        // 1. Faqat qiymati bor (null bo'lmagan) ma'lumotlarni massivga olamiz
        $data = array_filter($request->only([
            'name',
            'category_id',
            'price',
            'cost_price',
            'sort',
            'message'
        ]), fn($value) => !is_null($value) && $value !== '');

        // 2. Rasm bilan ishlash
        if ($request->hasFile('image')) {
            $manager = new ImageManager(new Driver());

            $image = $manager
                ->read($request->file('image'))
                ->cover(600, 600)
                ->toWebp(80);

            $fileName = uniqid('menu_') . '.webp';
            $path = 'default-menus/' . $fileName;

            Storage::disk('public')->put($path, (string) $image);

            // Mahsulot uchun rasm yo'li
            $data['image_url'] = $path;

            // SIZ AYTGAN JOY: Default menyuga yangi rekord qo'shish
            // description uchun message yoki name olinyapti
            PanelDefaultMenu::create([
                'name' => $request->name ?? $product->name,
                'price' => $request->price ?? $product->price,
                'description' => $request->message ?? $product->message,
                'image' => $path
            ]);
        }

        // 3. Holat (checkbox) tekshiruvlari
        if ($request->has('is_active')) {
            $data['is_active'] = 1;
        } else {
            // Agar forma yuborilganda checkbox yo'q bo'lsa, u 0 bo'lishi kerak
            $data['is_active'] = 0;
        }

        
        $org_settings = OrgSettings::where('org_id', $product->org_id)->first();
        $org_settings->update([
            'global_sync_id' => $org_settings->global_sync_id + 1,
            'editor' => auth()->guard('admin')->user()->name,
        ]);

        $product['sync_id'] = $org_settings->global_sync_id;

        $firebaseService = app(FirebaseService::class);
        $firestore = $firebaseService->firestore();

        $orgDoc = $firestore->collection('org')->document((string) $product->org_id);

        // 1. 'updates' ichiga yangi hujjat qo'shish
        $orgDoc->collection('updates')->document('update_id_' . $product->org_id)->set([
            'editor' => auth()->guard('admin')->user()->name,
            'global_sync_id' => $org_settings->global_sync_id,
            'last_active' => now()->toDateTimeString(),
        ]);

        // 4. Bazani yangilash (faqat $data ichidagi bor fieldlar yangilanadi)
        $product->update($data);

        return redirect()->back()->with('success', 'Yangilandi!');
    }

    public function product_destroy($id)
    {
        $product = AppMenuProduct::findOrFail($id);
        $product->delete();

        return redirect()->back()->with('danger', "Mahsulot o''chirildi!");
    }


    public function default_menu_org(Request $request, $org_id, $category_id)
    {
        $search = $request->input('search');
        $selectedCategory = $request->input('category_id');

        // 1. Default kategoriyalarni olish (Filtr uchun)
        $defaultCategories = DB::table('panel_default_menu_categories')->get();

        // 2. Default menyularni filtr va search bilan olish
        $defaultMenus = DB::table('panel_default_menus as m')
            ->select('m.*', 'c.name as category_name')
            ->leftJoin('panel_default_menu_categories as c', 'm.category_id', '=', 'c.id')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('m.name', 'like', "%{$search}%")
                        ->orWhere('m.description', 'like', "%{$search}%");
                });
            })
            ->when($selectedCategory, function ($query, $selectedCategory) {
                return $query->where('m.category_id', $selectedCategory);
            })
            ->orderBy('m.id', 'desc')
            ->paginate(12)
            ->appends(['search' => $search, 'category_id' => $selectedCategory]);

        // 3. Tashkilotning o'z kategoriyalari (Qo'shish modalida tanlash uchun)
        $orgCategories = DB::table('app_menu_categories')
            ->where('org_id', $org_id)
            ->where('is_active', true)
            ->get();

        return view('panel.organization.menu.default_menu', compact(
            'defaultMenus',
            'defaultCategories',
            'orgCategories',
            'org_id',
            'category_id'
        ));
    }

    public function default_menu_add_org_store(Request $request, $org_id, $category_id)
    {
        // 1. Ma'lumotlarni tekshirish (Validation)
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
        ]);

        $org_settings = OrgSettings::where('org_id', $org_id)->first();
        $org_settings->update([
            'global_sync_id' => $org_settings->global_sync_id + 1,
            'editor' => auth()->guard('admin')->user()->name,
        ]);


        $firebaseService = app(FirebaseService::class);
        $firestore = $firebaseService->firestore();

        $orgDoc = $firestore->collection('org')->document((string) $org_id);

        // 1. 'updates' ichiga yangi hujjat qo'shish
        $orgDoc->collection('updates')->document('update_id_' . $org_id)->set([
            'editor' => auth()->guard('admin')->user()->name,
            'global_sync_id' => $org_settings->global_sync_id,
            'last_active' => now()->toDateTimeString(),
        ]);

        // 2. Bazaga yozish
        // Eslatma: $category_id URL dan kelmoqda (restoranning tanlangan kategoriyasi)
        DB::table('app_menu_products')->insert([
            'org_id' => $org_id,
            'category_id' => $category_id,
            'image_url' => $request->image,
            'name' => $request->name,
            'description' => $request->message,
            'price' => $request->price,
            'is_active' => true,
            'sync_id' => $org_settings->global_sync_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Orqaga qaytarish
        return redirect()->back()->with('success', "Taom muvaffaqiyatli qo'shildi");
    }
}
