@extends('layouts.app')

@section('content')
<div x-data="{ 
        openModal: false, 
        openErrorModal: {{ session('error') ? 'true' : 'false' }},
        openSuccessModal: {{ session('success') ? 'true' : 'false' }}
    }" class="min-h-screen flex items-center justify-center bg-[var(--color-surface)] py-8">

    <div class="w-full max-w-md p-8 neumorphic">
        <h1 class="text-2xl font-bold text-center mb-6 text-[var(--color-primary)]">Registrar Usuario</h1>

        <!-- Formulario -->
        <form x-ref="registroForm" method="POST" action="{{ route('registrar_usuario.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nombre</label>
                <input type="text" name="nombre" class="input-neu" value="{{ old('nombre') }}" required>
                @error('nombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email" name="email" class="input-neu" value="{{ old('email') }}" required>
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Fecha de nacimiento</label>
                <input type="date" name="fecha_nacimiento" class="input-neu" value="{{ old('fecha_nacimiento') }}" required>
                @error('fecha_nacimiento') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Contraseña</label>
                <input type="password" name="contrasena" class="input-neu" required>
                @error('contrasena') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">Confirmar Contraseña</label>
                <input type="password" name="contrasena_confirmation" class="input-neu" required>
            </div>

            <!-- Botón que abre el modal de confirmación -->
            <button type="button" @click="openModal = true" class="btn-neu w-full text-center">
                Registrarse
            </button>
        </form>
    </div>

    <!-- Modal de confirmación -->
    <div x-show="openModal" x-transition class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="w-full max-w-md p-8 neumorphic mx-4">
            <h2 class="text-2xl font-bold text-center mb-4 text-[var(--color-primary)]">Confirmar registro</h2>
            <p class="text-gray-600 mb-6 text-center">¿Deseas continuar con el registro de este usuario?</p>

            <div class="flex space-x-3">
                <!-- Confirmar -->
                <button type="button" 
                        @click="$refs.registroForm.submit(); openModal = false" 
                        class="btn-neu flex-1">
                    Confirmar
                </button>

                <!-- Cancelar -->
                <button type="button" 
                        @click="openModal = false" 
                        class="btn-neu flex-1 bg-gray-300 text-gray-700 hover:bg-gray-400">
                    Cancelar
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de error -->
    <div x-show="openErrorModal" x-transition class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="w-full max-w-md p-8 neumorphic mx-4">
            <h2 class="text-2xl font-bold text-center mb-4 text-red-600">Error</h2>
            <p class="text-gray-700 mb-6 text-center">{{ session('error') }}</p>

            <div class="flex justify-center">
                <button type="button" @click="openErrorModal = false"
                    class="btn-neu bg-red-500 hover:bg-red-600">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de éxito -->
    <div x-show="openSuccessModal" x-transition class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="w-full max-w-md p-8 neumorphic mx-4">
            <h2 class="text-2xl font-bold text-center mb-4 text-[var(--color-primary)]">¡Éxito!</h2>
            <p class="text-gray-700 mb-6 text-center">{{ session('success') }}</p>

            <div class="flex justify-center">
                <button type="button" @click="openSuccessModal = false ; window.location.href='{{ route('dashboard') }}'"
                    class="btn-neu" >
                    Cerrar
                </button>
            </div>
        </div>
    </div>

</div>
@endsection