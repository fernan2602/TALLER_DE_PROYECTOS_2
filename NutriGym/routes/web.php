<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\RegistroUsuarioController;
use App\Http\Controllers\LoginUsuarioController;
use App\Http\Controllers\PreferenciaController;
use App\Http\Controllers\MedidaController;
use App\Http\Controllers\ObjetivoController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AlimentosController;
use App\Http\Controllers\BackUpController;
use App\Http\Controllers\NutriologoController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProgresoController;
use App\Http\Controllers\EntrenadorController;
use App\Models\Usuario;
use Illuminate\Auth\Events\Logout;

// Redirigir la raíz al login
Route::get('/', function () {
    return redirect('/login');
});

// Registro
Route::get('/registrar_usuario', function () {
    return view('usuario.registrar_usuario');
})->name('registrar_usuario');


Route::post('/registrar_usuario', [RegistroUsuarioController::class, 'store'])
    ->name('registrar_usuario.store');


Route::get('/cuenta', [MedidaController::class, 'index'])->name('cuenta');

// Store
Route::post('/medidas', [MedidaController::class, 'store'])->name('medidas.store');

// Update
Route::put('/medidas/{medida}', [MedidaController::class, 'update'])->name('medidas.update');


    
// Dashboard

Route::get('/dashboard', function() {
    return view('dashboard'); // 
})->name('dashboard');


// Logout
Route::get('logout',function(){
    return view('usuario.logout');
})->name('logout.confirm');

// Ruta para procesar el logout
Route::post('logout', function() {
    //Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    
    return redirect('/')->with('success', 'Sesión cerrada correctamente');
})->name('logout');

// control de usuarios
Route::get('control', function() {
    return view('admin.control'); // 
})->name('control');


//


// Preferencia
Route::get('preferencia', [ObjetivoController::class, 'select'])->name('preferencia');

// Ruta para guardar objetivo (formulario)
Route::post('/guardar-objetivo', [ObjetivoController::class, 'guardarObjetivo'])->name('guardar.objetivo');
// Ruta para guardar preferencia
Route::post('/guardar-preferencia',[PreferenciaController::class, 'guardarPreferencia'])->name('guardar.preferencia');




// 
Route::get('usuario', function() {
    return view('ui_dashboard.usuario'); // vista de usuario --> autenticado
})->name('usuario');

// Ruta selección  preferencias y objetivos
Route::get('/preferencias', [PreferenciaController::class, 'index'])->name('preferencias.index');
Route::post('/preferencias/guardar', [PreferenciaController::class, 'guardarpreferencias'])->name('preferencias.guardar');
Route::get('/preferencias/seleccionados', [PreferenciaController::class, 'preferenciasSeleccionados'])->name('preferencias.seleccionados');
Route::delete('/preferencias/{id}/eliminar', [PreferenciaController::class, 'eliminarPreferencia'])->name('preferencias.eliminar');

Route::get('/objetivos', [ObjetivoController::class, 'index'])->name('objetivos.index');
Route::post('/objetivos/guardar', [ObjetivoController::class, 'guardarObjetivos'])->name('objetivos.guardar');
Route::get('/objetivos/seleccionados', [ObjetivoController::class, 'objetivosSeleccionados'])->name('objetivos.seleccionados');
Route::delete('/objetivos/{id}/eliminar', [ObjetivoController::class, 'eliminarObjetivo'])->name('objetivos.eliminar');

// Ruta seleccion y generacion de menu 
Route::get('/dashboard/generar-dieta', [MenuController::class, 'generarDieta']);
Route::post('/menus', [MenuController::class, 'store']);
Route::get('/menus/mis-menus', [MenuController::class, 'getMyMenus'])->middleware('auth');
Route::get('/mis-dietas', [MenuController::class, 'getMyMenus'])->name('dietas.mis-dietas');








Route::get('nutriologo', function() {
    return view('ui_dashboard.nutriologo'); // vista de nutriologo --> autenticado
})->name('nutriologo');

// Para nutriologo
Route::get('nutriologo', [NutriologoController::class, 'index'])->name('ui_dashboard.nutriologo');
Route::get('/usuario/{id}/dietas', [MenuController::class, 'getByUsuario'])->name('usuario.dietas');
Route::post('/menu/{id}/toggle-validacion', [MenuController::class, 'toggleValidacion']);


// En routes/web.php - parámetro realmente opcional
Route::get('/progreso/datos/{pacienteId?}', [ProgresoController::class, 'obtenerProgresoUsuario'])
    ->name('progreso.datos')
    ->middleware('auth');



// Ruta para obtener alimentos 
Route::get('alimentos', [AlimentosController::class, 'index'])->name('nutriologo.alimentos');

// Update alimentos 
Route::put('alimentos/{id}', [AlimentosController::class, 'update'])->name('nutriologo.alimentos.update');

// Ruta para guardar nuevo alimento
Route::post('alimentos', [AlimentosController::class, 'store'])->name('nutriologo.alimentos.store');
// Ruta para eliminar alimentos
Route::delete('alimentos/{id}', [AlimentosController::class, 'destroy'])->name('nutriologo.alimentos.destroy');


Route::get('entrenador', function() {
    return view('ui_dashboard.entrenador'); // vista de entrenador --> autenticado
})->name('entrenador');

// Vista entrenador clientes
Route::get('entrenador', [EntrenadorController::class, 'index'])->name('ui_dashboard.entrenador');

Route::get('/entrenador/medidas/{usuarioId}', [EntrenadorController::class, 'obtenerMedidasUsuario'])
    ->name('entrenador.medidas.usuario');

// Para obtener la última medida de un usuario
Route::get('/entrenador/ultima-medida/{usuarioId}', [EntrenadorController::class, 'obtenerUltimaMedida'])
    ->name('entrenador.ultima.medida');

// En routes/web.php - parámetro realmente opcional
Route::get('/progreso/datos/{pacienteId?}', [ProgresoController::class, 'obtenerProgresoUsuario'])
    ->name('progreso.datos')
    ->middleware('auth');

// Login
Route::get('/login', function () {
    return view('usuario.login');
})->name('login');

Route::post('/login', [LoginUsuarioController::class, 'validacion'])
    ->name('login.store');


    
Route::get('admin', function() {
    return view('ui_dashboard.admin'); // vista de admin --> autenticado
})->name('admin');

// Para admin
Route::get('admin', [AdminController::class, 'index'])->name('admin');


// Ruta objetivos validar   
Route::get('/usuarios/{id}/objetivos', [NutriologoController::class, 'obtenerObjetivosUsuario'])->name('usuarios.objetivos');
// Ruta preferencias validar
Route::get('/usuarios/{id}/preferencias', [NutriologoController::class, 'obtenerPreferenciasUsuario'])->name('usuarios.preferencias');
// GET
Route::get('/menu/calcular-get/{usuarioId}', [MenuController::class, 'calcularGET']);
// Ruta : BackUp
Route::post('/backup/create', [BackupController::class, 'createBackup'])->name('backup.create');



// Prueba gemini    
Route::get('/dashboard/prueba-gemini', [MenuController::class, 'pruebaGemini']);
Route::get('/dashboard/debug-gemini', [MenuController::class, 'debugGeminiConfig']); 
Route::get('/dashboard/prueba-gemini-usuario/{usuarioId}', [MenuController::class, 'pruebaGeminiUsuario']);
Route::get('/dashboard/generar-dieta/{usuarioId}', [MenuController::class, 'generarDieta']);


// En routes/web.php

?>

