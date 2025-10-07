@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-[var(--color-surface)]">
    <div class="w-full max-w-md p-8 neumorphic">
        <h2 class="text-2xl font-bold text-center mb-6 text-[var(--color-primary)]">
            Iniciar Sesión
        </h2>

        <!-- Formulario -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium mb-1">Correo</label>
                <input type="email" id="email" name="email"
                       class="input-neu"
                       value="{{ old('email') }}" required autofocus>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium mb-1">Contraseña</label>
                <input type="password" id="password" name="contrasena"
                       class="input-neu"
                       required>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember me -->
            <div class="flex items-center mb-6">
                <input type="checkbox" id="remember" name="remember"
                       class="mr-2">
                <label for="remember" class="text-sm">Recuérdame</label>
            </div>

            <!-- Botón -->
            <div class="flex justify-center">
                <button type="submit" class="btn-neu w-full text-center">
                    Entrar
                </button>
            </div>

            <!-- Link de registro -->
            <div class="mt-4 text-center">
                <p class="text-sm">
                    ¿No tienes cuenta?
                    <a href="{{ route('registrar_usuario') }}" class="text-[var(--color-accent)] hover:underline">
                        Regístrate aquí
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection
