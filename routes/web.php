<?php

use App\Livewire\LoginController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Redirigir la raíz al login
Route::redirect('/', '/login');

// Autenticación (Livewire)
Route::middleware('guest')->group(function () {
    Route::get('/login', LoginController::class)->name('login');
});

// Rutas protegidas (Usuario general)
Route::middleware(['auth', 'check.status'])->group(function () {
    
        Route::get('/dashboard', \App\Livewire\DashboardController::class)->name('dashboard');
        Route::get('/ajustes', \App\Livewire\AjusteController::class)->name('admin.ajustes.index');
        Route::get('/roles', \App\Livewire\RoleController::class)->name('admin.roles.index');
        Route::get('/usuarios', \App\Livewire\UsuarioController::class)->name('admin.usuarios.index');
        Route::get('/sectores', \App\Livewire\SectorController::class)->name('admin.sectores.index');
        Route::get('/comunas', \App\Livewire\ComunaController::class)->name('admin.comunas.index');
        Route::get('/metas', \App\Livewire\MetaController::class)->name('admin.metas.index');
        Route::get('/transcripciones', \App\Livewire\TranscripcionController::class)->name('admin.transcripciones.index');
        
        Route::get('/logout', function () {
            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();
            return redirect('/');
        })->name('logout');


});
