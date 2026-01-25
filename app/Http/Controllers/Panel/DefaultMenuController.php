<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\PanelDefaultMenu;
use App\Models\PanelDefaultMenuCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class DefaultMenuController extends Controller
{
    /* =======================
        INDEX
    ======================== */
    public function index(Request $request)
    {
        // 1) Categorylar
        $categories = PanelDefaultMenuCategory::orderBy('name')->get();

        // 2) Tanlangan category (null bo‘lishi mumkin)
        $categoryId = $request->query('category_id');

        // 3) Menu query
        $menus = PanelDefaultMenu::select(
            'id',
            'name',
            'price',
            'image',
            'description',
            'category_id'
        )
            ->when($categoryId !== null, function ($query) use ($categoryId) {
                // tugma bosilganda
                $query->where('category_id', $categoryId);
            }, function ($query) {
                // boshida: category_id = 0 yoki NULL
                $query->where(function ($q) {
                    $q->whereNull('category_id')
                        ->orWhere('category_id', 0);
                });
            })
            ->orderByDesc('id')
            ->paginate(30)
            ->withQueryString();

        return view(
            'panel.default_menu.index',
            compact('menus', 'categories', 'categoryId')
        );
    }



    /* =======================
        MENU STORE
    ======================== */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'category_id' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $data['creator'] = auth('admin')->user()->name;

        if ($request->hasFile('image')) {

            $manager = new ImageManager(new Driver());

            $image = $manager
                ->read($request->file('image'))
                ->cover(600, 600)
                ->toWebp(80);

            $fileName = uniqid('menu_') . '.webp';
            $path = 'default-menus/' . $fileName;

            Storage::disk('public')->put($path, $image);

            $data['image'] = $path;
        }
        $data['creator'] = auth()->guard('admin')->user()?->name ?? 'System';
        PanelDefaultMenu::create($data);

        return back()->with('success', 'Menu saqlandi');
    }

    /* =======================
        MENU UPDATE
    ======================== */
    public function update(Request $request, PanelDefaultMenu $defaultMenu)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'category_id' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        if ($request->hasFile('image')) {

            if ($defaultMenu->image && Storage::disk('public')->exists($defaultMenu->image)) {
                Storage::disk('public')->delete($defaultMenu->image);
            }

            $manager = new ImageManager(new Driver());

            $image = $manager
                ->read($request->file('image'))
                ->cover(600, 600)
                ->toWebp(80);

            $fileName = uniqid('menu_') . '.webp';
            $path = 'default-menus/' . $fileName;

            Storage::disk('public')->put($path, $image);

            $data['image'] = $path;
        }

        $defaultMenu->update($data);

        return back()->with('success', 'Menu yangilandi');
    }

    /* =======================
        MENU DELETE
    ======================== */
    public function destroy(PanelDefaultMenu $defaultMenu)
    {
        if ($defaultMenu->image && Storage::disk('public')->exists($defaultMenu->image)) {
            Storage::disk('public')->delete($defaultMenu->image);
        }

        $defaultMenu->delete();

        return back()->with('success', "Menu o'chirildi");
    }

    /* =======================
        CATEGORY STORE
    ======================== */
    public function categoryStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        PanelDefaultMenuCategory::create([
            'name' => $request->name,
            'creator' => auth('admin')->user()->name,
        ]);

        return back()->with('success', "Kategoriya qo'shildi");
    }

    /* =======================
        CATEGORY UPDATE
    ======================== */
    public function categoryUpdate(Request $request, PanelDefaultMenuCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Kategoriya yangilandi');
    }

    /* =======================
        CATEGORY DELETE
    ======================== */
    public function categoryDestroy(PanelDefaultMenuCategory $category)
    {
        // category ishlatilgan bo'lsa, menu’lardan uzamiz
        PanelDefaultMenu::where('category_id', $category->id)->update([
            'category_id' => null,
        ]);

        $category->delete();

        return back()->with('success', "Kategoriya o'chirildi");
    }
}
