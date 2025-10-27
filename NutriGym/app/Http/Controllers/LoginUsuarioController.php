<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules;

class LoginUsuarioController extends Controller
{
    // Manejo de login para los usuarios 
    public function validacion(Request $request) : RedirectResponse {
        
        // credenciales de ingreso 
        $credenciales = $request->validate([
            'email'=>['required','email'],
            'contrasena'=>['required'],
        ]);
        // Busqueda del email 
        $usuario  = Usuario::where('email',$credenciales['email'])->first();

        //Verificar contrasena y email 
        if ($usuario && Hash::check($credenciales['contrasena'], $usuario->contrasena))
        {
            Auth::login($usuario);
            // Redirigir y enviar mensaje de ingreso 
            if($usuario->id_rol == 1)
            {
                return redirect()->intended('admin')->with('succes','Bienvenido'.$usuario->nombre);

            }
            if($usuario->id_rol == 2)
            {
                return redirect()->intended('nutriologo')->with('succes','Bienvenido'.$usuario->nombre);
            }
            if($usuario->id_rol == 3)
            {
                return redirect()->intended('entrenador')->with('succes','Bienvenido'.$usuario->nombre);
            }
            if($usuario->id_rol == 4)
            {
                return redirect()->intended('usuario')->with('succes','Bienvenido'.$usuario->nombre);   
            }
        }
        // Si falla
        return back()->withErrors([
            'email' => "Las credenciales son errores",
        ])->onlyInput('email');
    }

    public function cerrar(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Sesión cerrada correctamente.');
    }

}
