<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistroUsuarioController;
use App\Http\Controllers\LoginUsuarioController;
use App\Http\Controllers\PreferenciaController;
use App\Http\Controllers\MedidaController;
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
Route::get('cuenta', [PreferenciaController::class, 'index'])->name('cuenta');

// Registros medidas
Route::post('/medidas', [MedidaController::class, 'store'])->name('medidas.store');


// 
Route::get('user', function() {
    return view('ui_dashboard.user'); // vista de usuario --> sin autenticar 
})->name('user');


// Login
Route::get('/login', function () {
    return view('usuario.login');
})->name('login');

Route::post('/login', [LoginUsuarioController::class, 'validacion'])
    ->name('login.store');


    ?>