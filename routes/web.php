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
    
    
        Route::get('/dashboard', \App\Livewire\DashboardController::class)->name('dashboard')->middleware('can:Ver Inicio');
        Route::get('/ajustes', \App\Livewire\AjusteController::class)->name('admin.ajustes.index')->middleware('can:Ajustes del Sistema');
        Route::get('/roles', \App\Livewire\RoleController::class)->name('admin.roles.index')->middleware('can:Ver Listado de Roles');
        Route::get('/usuarios', \App\Livewire\UsuarioController::class)->name('admin.usuarios.index')->middleware('can:Ver Listado de Usuarios');
        Route::get('/sectores', \App\Livewire\SectorController::class)->name('admin.sectores.index')->middleware('can:Ver Listado de Sectores');
        Route::get('/comunas', \App\Livewire\ComunaController::class)->name('admin.comunas.index')->middleware('can:Ver Listado de Comunas');
        Route::get('/metas', \App\Livewire\MetaController::class)->name('admin.metas.index')->middleware('can:Ver Listado de Metas');
        Route::get('/metas/graficos/{id}', \App\Livewire\MetaGraficosController::class)->name('admin.metas.graficos')->middleware('can:Ver Listado de Metas');
        
        Route::get('/transcripciones', \App\Livewire\TranscripcionController::class)->name('admin.transcripciones.index')->middleware('can:Ver Transcripciones');
        Route::get('/transcripciones/pdf', [\App\Livewire\ReporteTranscripcionController::class, 'descargar'])->name('admin.transcripciones.pdf')->middleware('can:Ver Transcripciones');
        Route::get('/transcripciones/graficos', \App\Livewire\GraficosTranscripcionController::class)->name('admin.transcripciones.graficos')->middleware('can:Ver Transcripciones');
        
        Route::get('/actividades/abordajes', \App\Livewire\Actividades\AbordajeController::class)->name('admin.actividades.abordajes.index')->middleware('can:Ver Abordajes');
        Route::get('/actividades/abordajes/pdf', [\App\Http\Controllers\ReporteActividadController::class, 'descargar'])->defaults('actividad', 'abordaje')->name('admin.actividades.abordajes.pdf')->middleware('can:Ver Abordajes');
        Route::get('/actividades/abordajes/graficos', \App\Livewire\Actividades\AbordajeController::class)->name('admin.actividades.abordajes.graficos')->middleware('can:Ver Abordajes');

        Route::get('/actividades/escuela4s', \App\Livewire\Actividades\Escuela4sController::class)->name('admin.actividades.escuela4s.index')->middleware('can:Ver Escuela 4S');
        Route::get('/actividades/escuela4s/pdf', [\App\Http\Controllers\ReporteActividadController::class, 'descargar'])->defaults('actividad', 'escuela4s')->name('admin.actividades.escuela4s.pdf')->middleware('can:Ver Escuela 4S');
        Route::get('/actividades/escuela4s/graficos', \App\Livewire\Actividades\Escuela4sController::class)->name('admin.actividades.escuela4s.graficos')->middleware('can:Ver Escuela 4S');

        Route::get('/actividades/liderazgo-territorial', \App\Livewire\Actividades\LiderazgoTerritorialController::class)->name('admin.actividades.liderazgo.index')->middleware('can:Ver Liderazgo Territorial');
        Route::get('/actividades/liderazgo-territorial/pdf', [\App\Http\Controllers\ReporteActividadController::class, 'descargar'])->defaults('actividad', 'liderazgo_territorial')->name('admin.actividades.liderazgo.pdf')->middleware('can:Ver Liderazgo Territorial');
        Route::get('/actividades/liderazgo-territorial/graficos', \App\Livewire\Actividades\LiderazgoTerritorialController::class)->name('admin.actividades.liderazgo.graficos')->middleware('can:Ver Liderazgo Territorial');

        Route::get('/actividades/diversidad-dietaria', \App\Livewire\Actividades\DiversidadDietariaController::class)->name('admin.actividades.diversidad.index')->middleware('can:Ver Diversidad Dietaria');
        Route::get('/actividades/diversidad-dietaria/pdf', [\App\Http\Controllers\ReporteActividadController::class, 'descargar'])->defaults('actividad', 'diversidad_dietaria')->name('admin.actividades.diversidad.pdf')->middleware('can:Ver Diversidad Dietaria');
        Route::get('/actividades/diversidad-dietaria/graficos', \App\Livewire\Actividades\DiversidadDietariaController::class)->name('admin.actividades.diversidad.graficos')->middleware('can:Ver Diversidad Dietaria');

        Route::get('/actividades/circulo-lactancia', \App\Livewire\Actividades\CirculoLactanciaController::class)->name('admin.actividades.circulo.index')->middleware('can:Ver Circulo de Lactancia');
        Route::get('/actividades/circulo-lactancia/pdf', [\App\Http\Controllers\ReporteActividadController::class, 'descargar'])->defaults('actividad', 'circulo_lactancia')->name('admin.actividades.circulo.pdf')->middleware('can:Ver Circulo de Lactancia');
        Route::get('/actividades/circulo-lactancia/graficos', \App\Livewire\Actividades\CirculoLactanciaController::class)->name('admin.actividades.circulo.graficos')->middleware('can:Ver Circulo de Lactancia');

        Route::get('/actividades/plan-vulnerabilidad', \App\Livewire\Actividades\PlanVulnerabilidadController::class)->name('admin.actividades.vulnerabilidad.index')->middleware('can:Ver Plan Vulnerabilidad');
        Route::get('/actividades/plan-vulnerabilidad/pdf', [\App\Http\Controllers\ReporteActividadController::class, 'descargar'])->defaults('actividad', 'plan_vulnerabilidad')->name('admin.actividades.vulnerabilidad.pdf')->middleware('can:Ver Plan Vulnerabilidad');
        Route::get('/actividades/plan-vulnerabilidad/graficos', \App\Livewire\Actividades\PlanVulnerabilidadController::class)->name('admin.actividades.vulnerabilidad.graficos')->middleware('can:Ver Plan Vulnerabilidad');

        Route::get('/actividades/feria-campo', \App\Livewire\Actividades\FeriaCampoController::class)->name('admin.actividades.feria.index')->middleware('can:Ver Feria del Campo');
        Route::get('/actividades/feria-campo/pdf', [\App\Http\Controllers\ReporteActividadController::class, 'descargar'])->defaults('actividad', 'feria_campo')->name('admin.actividades.feria.pdf')->middleware('can:Ver Feria del Campo');
        Route::get('/actividades/feria-campo/graficos', \App\Livewire\Actividades\FeriaCampoController::class)->name('admin.actividades.feria.graficos')->middleware('can:Ver Feria del Campo');

        Route::get('/calendario', \App\Livewire\CalendarioController::class)->name('admin.calendario.index')->middleware('can:Ver Calendario');
        
        Route::get('/logout', function () {
            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();
            return redirect('/');
        })->name('logout');


});
