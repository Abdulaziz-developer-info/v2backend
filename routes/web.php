<?php

use App\Http\Controllers\Panel\Admin\AdminController;
use App\Http\Controllers\Panel\Admin\DashboardController;
use App\Http\Controllers\Panel\DefaultMenuController;
use App\Http\Controllers\Panel\Organization\OrganizationController;
use App\Http\Controllers\Panel\Organization\OrganizationStaffController;
use App\Http\Controllers\Panel\Organization\OrgMenuController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;



Route::get('/create', function () {
    \App\Models\Admin::create([
        'name' => 'admin',
        'password' => bcrypt('admin'),
    ]);
    return redirect()->route('admin.login');
});


Route::get('/login', [AdminController::class, 'login_page'])->name('admin.login');
Route::post('/login', [AdminController::class, 'login'])->name('admin.login.submit');


Route::middleware('admin.auth')->group(function () {
    Route::get('/', [DashboardController::class, 'dashboard'])->name('admin.dashboard');

    Route::prefix('admins')->name('admins.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::post('/', [AdminController::class, 'store'])->name('store');
        Route::put('/{admin}', [AdminController::class, 'update'])->name('update');
        Route::delete('/{admin}', [AdminController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('default-menus')->name('default-menus.')->group(function () {
        Route::get('/', [DefaultMenuController::class, 'index'])->name('index');
        Route::post('/', [DefaultMenuController::class, 'store'])->name('store');
        Route::put('/{defaultMenu}', [DefaultMenuController::class, 'update'])->name('update');
        Route::delete('/{defaultMenu}', [DefaultMenuController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('default-menus-categories')->name('categories.')->group(function () {
        Route::post('/', [DefaultMenuController::class, 'categoryStore'])->name('store');
        Route::put('/{category}', [DefaultMenuController::class, 'categoryUpdate'])->name('update');
        Route::delete('/{category}', [DefaultMenuController::class, 'categoryDestroy'])->name('destroy');
    });

    Route::prefix('organizations')->name('organizations.')->group(function () {
        Route::get('/', [OrganizationController::class, 'index'])->name('index');
        Route::get('/create', [OrganizationController::class, 'create'])->name('create');
        Route::post('/', [OrganizationController::class, 'store'])->name('store');
        Route::get('/{organization}', [OrganizationController::class, 'show'])->name('show');
        Route::get('/{organization}/edit', [OrganizationController::class, 'edit'])->name('edit');
        Route::put('/{organization}', [OrganizationController::class, 'update'])->name('update');
        Route::delete('/{organization}', [OrganizationController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('organization/staff')->name('organization_staff.')->group(function () {
        Route::get('/{org_id}', [OrganizationStaffController::class, 'index'])->name('org_staff');
        Route::get('/view/{org_id}/{staff_id}', [OrganizationStaffController::class, 'view'])->name('view');
        Route::post('/create', [OrganizationStaffController::class, 'create'])->name('create');
        Route::put('/update/{org_id}/{staff_id}', [OrganizationStaffController::class, 'update'])->name('update');
        Route::get('/auth-refresh/{org_id}/{staff_id}', [OrganizationStaffController::class, 'auth_edit'])->name('auth_edit');
        Route::delete('/destroy/{org_id}/{staff_id}', [OrganizationStaffController::class, 'destroy'])->name('destroy');

        Route::prefix('sessions')->name('sessions.')->group(function () {
            Route::post('/edit/{session_id}', [OrganizationStaffController::class, 'editSession'])->name('editSession');
            Route::delete('/destroy/{session_id}', [OrganizationStaffController::class, 'destroySession'])->name('destroySession');
        });
    });

    Route::prefix('organization/menu/')->name('organization_menu.')->group(function () {
        Route::get('menu/{org_id}', [OrgMenuController::class, 'menu'])->name('menu');
        Route::post('category/store/{org_id}', [OrgMenuController::class, 'category_store'])->name('category_store');
        Route::post('product/update/{id}', [OrgMenuController::class, 'product_update'])->name('product_update');
        Route::delete('product/delete/{id}', [OrgMenuController::class, 'product_destroy'])->name('product_destroy');


        Route::get('default/menu/org/{org_id}/{category_id}', [OrgMenuController::class, 'default_menu_org'])->name('default_menu_org');
        Route::post('/default/menu/add/org/store/{org_id}/{category_id}', [OrgMenuController::class, 'default_menu_add_org_store'])
            ->name('default_menu_add_org_store');
    });

    Route::get('/organization/staff/notlification/{id}', [TestController::class, 'sendNotification']);

});

