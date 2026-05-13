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
        Route::get('/metas/graficos/{id}', \App\Livewire\MetaGraficosController::class)->name('admin.metas.graficos');
        Route::get('/transcripciones', \App\Livewire\TranscripcionController::class)->name('admin.transcripciones.index');
        Route::get('/transcripciones/pdf', [\App\Livewire\ReporteTranscripcionController::class, 'descargar'])->name('admin.transcripciones.pdf');
        Route::get('/transcripciones/graficos', \App\Livewire\GraficosTranscripcionController::class)->name('admin.transcripciones.graficos');
        Route::get('/actividades/abordajes', \App\Livewire\Actividades\AbordajeController::class)->name('admin.actividades.abordajes.index');
        Route::get('/actividades/abordajes/pdf', [\App\Http\Controllers\ReporteActividadController::class, 'descargar'])->defaults('actividad', 'abordaje')->name('admin.actividades.abordajes.pdf');
        Route::get('/actividades/abordajes/graficos', \App\Livewire\Actividades\AbordajeController::class)->name('admin.actividades.abordajes.graficos');

        Route::get('/actividades/escuela4s', \App\Livewire\Actividades\Escuela4sController::class)->name('admin.actividades.escuela4s.index');
        Route::get('/actividades/escuela4s/pdf', [\App\Http\Controllers\ReporteActividadController::class, 'descargar'])->defaults('actividad', 'escuela4s')->name('admin.actividades.escuela4s.pdf');
        Route::get('/actividades/escuela4s/graficos', \App\Livewire\Actividades\Escuela4sController::class)->name('admin.actividades.escuela4s.graficos');

        Route::get('/actividades/liderazgo-territorial', \App\Livewire\Actividades\LiderazgoTerritorialController::class)->name('admin.actividades.liderazgo.index');
        Route::get('/actividades/liderazgo-territorial/pdf', [\App\Http\Controllers\ReporteActividadController::class, 'descargar'])->defaults('actividad', 'liderazgo_territorial')->name('admin.actividades.liderazgo.pdf');
        Route::get('/actividades/liderazgo-territorial/graficos', \App\Livewire\Actividades\LiderazgoTerritorialController::class)->name('admin.actividades.liderazgo.graficos');

        Route::get('/actividades/diversidad-dietaria', \App\Livewire\Actividades\DiversidadDietariaController::class)->name('admin.actividades.diversidad.index');
        Route::get('/actividades/diversidad-dietaria/pdf', [\App\Http\Controllers\ReporteActividadController::class, 'descargar'])->defaults('actividad', 'diversidad_dietaria')->name('admin.actividades.diversidad.pdf');
        Route::get('/actividades/diversidad-dietaria/graficos', \App\Livewire\Actividades\DiversidadDietariaController::class)->name('admin.actividades.diversidad.graficos');

        Route::get('/actividades/circulo-lactancia', \App\Livewire\Actividades\CirculoLactanciaController::class)->name('admin.actividades.circulo.index');
        Route::get('/actividades/circulo-lactancia/pdf', [\App\Http\Controllers\ReporteActividadController::class, 'descargar'])->defaults('actividad', 'circulo_lactancia')->name('admin.actividades.circulo.pdf');
        Route::get('/actividades/circulo-lactancia/graficos', \App\Livewire\Actividades\CirculoLactanciaController::class)->name('admin.actividades.circulo.graficos');

        Route::get('/actividades/plan-vulnerabilidad', \App\Livewire\Actividades\PlanVulnerabilidadController::class)->name('admin.actividades.vulnerabilidad.index');
        Route::get('/actividades/plan-vulnerabilidad/pdf', [\App\Http\Controllers\ReporteActividadController::class, 'descargar'])->defaults('actividad', 'plan_vulnerabilidad')->name('admin.actividades.vulnerabilidad.pdf');
        Route::get('/actividades/plan-vulnerabilidad/graficos', \App\Livewire\Actividades\PlanVulnerabilidadController::class)->name('admin.actividades.vulnerabilidad.graficos');

        Route::get('/actividades/feria-campo', \App\Livewire\Actividades\FeriaCampoController::class)->name('admin.actividades.feria.index');
        Route::get('/actividades/feria-campo/pdf', [\App\Http\Controllers\ReporteActividadController::class, 'descargar'])->defaults('actividad', 'feria_campo')->name('admin.actividades.feria.pdf');
        Route::get('/actividades/feria-campo/graficos', \App\Livewire\Actividades\FeriaCampoController::class)->name('admin.actividades.feria.graficos');

        Route::get('/calendario', \App\Livewire\CalendarioController::class)->name('admin.calendario.index');
        
        Route::get('/logout', function () {
            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();
            return redirect('/');
        })->name('logout');


});
