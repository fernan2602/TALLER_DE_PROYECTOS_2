<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistroUsuarioController;
use App\Http\Controllers\LoginUsuarioController;
use App\Http\Controllers\PreferenciaController;
use App\Http\Controllers\MedidaController;
use App\Http\Controllers\ObjetivoController;
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

// Update de registro de medidas
Route::put('/medidas/{id}',[MedidaController::class, 'update'])->name('medidas.update');


Route::post('/registrar_usuario', [RegistroUsuarioController::class, 'store'])
    ->name('registrar_usuario.store');
    
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

Route::get('admin', function() {
    return view('ui_dashboard.admin'); // vista de admin --> autenticado
})->name('admin');

Route::get('nutriologo', function() {
    return view('ui_dashboard.nutriologo'); // vista de nutriologo --> autenticado
})->name('nutriologo');

Route::get('entrenador', function() {
    return view('ui_dashboard.entrenador'); // vista de entrenador --> autenticado
})->name('entrenador');

// Login
Route::get('/login', function () {
    return view('usuario.login');
})->name('login');

Route::post('/login', [LoginUsuarioController::class, 'validacion'])
    ->name('login.store');
?>