<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegistroUsuarioController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        //dd($request->all());

        $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:usuarios,email'],
            'fecha_nacimiento' => ['required','date'],
            'contrasena' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        try {
        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'fecha_registro' => now(),
            'contrasena' => Hash::make($request->contrasena),
            'id_rol' => 2,
        ]);

        Auth::login($usuario);

        return redirect()->route('registrar_usuario')->with('success', 'Usuario registrado correctamente.');

    } catch (\Exception $e) {
        // Redirige de vuelta al formulario con un flag de error
        return redirect()->back()->with('error', 'No se pudo crear el usuario. ' . $e->getMessage());
    }
    }
}
