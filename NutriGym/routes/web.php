<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistroUsuarioController;
use App\Http\Controllers\LoginUsuarioController;

// Registro
Route::get('/registrar_usuario', function () {
    return view('usuario.registrar_usuario');
})->name('registrar_usuario');

Route::post('/registrar_usuario', [RegistroUsuarioController::class, 'store'])
    ->name('registrar_usuario.store');
    
// Dashboard
Route::get('/dashboard', function() {
    return view('dashboard'); // o tu vista correspondiente
})->name('dashboard');


/*Login
Route::get('/login', function () {
    return view('usuario.login');
})->name('login');

Route::post('/login', [LoginUsuarioController::class, 'authenticate'])
    ->name('login.store');

*/
    ?>