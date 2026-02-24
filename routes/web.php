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

/*
|--------------------------------------------------------------------------
| Rutas Públicas (Acceso sin sesión)
|--------------------------------------------------------------------------
*/

// Rutas para que el personal del taller pueda entrar al sistema
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Rutas Protegidas (Solo usuarios autenticados)
|--------------------------------------------------------------------------
*/
// El middleware 'auth' asegura que nadie extraño vea los datos de tus clientes o facturas
Route::middleware(['auth'])->group(function () {

  // Página de inicio tras el login: un panel analítico (dashboard)
  Route::get('/', function () {
    return view('content.dashboard.dashboards-analytics');
  })->name('dashboard-analytics');

  /*
  | MÓDULOS PRINCIPALES (CRUDs)
  | El uso de 'Route::resource' crea automáticamente 7 rutas para cada uno:
  | index (lista), create (formulario), store (guardar), show (ver), 
  | edit (editar formulario), update (actualizar) y destroy (borrar).
  */
  Route::resource('vehiculos', VehiculoController::class);
  Route::resource('encargos', EncargoController::class);
  Route::resource('materiales', MaterialController::class);
  Route::resource('citas', CitaController::class);
  Route::resource('usos_materiales', UsoMaterialController::class);
  Route::resource('fotos', FotoController::class);
  Route::resource('facturas', FacturaController::class);
  Route::resource('presupuestos', PresupuestoController::class);

  // Gestión de Clientes: Separado para tener un control más específico del Index
  Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
  Route::resource('clientes', ClienteController::class)->except(['index']);

  /*
  |--------------------------------------------------------------------------
  | RUTAS DE REFERENCIA DEL TEMPLATE (UI)
  | Útiles para que el desarrollador consulte cómo usar los componentes del tema
  |--------------------------------------------------------------------------
  */
  Route::get('/ui/buttons', [App\Http\Controllers\user_interface\Buttons::class, 'index'])->name('ui-buttons');
  Route::get('/forms/basic-inputs', [App\Http\Controllers\form_elements\BasicInput::class, 'index'])->name('forms-basic-inputs');
  Route::get('/tables/basic', [App\Http\Controllers\tables\Basic::class, 'index'])->name('tables-basic');
});
