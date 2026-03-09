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
use App\Http\Controllers\dashboard\Analytics; // <--- Añadimos \dashboard\
/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS (Sin autenticación)
|--------------------------------------------------------------------------
*/

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

  // --- DASHBOARD ---
  Route::get('/', [Analytics::class, 'index'])->name('dashboard-analytics');

  // --- GESTIÓN DE PERFIL ---
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

  // --- MÓDULO ALTA RÁPIDA  ---
  Route::get('/nuevo-trabajo', [AltaTrabajoController::class, 'create'])->name('trabajo.create');
  Route::post('/nuevo-trabajo', [AltaTrabajoController::class, 'store'])->name('trabajo.store');


  // --- MÓDULOS PRINCIPALES (CRUDs) ---
  Route::resource('vehiculos', VehiculoController::class);
  Route::resource('encargos', EncargoController::class);
  Route::resource('materiales', MaterialController::class);
  Route::resource('citas', CitaController::class);
  Route::resource('usos_materiales', UsoMaterialController::class);
  Route::resource('fotos', FotoController::class);
  Route::resource('facturas', FacturaController::class);
  Route::resource('presupuestos', PresupuestoController::class);

  // Clientes
  Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
  Route::resource('clientes', ClienteController::class)->except(['index']);

  // --- COMPONENTES UI ---
  Route::prefix('ui')->group(function () {
    Route::get('/buttons', [App\Http\Controllers\user_interface\Buttons::class, 'index'])->name('ui-buttons');
  });

  Route::prefix('forms')->group(function () {
    Route::get('/basic-inputs', [App\Http\Controllers\form_elements\BasicInput::class, 'index'])->name('forms-basic-inputs');
  });

  Route::prefix('tables')->group(function () {
    Route::get('/basic', [App\Http\Controllers\tables\Basic::class, 'index'])->name('tables-basic');
  });
});
