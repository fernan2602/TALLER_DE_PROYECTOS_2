<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
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

// Update de registro de medidas
Route::put('/medidas/{id}',[MedidaController::class, 'update'])->name('medidas.update');


    
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

// Mi cuenta
Route::get('cuenta', function()
{
    return view('usuario.cuenta');
})->name('cuenta');
//


// Preferencia
Route::get('preferencia', [ObjetivoController::class, 'select'])->name('preferencia');

// Ruta para guardar objetivo (formulario)
Route::post('/guardar-objetivo', [ObjetivoController::class, 'guardarObjetivo'])->name('guardar.objetivo');
// Ruta para guardar preferencia
Route::post('/guardar-preferencia',[PreferenciaController::class, 'guardarPreferencia'])->name('guardar.preferencia');

// Registros medidas
Route::post('/medidas', [MedidaController::class, 'store'])->name('medidas.store');


// 
Route::get('usuario', function() {
    return view('ui_dashboard.usuario'); // vista de usuario --> autenticado
})->name('usuario');


Route::get('nutriologo', function() {
    return view('ui_dashboard.nutriologo'); // vista de nutriologo --> autenticado
})->name('nutriologo');

// Para nutriologo
Route::get('nutriologo', [NutriologoController::class, 'index'])->name('ui_dashboard.nutriologo');

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
Route::get('/dashboard/buscar-preferencia/{usuarioId}', [MenuController::class, 'buscarPreferencia']);


// En routes/web.php

?>

