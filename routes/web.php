<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\EncargoController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\UsoMaterialController;
use App\Http\Controllers\FotoController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\PresupuestoController;
use App\Http\Controllers\AltaTrabajoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\VehiculoDataController;

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS (Sin autenticación)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $fotos = \App\Models\Foto::where('es_publica', true)
        ->where('tipo', '!=', 'despues')
        ->with('despues')
        ->latest()
        ->get();
    return view('content.pages.landing', compact('fotos'));
})->name('landing');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS (Solo personal autenticado)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

  // --- RUTAS CRÍTICAS DE EDICIÓN Y CREACIÓN (Solamente Administradores) ---
  Route::middleware(['admin'])->group(function () {
      Route::get('/nuevo-trabajo', [AltaTrabajoController::class, 'create'])->name('trabajo.create');
      Route::get('/api/clientes/buscar', [AltaTrabajoController::class, 'buscarCliente'])->name('api.clientes.buscar');
      Route::get('/api/materiales/buscar', [MaterialController::class, 'buscar'])->name('api.materiales.buscar');
      Route::get('/api/vehiculos/marcas', [VehiculoDataController::class, 'buscarMarcas'])->name('api.vehiculos.marcas');
      Route::get('/api/vehiculos/modelos', [VehiculoDataController::class, 'buscarModelos'])->name('api.vehiculos.modelos');
      Route::post('/nuevo-trabajo', [AltaTrabajoController::class, 'store'])->name('trabajo.store');
      
      // Cruds Críticos (Excepto Index/Show)
      Route::resource('materiales', MaterialController::class)->except(['index', 'show']);
      Route::resource('vehiculos', VehiculoController::class)->except(['index', 'show']);
      Route::resource('encargos', EncargoController::class)->except(['index', 'show']);
      Route::resource('citas', CitaController::class)->except(['index', 'show']);
      Route::resource('usos_materiales', UsoMaterialController::class)->except(['index', 'show']);
      Route::resource('fotos', FotoController::class)->except(['index', 'show']);
      Route::resource('facturas', FacturaController::class)->except(['index', 'show']);
      Route::post('/presupuestos/{id}/quick-update', [PresupuestoController::class, 'quickUpdate'])->name('presupuestos.quickUpdate');
      Route::resource('presupuestos', PresupuestoController::class)->except(['index', 'show']);
      Route::resource('clientes', ClienteController::class)->except(['index', 'show']);

      // GESTIÓN DE USUARIOS
      Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
      Route::put('/usuarios/{usuario}', [UserController::class, 'update'])->name('usuarios.update');
      Route::delete('/usuarios/{usuario}', [UserController::class, 'destroy'])->name('usuarios.destroy');

      // CMS GALERÍA PÚBLICA
      Route::resource('galeria', \App\Http\Controllers\GaleriaController::class)->only(['index', 'store', 'destroy']);
  });

  // --- RUTAS DE VISUALIZACIÓN Y TRABAJO (Admins y Operarios) ---

  Route::get('/dashboard', [Analytics::class, 'index'])->name('dashboard-analytics');
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

  // Módulos Read-Only
  Route::get('/materiales/categoria/{tipo}', [MaterialController::class, 'index'])->name('materiales.categoria');
  Route::get('/materiales', [MaterialController::class, 'index'])->name('materiales.index');
  Route::resource('materiales', MaterialController::class)->only(['show']);

  Route::resource('vehiculos', VehiculoController::class)->only(['index', 'show']);
  Route::get('/encargos/rechazados', [EncargoController::class, 'rechazados'])->name('encargos.rechazados');
  Route::resource('encargos', EncargoController::class)->only(['index', 'show']);
  
  Route::get('/calendario', [CitaController::class, 'showCalendar'])->name('citas.calendario');
  Route::get('/api/eventos', [CitaController::class, 'getEvents'])->name('api.eventos');
  Route::get('/api/disponibilidad', [CitaController::class, 'checkAvailability'])->name('api.disponibilidad');
  Route::resource('citas', CitaController::class)->only(['index', 'show']);
  
  Route::resource('usos_materiales', UsoMaterialController::class)->only(['index', 'show']);
  Route::resource('fotos', FotoController::class)->only(['index', 'show']);
  Route::get('/facturas/{id}/imprimir', [FacturaController::class, 'imprimir'])->name('facturas.imprimir');
  Route::resource('facturas', FacturaController::class)->only(['index', 'show']);
  Route::resource('presupuestos', PresupuestoController::class)->only(['index', 'show']);
  
  Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
  Route::resource('clientes', ClienteController::class)->only(['show']);

  // Kanbans y Flujos de Taller (Operarios deben poder mover tarjetas)

  Route::get('/taller/recepcion', [EncargoController::class, 'kanbanRecepcion'])->name('encargos.recepcion');
  Route::get('/taller/produccion', [EncargoController::class, 'kanbanProduccion'])->name('encargos.produccion');
  Route::post('/encargos/{id}/status', [EncargoController::class, 'cambiarEstado'])->name('encargos.updateStatus');
  Route::post('/encargos/{id}/status/ajax', [EncargoController::class, 'cambiarEstadoAjax'])->name('encargos.updateStatusAjax');
  Route::post('/encargos/{id}/aceptar-programar', [EncargoController::class, 'aceptarYProgramar'])->name('encargos.aceptar-programar');

});
