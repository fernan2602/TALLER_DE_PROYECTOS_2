@extends('layouts.app')

@section('content')

<div class="flex justify-center items-center min-h-screen py-8">
    <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="setup()">
        <!-- Contenedor Principal -->
        <div class="neumorphic rounded-2xl overflow-hidden min-h-[600px]">
            <!-- Content Area -->
            <div class="p-8 bg-white min-h-[500px]">
                <!-- Tab 0: Elegir Preferencia -->
                <div x-show="activeTab === 0" class="animate-fade-in space-y-8">
                    <!-- Header Section -->
                    <div class="text-center mb-8">
                        <h1 class="text-3xl font-bold text-gray-800 mb-2">🍽️ Elegir Dieta</h1>
                        <p class="text-gray-600 text-lg">Selecciona o crea una dieta personalizada</p>
                    </div>

                    <!-- Search and Action Bar -->
                    <div class="flex flex-col lg:flex-row gap-6 items-center justify-between mb-8">
                        <!-- Barra de Búsqueda Mejorada -->
                        <div class="flex-1 w-full lg:max-w-2xl">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <input 
                                    type="search" 
                                    class="input-neu pl-12 pr-4 py-4 text-base placeholder-gray-500 focus:border-green-500"
                                    placeholder="Buscar dietas por nombre, tipo, ingredientes..."
                                />
                            </div>
                        </div>
                        
                        <!-- Botón Nueva Dieta Mejorado -->
                        <button class="btn-neu w-full lg:w-auto flex items-center justify-center space-x-3 text-lg px-8 py-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <span class="text-lg font-semibold">Crear Nueva Dieta</span>
                        </button>
                    </div>

                    <!-- Cards Grid Mejorado -->
                    

                    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
                        <!-- Card Dieta 1 -->
                        @foreach($preferencias as $preferencia)
                            <!-- Card Objetivo -->
                                <div class="card-neu p-8 hover:transform hover:scale-105 transition-all duration-300">
                                    <div class="flex items-center justify-between mb-6">
                                        <div class="w-16 h-16 bg-{{ $preferencia->color ?? 'green' }}-500 rounded-2xl flex items-center justify-center neumorphic-inset">
                                            <span class="text-2xl">{{ $preferencia->icono ?? '🎯' }}</span>
                                        </div>
                                            <span class="bg-{{ $preferencia->color ?? 'green' }}-100 text-{{ $preferencia->color ?? 'green' }}-800 text-sm font-medium px-3 py-1 rounded-full">
                                                {{ $preferencia->tipo ?? 'General' }}
                                            </span>
                                    </div>
                                    <h3 class="text-2xl font-bold text-gray-800 mb-3">{{ $preferencia->tipo }}</h3>
                                    <p class="text-gray-600 mb-4 text-lg">{{ $preferencia->descripcion }}</p>
                                    


                                    <div class="flex justify-between items-center">
                                        <span class="text-{{ $preferencia->color ?? 'green' }}-600 font-semibold text-lg">                                    {{ $objetivo->meta ?? 'Objetivo' }}
                                        </span>
                                        <button class="btn-neu py-2 px-6 text-sm" onclick="seleccionarObjetivo({{ $preferencia->id }})">
                                            Seleccionar
                                        </button>
                                    </div>
                                </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Tab 1: Elegir objetivos -->
                <div x-show="activeTab === 1" class="animate-fade-in space-y-8">
                    <div class="text-center mb-8">
                            <h1 class="text-3xl font-bold text-gray-800 mb-2">🍽️ Elegir Dieta</h1>
                            <p class="text-gray-600 text-lg">Selecciona o crea una dieta personalizada</p>
                        </div>

                        <!-- Search and Action Bar -->
                        <div class="flex flex-col lg:flex-row gap-6 items-center justify-between mb-8">
                            <!-- Barra de Búsqueda Mejorada -->
                            <div class="flex-1 w-full lg:max-w-2xl">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                    <input 
                                        type="search" 
                                        class="input-neu pl-12 pr-4 py-4 text-base placeholder-gray-500 focus:border-green-500"
                                        placeholder="Buscar dietas por nombre, tipo, ingredientes..."
                                    />
                                </div>
                            </div>
                            
                            <!-- Botón Nueva Dieta Mejorado -->
                            <button class="btn-neu w-full lg:w-auto flex items-center justify-center space-x-3 text-lg px-8 py-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                <span class="text-lg font-semibold">Crear Nueva Dieta</span>
                            </button>
                        </div>

                        <!-- Cards Grid Mejorado -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
                            @foreach($objetivos as $objetivo)
                                <!-- Card Objetivo -->
                                <div class="card-neu p-8 hover:transform hover:scale-105 transition-all duration-300">
                                    <div class="flex items-center justify-between mb-6">
                                        <div class="w-16 h-16 bg-{{ $objetivo->color ?? 'green' }}-500 rounded-2xl flex items-center justify-center neumorphic-inset">
                                            <span class="text-2xl">{{ $objetivo->icono ?? '🎯' }}</span>
                                        </div>
                                            <span class="bg-{{ $objetivo->color ?? 'green' }}-100 text-{{ $objetivo->color ?? 'green' }}-800 text-sm font-medium px-3 py-1 rounded-full">
                                                {{ $objetivo->categoria ?? 'General' }}
                                            </span>
                                    </div>
                                    <h3 class="text-2xl font-bold text-gray-800 mb-3">{{ $objetivo->nombre }}</h3>
                                    <p class="text-gray-600 mb-4 text-lg">{{ $objetivo->descripcion }}</p>
                                


                                    <div class="flex justify-between items-center">
                                        <span class="text-{{ $objetivo->color ?? 'green' }}-600 font-semibold text-lg">                                    {{ $objetivo->meta ?? 'Objetivo' }}
                                        </span>
                                        <button class="btn-neu py-2 px-6 text-sm" onclick="seleccionarObjetivo({{ $objetivo->id }})">
                                            Seleccionar
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                </div>

                <!-- Tabs 2 y 3 con estructura similar -->
                <div x-show="activeTab === 2" class="animate-fade-in space-y-8">
                    <div class="text-center mb-8">
                        <h1 class="text-3xl font-bold text-gray-800 mb-2">⭐ Preferencias</h1>
                        <p class="text-gray-600 text-lg">Personaliza tus preferencias alimenticias</p>
                    </div>
                    <!-- Contenido de preferencias similar -->
                </div>

                <div x-show="activeTab === 3" class="animate-fade-in space-y-8">
                    <div class="text-center mb-8">
                        <h1 class="text-3xl font-bold text-gray-800 mb-2">📋 Asignar Dietas</h1>
                        <p class="text-gray-600 text-lg">Asigna dietas a usuarios específicos</p>
                    </div>
                    <!-- Contenido de asignación similar -->
                </div>
            </div>
            
            <!-- Navigation Buttons -->
            <div class="flex gap-4 justify-center border-t p-6 bg-gray-50">
                <button
                    class="py-3 px-8 border-2 rounded-xl border-blue-600 text-blue-600 cursor-pointer uppercase text-base font-bold hover:bg-blue-500 hover:text-white hover:shadow transition-all duration-200"
                    @click="activeTab--" x-show="activeTab>0"
                >
                    ← Anterior
                </button>
                <button
                    class="py-3 px-8 border-2 rounded-xl border-blue-600 text-blue-600 cursor-pointer uppercase text-base font-bold hover:bg-blue-500 hover:text-white hover:shadow transition-all duration-200"
                    @click="activeTab++" x-show="activeTab<tabs.length-1"
                >
                    Siguiente →
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function setup() {
        return {
            activeTab: 0,
            tabs: [
                "Elegir Dieta",
                "Elegir Objetivos", 
                "Preferencias",
                "Asignar Dietas",
            ]
        };
    };
</script>

@endsection