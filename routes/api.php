<?php

use App\Http\Controllers\Mobile\AppAuthController;
use App\Http\Controllers\Mobile\Organization\AppMenuAndCategoryController;
use App\Http\Controllers\Mobile\Organization\AppOrganizationController;
use App\Http\Controllers\Mobile\Organization\OrgStaffController;
use App\Http\Controllers\Mobile\Organization\OrgTableController;
use App\Http\Controllers\Mobile\User\AppUserController;
use App\Http\Controllers\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/user', function (Request $request) {
    return response()->json($request->all());
});

// 1. Ochiq marshrut (Login uchun)
Route::post('/qr/login', [AppAuthController::class, 'qr_login']);
// ->middleware('throttle:5,1');
Route::post('/google/login', [AppAuthController::class, 'google_login']);
Route::get('/check/session', [AppAuthController::class, 'check_session']);

Route::get('/ping', function () {
    return response()->json(['ok' => true]);
});

// Professional approach: Use different middleware based on environment
$middleware = app()->environment('local') && PHP_OS_FAMILY === 'Windows'
    ? 'api.auth'
    : 'auth:sanctum';

Route::middleware($middleware)->group(function () {
    Route::get('/logout', [AppAuthController::class, 'logout']);
    Route::get('/admins', [AppAuthController::class, 'index']);

    Route::prefix('user/')->group(function () {
        Route::get('/info', [AppUserController::class, 'user_info']);
    });

    Route::prefix('organization')->group(function () {
        Route::post('/global/data/sync/{org_id}', [AppOrganizationController::class, 'global_data_sync']);

        Route::get('/info', [AppOrganizationController::class, 'organization_info']);
        Route::get('/info/{id}', [AppOrganizationController::class, 'organization_info_find']);
        Route::put('/update/{id}', [AppOrganizationController::class, 'organization_update']);

        Route::get('/settings', [AppOrganizationController::class, 'organization_settings']);
        Route::post('/setting/update', [AppOrganizationController::class, 'setting_update']);
    });

    Route::prefix('menu')->group(function () {
        Route::get('/categories/{org_id}', [AppMenuAndCategoryController::class, 'categories']);
        Route::post('/categories/create/{org_id}', [AppMenuAndCategoryController::class, 'categories_create']);
        Route::put('/categories/update/{org_id}/{category_id}', [AppMenuAndCategoryController::class, 'categories_update']);
        Route::delete('/categories/delete/{org_id}/{category_id}', [AppMenuAndCategoryController::class, 'categories_delete']);


        Route::get('/products/{org_id}', [AppMenuAndCategoryController::class, 'products']);
        Route::post('/products/crate/{org_id}', [AppMenuAndCategoryController::class, 'product_create']);
        Route::put('/products/update/{org_id}/{product_id}', [AppMenuAndCategoryController::class, 'product_update']);
        Route::delete('/products/delete/{org_id}/{product_id}', [AppMenuAndCategoryController::class, 'product_delete']);
    });

    Route::prefix('table')->group(function () {
        Route::get('/categories/{org_id}', [OrgTableController::class, 'table_categories']); 
        Route::post('/categories/create/{org_id}', [OrgTableController::class, 'table_categories_create']); 
        Route::put('/categories/update/{org_id}/{category_id}', [OrgTableController::class, 'table_categories_update']); 
        Route::delete('/categories/delete/{org_id}/{category_id}', [OrgTableController::class, 'table_categories_delete']); 

        Route::get('/{org_id}', [OrgTableController::class, 'table']); 
        Route::post('/create/{org_id}', [OrgTableController::class, 'table_create']); 
        Route::put('/update/{org_id}/{id}', [OrgTableController::class, 'table_update']); 
        Route::put('/list/update/{org_id}', [OrgTableController::class, 'table_list_update']); 
        Route::delete('/delete/{org_id}/{id}', [OrgTableController::class, 'table_delete']);
        Route::delete('/delete/{org_id}/{id}', [OrgTableController::class, 'table_delete']);
    });

    Route::prefix('staff')->group(function () {
        Route::get('/all/{org_id}', [OrgStaffController::class, 'staff_all']); 
        Route::get('/create/{org_id}', [OrgStaffController::class, 'staff_create']); 
        Route::get('/edit/{org_id}/{staff_id}', [OrgStaffController::class, 'staff_edit']); 
        Route::get('/delete/{org_id}/{staff_id}', [OrgStaffController::class, 'staff_delete']); 
    });
});


Route::get('/test', [TestController::class, 'storeData']);