
<?php

use App\Http\Controllers\Panel\UserController;
use App\Http\Controllers\Panel\CategoryController;
use App\Http\Controllers\Inputs\SelectController;
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

         
        # module categories
        Route::resource('categories', CategoryController::class);
        # list categories
        Route::get('listar-categories',[CategoryController::class,'listarCategories'])->name('categories.listar');
     



          # Route group for inputs, selects and autocomplete
          Route::prefix('inputs')->name('inputs.')->group(function(){
            # get categories list
            Route::get('category_list',[SelectController::class,'getCategoryList'])->name('category_list');
            
        });
    });
});



require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
