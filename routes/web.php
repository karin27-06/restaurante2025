
<?php

use App\Http\Controllers\Panel\UserController;
use App\Http\Controllers\AlmacenController;
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
    
    });
});



require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
