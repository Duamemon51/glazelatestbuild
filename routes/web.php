<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\ProductTypeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\ProductExampleController;
use App\Http\Controllers\Admin\ParentCategoryController;
use App\Http\Controllers\Admin\DesignController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PageController;
/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'registerUser'])->name('register.submit');

    Route::get('/login', function () {
        return view('auth.login');
    })->name('login.form');
    Route::post('/login', [LoginController::class, 'login'])->name('login');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Pattern routes
    Route::resource('admin/patterns', \App\Http\Controllers\Admin\PatternController::class, ['as' => 'admin']);

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Admin root route - redirect to dashboard
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    // Existing resources
   Route::resource('parents', ParentCategoryController::class);
    Route::resource('subcategories', SubcategoryController::class);
    Route::resource('product-types', ProductTypeController::class);

    // AJAX route for dependent dropdown
    Route::get('subcategories/by-parent/{parentId}', [SubcategoryController::class, 'getByParent']);

    Route::resource('products', ProductController::class);
    Route::get('/products/{product}/3d', [ProductController::class, 'show3D'])->name('products.show3D');

    // ProductExample CRUD
    Route::resource('examples', ProductExampleController::class);
     Route::get('examples/{example}/3d', [ProductExampleController::class, 'show3D'])->name('examples.show3D');

    Route::resource('designs', DesignController::class);

    // Users management
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);

    // Orders management
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);

    // Reports
    Route::get('reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/generate', [\App\Http\Controllers\Admin\ReportController::class, 'generate'])->name('reports.generate');
    Route::get('reports/show', [\App\Http\Controllers\Admin\ReportController::class, 'show'])->name('reports.show');

    // Color Management
    Route::resource('color-categories', \App\Http\Controllers\Admin\ColorCategoryController::class);
    Route::resource('colors', \App\Http\Controllers\Admin\ColorController::class);

    // CMS Management
    Route::prefix('cms')->name('cms.')->group(function () {
        // Menu Management
        Route::resource('menus', \App\Http\Controllers\MenuController::class);
        Route::post('menus/{menu}/order', [\App\Http\Controllers\MenuController::class, 'updateOrder'])->name('menus.order');

        // Menu Items Management
        Route::resource('menus.items', \App\Http\Controllers\MenuItemController::class)->shallow();
        Route::post('menu-items/reorder', [\App\Http\Controllers\MenuItemController::class, 'reorder'])->name('menu-items.reorder');

        // Page Management
        Route::resource('pages', \App\Http\Controllers\PageController::class);
        Route::post('pages/{page}/seo', [\App\Http\Controllers\PageController::class, 'updateSeo'])->name('pages.seo');
    });

});



Route::get('/', [HomeController::class, 'index'])->name('home');
// Public route for showing a single product type

Route::get('/product_details', function () {
    return view('product_details');
});

// routes/web.php
Route::get('/product-details/{id}', [ProductTypeController::class, 'showProductType'])
    ->name('product_details');
Route::get('/category/{id}', [ProductTypeController::class, 'showParentCategory'])
     ->name('show_category');
Route::get('/product-type/{id}', [ProductTypeController::class, 'showProductType'])->name('show_product_type');

     Route::get('/product-page', function () {
    return view('product-page');
});
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product-page');

// CMS Frontend Routes
Route::get('/page/{slug}', [\App\Http\Controllers\PageController::class, 'show'])->name('page.show');
Route::get('/menu/{location}', [\App\Http\Controllers\MenuController::class, 'show'])->name('menu.show');