
<?php

use App\Http\Controllers\Panel\UserController;
use App\Http\Controllers\Panel\CategoryController;
use App\Http\Controllers\AlmacenController;
use App\Http\Controllers\ClientTypeController;
use App\Http\Controllers\Inputs\AutoCompleteController;
use App\Http\Controllers\Inputs\SelectController;
use App\Http\Controllers\Panel\CustomerController;
use App\Http\Controllers\Panel\FloorController;
use App\Http\Controllers\Panel\ProductController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

# list prueba suppliers 

# route group for panel
Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('panel')->name('panel.')->group(function () {
        # module users
        Route::resource('users', UserController::class);
        # list users
        Route::get('listar-users',[UserController::class,'listarUsers'])->name('users.listar');
        # module almacens
        Route::resource('almacens', AlmacenController::class);
        # list almacens
        Route::get('listar-almacens', [AlmacenController::class, 'listarAlmacens'])->name('almacens.listar');
        Route::get('almacens-option', [AlmacenController::class, 'getAlmacensOption']);

        # module Client Types
        Route::resource('clientTypes', ClientTypeController::class);
        # list Client Types
        Route::get('listar-clientTypes', [ClientTypeController::class, 'listarClientTypes'])->name('clientTypes.listar');
        # module Customers
        Route::resource('customers', CustomerController::class);
        # list Customers
        Route::get('listar-customers', [CustomerController::class, 'listarCustomers'])->name('customers.listar');
        # module categories
        Route::resource('categories', CategoryController::class);
        # list categories
        Route::get('listar-categories',[CategoryController::class,'listarCategories'])->name('categories.listar');
        Route::get('categories-option', [CategoryController::class, 'getCategoriesOption']);
        # module floors
        Route::resource('floors', FloorController::class);
        # list floors
        Route::get('listar-floors',[FloorController::class,'listarFloors'])->name('floors.listar');
        # module products
        Route::resource('products', ProductController::class);
        # list products
        Route::get('listar-products',[ProductController::class,'listarProductos'])->name('products.listar');

        # Route group for inputs, selects and autocomplete
        Route::prefix('inputs')->name('inputs.')->group(function () {
            # get client_type list
            route::get('client_type_list', [SelectController::class, 'getClientTypeList'])->name('client_type_list');
            //automplete customers
            Route::get('customers_list', [AutoCompleteController::class, 'getCustomerList'])->name('customers_list');
            # get categories list
            Route::get('category_list',[SelectController::class,'getCategoryList'])->name('category_list');
            # get floors list
            Route::get('floor_list',[SelectController::class,'getFloorList'])->name('Floor_list');
             # get products list
            Route::get('product_list',[SelectController::class,'getProductList'])->name('product_list');
        });
    });
});



require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
