@extends('layouts.app')

@section('content')
<div x-data="{ 
        openModal: false, 
        openErrorModal: {{ session('error') ? 'true' : 'false' }},
        openSuccessModal: {{ session('success') ? 'true' : 'false' }}
    }" class="max-w-md mx-auto bg-white p-6 rounded shadow mt-10">

    <h1 class="text-2xl font-bold mb-6 text-center">Registrar Usuario</h1>

    <!-- Formulario -->
    <form x-ref="registroForm" method="POST" action="{{ route('registrar_usuario.store') }}">
        @csrf

        <div class="mb-4">
            <label class="block mb-1 font-medium">Nombre</label>
            <input type="text" name="nombre" class="w-full border rounded px-3 py-2" value="{{ old('nombre') }}" required>
            @error('nombre') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-medium">Email</label>
            <input type="email" name="email" class="w-full border rounded px-3 py-2" value="{{ old('email') }}" required>
            @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-medium">Fecha de nacimiento</label>
            <input type="date" name="fecha_nacimiento" class="w-full border rounded px-3 py-2" value="{{ old('fecha_nacimiento') }}" required>
            @error('fecha_nacimiento') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-medium">Contraseña</label>
            <input type="password" name="contrasena" class="w-full border rounded px-3 py-2" required>
            @error('contrasena') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-medium">Confirmar Contraseña</label>
            <input type="password" name="contrasena_confirmation" class="w-full border rounded px-3 py-2" required>
        </div>

        <!-- Botón que abre el modal de confirmación -->
        <button type="button" @click="openModal = true" class="w-full bg-green-500 text-white py-2 rounded hover:bg-green-600">
            Registrarse
        </button>
    </form>

    <!-- Modal de confirmación -->
    <div x-show="openModal" x-transition class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white max-w-md w-full rounded-xl overflow-hidden p-6">
            <h2 class="text-2xl font-bold mb-4 text-center">Confirmar registro</h2>
            <p class="text-gray-600 mb-6 text-center">¿Deseas continuar con el registro de este usuario?</p>

            <div class="flex space-x-3">
                <!-- Confirmar -->
                <button type="button" 
                        @click="$refs.registroForm.submit(); openModal = false" 
                        class="flex-1 p-3 bg-green-500 text-white rounded hover:bg-green-600">
                    Confirmar
                </button>

                <!-- Cancelar -->
                <button type="button" 
                        @click="openModal = false" 
                        class="flex-1 p-3 bg-gray-300 rounded hover:bg-gray-400">
                    Cancelar
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de error -->
    <div x-show="openErrorModal" x-transition class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white max-w-md w-full rounded-xl overflow-hidden p-6">
            <h2 class="text-2xl font-bold mb-4 text-center text-red-600">Error</h2>
            <p class="text-gray-700 mb-6 text-center">{{ session('error') }}</p>

            <div class="flex justify-center">
                <button type="button" @click="openErrorModal = false"
                    class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de éxito -->
    <div x-show="openSuccessModal" x-transition class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white max-w-md w-full rounded-xl overflow-hidden p-6">
            <h2 class="text-2xl font-bold mb-4 text-center text-green-600">¡Éxito!</h2>
            <p class="text-gray-700 mb-6 text-center">{{ session('success') }}</p>

            <div class="flex justify-center">
                <button type="button" @click="openSuccessModal = false"
                    class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

</div>
@endsection
