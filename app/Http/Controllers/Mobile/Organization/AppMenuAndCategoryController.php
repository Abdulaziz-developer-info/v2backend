<?php

namespace App\Http\Controllers\Mobile\Organization;

use App\Http\Controllers\Controller;
use App\Models\AppMenuCategory;
use App\Models\AppMenuProduct;
use Illuminate\Http\Request;

class AppMenuAndCategoryController extends Controller
{
    public function categories($org_id)
    {
        $categories = AppMenuCategory::where('org_id', $org_id)->get();
        return response()->json($categories);
    }

    public function products($org_id)
    {
        $products = AppMenuProduct::where('org_id', $org_id)->get();
        return response()->json($products);
    }






















    public function menu_data($org_id)
    {
        $categories = AppMenuCategory::where('org_id', $org_id)->get();
        $menus = AppMenuProduct::where('org_id', $org_id)->get();
        return response()->json([
            'categories' => $categories,
            'menus' => $menus
        ]);
    }
}
