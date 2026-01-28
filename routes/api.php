<?php

use App\Http\Controllers\Mobile\AppAuthController;
use App\Http\Controllers\Mobile\Organization\AppMenuAndCategoryController;
use App\Http\Controllers\Mobile\Organization\AppOrganizationController;
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
        Route::get('/info', [AppOrganizationController::class, 'organization_info']);
        Route::get('/info/{id}', [AppOrganizationController::class, 'organization_info_find']);
        Route::put('/update/{id}', [AppOrganizationController::class, 'organization_update']);

        Route::get('/settings', [AppOrganizationController::class, 'organization_settings']);
        Route::post('/setting/update', [AppOrganizationController::class, 'setting_update']);
    });

    Route::prefix('menu')->group(function () {
        Route::get('/categories/{org_id}', [AppMenuAndCategoryController::class, 'categories']);
        Route::get('/products/{org_id}', [AppMenuAndCategoryController::class, 'products']);
 

        Route::get('/info/{id}', [AppOrganizationController::class, 'organization_info_find']);
        Route::put('/update/{id}', [AppOrganizationController::class, 'organization_update']);
    });
});


Route::get('/test', [TestController::class, 'storeData']);