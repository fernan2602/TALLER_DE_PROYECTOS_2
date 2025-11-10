@extends('layouts.app')


@section('content')
@php 
    $registro = auth()->check()  ?
    \App\Models\Medida::where('id_usuario', auth()->id())->exists() :
    false;
@endphp
    <div class="max-w-2xl mx-auto neumorphic p-8">
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-8">Panel de Control NutriGym</h1>
        <div class="p-5 space-y-4">        
            <!-- Botón 1: Ver mis datos -->
            <div class="flex justify-center">
                <button 
                    onclick="openModal('datosModal')" 
                    class="btn-neu w-full text-center flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Ver mis datos
                </button>
            </div>
            
            <!-- Botón 3: Reigstrar medidas -->
            <div class="flex justify-center">
                <!-- id_registro != null --> 
                <button onclick="openModal('registroModal')" class="btn-neu w-full text-center flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    {{ $registro ? 'Actualizar datos' : 'Registrar datos'}}
                </button>
            </div>
            <!-- Botón 4: Ver progreso -->
            <div class="flex justify-center">
                <button 
                    onclick="openModal('progresoModal')" 
                    class="btn-neu w-full text-center flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Ver progreso
                </button>
            </div>        
        
            <!-- MODAL 1: Ver mis datos -->
            <div id="datosModal" class="fixed inset-0 z-50 hidden">
                <div class="modal-backdrop fixed inset-0" onclick="closeModal('datosModal')"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="modal-content neumorphic w-full max-w-md">
                        <div class="p-6">
                            <!-- Header -->
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-bold text-gray-800 flex items-center">
                                    <svg class="w-6 h-6 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Mis Datos Personales
                                </h3>
                                <button onclick="closeModal('datosModal')" class="text-gray-500 hover:text-gray-700">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- Contenido -->
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <div class="block text-sm font-medium text-gray-700 mb-1">Nombre</div>
                                        <p class="input-neu bg-gray-50">{{ Auth::user()->nombre }}</p>
                                    </div>
                                </div>
                                
                                <div>
                                    <div>
                                        <div class="block text-sm font-medium text-gray-700 mb-1">Email</div>
                                        <p class="input-neu bg-gray-50">{{ Auth::user()->email }}</p>
                                    </div>
                                </div>
                                
                                <div>
                                    <div>
                                        <div class="block text-sm font-medium text-gray-700 mb-1">Fecha de nacimiento</div>
                                        <p class="input-neu bg-gray-50">{{ Auth::user()->fecha_nacimiento }}</p>
                                    </div>
                                </div>

                                <div>
                                    <div>
                                        <div class="block text-sm font-medium text-gray-700 mb-1">Fecha de registro</div>
                                        <p class="input-neu bg-gray-50">{{ Auth::user()->fecha_registro }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="flex gap-3 mt-6">
                                <button onclick="closeModal('datosModal')" class="btn-neu-secondary flex-1">
                                    Cerrar
                                </button>
                                <button class="btn-neu flex-1">
                                    Editar Datos
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <!-- MODAL 3 : Regisro de datos -->
            @if($registro)
                <div id="registroModal" class="fixed inset-0 z-50 hidden">
                    <div class="modal-backdrop fixed inset-0" onclick="closeModal('registroModal')"></div>
                        <div class="fixed inset-0 flex items-center justify-center p-4">
                            <div class="modal-content neumorphic w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                                <div class="p-6">
                                    <!-- Header -->
                                    <div class="flex items-center justify-between mb-6">
                                        <h3 class="text-xl font-bold text-gray-800 flex items-center">
                                            <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Actualizar Mis Datos
                                        </h3>
                                        <button onclick="closeModal('registroModal')" class="text-gray-500 hover:text-gray-700">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Formulario de ACTUALIZACIÓN -->
                                    <form id="registroForm" action="{{ route('medidas.update', auth()->user()->ultimaMedida()->id) }}" method="POST" class="space-y-4">
                                        @csrf
                                        @method('PUT') 
                                        
                                        <!-- Información Básica -->
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div>
                                                <label for="peso" class="block text-sm font-medium text-gray-700 mb-1">Peso (kg) *</label>
                                                <input type="number" id="peso" name="peso" step="0.01" class="input-neu" 
                                                    value="{{ auth()->user()->ultimaMedida()->peso }}" required>
                                            </div>
                                            <div>
                                                <label for="talla" class="block text-sm font-medium text-gray-700 mb-1">Talla (cm) *</label>
                                                <input type="number" id="talla" name="talla" step="0.01" class="input-neu" 
                                                    value="{{ auth()->user()->ultimaMedida()->talla }}" required>
                                            </div>
                                            <div>
                                                <label for="edad" class="block text-sm font-medium text-gray-700 mb-1">Edad *</label>
                                                <input type="number" id="edad" name="edad" class="input-neu" 
                                                    value="{{ auth()->user()->ultimaMedida()->edad }}" required>
                                            </div>
                                        </div>

                                        <!-- Circunferencias - Fila 1 -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label for="circunferencia_brazo" class="block text-sm font-medium text-gray-700 mb-1">Brazo (cm)</label>
                                                <input type="number" id="circunferencia_brazo" name="circunferencia_brazo" step="0.01" class="input-neu" 
                                                    value="{{ auth()->user()->ultimaMedida()->circunferencia_brazo }}" placeholder="32.5">
                                            </div>
                                            <div>
                                                <label for="circunferencia_antebrazo" class="block text-sm font-medium text-gray-700 mb-1">Antebrazo (cm)</label>
                                                <input type="number" id="circunferencia_antebrazo" name="circunferencia_antebrazo" step="0.01" class="input-neu" 
                                                    value="{{ auth()->user()->ultimaMedida()->circunferencia_antebrazo }}" placeholder="28.0">
                                            </div>
                                        </div>

                                        <!-- Circunferencias - Fila 2 -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label for="circunferencia_cintura" class="block text-sm font-medium text-gray-700 mb-1">Cintura (cm)</label>
                                                <input type="number" id="circunferencia_cintura" name="circunferencia_cintura" step="0.01" class="input-neu" 
                                                    value="{{ auth()->user()->ultimaMedida()->circunferencia_cintura }}" placeholder="85.0">
                                            </div>
                                            <div>
                                                <label for="circunferencia_caderas" class="block text-sm font-medium text-gray-700 mb-1">Caderas (cm)</label>
                                                <input type="number" id="circunferencia_caderas" name="circunferencia_caderas" step="0.01" class="input-neu" 
                                                    value="{{ auth()->user()->ultimaMedida()->circunferencia_caderas }}" placeholder="95.0">
                                            </div>
                                        </div>

                                        <!-- Circunferencias - Fila 3 -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label for="circunferencia_muslos" class="block text-sm font-medium text-gray-700 mb-1">Muslos (cm)</label>
                                                <input type="number" id="circunferencia_muslos" name="circunferencia_muslos" step="0.01" class="input-neu" 
                                                    value="{{ auth()->user()->ultimaMedida()->circunferencia_muslos }}" placeholder="55.0">
                                            </div>
                                            <div>
                                                <label for="circunferencia_pantorrilla" class="block text-sm font-medium text-gray-700 mb-1">Pantorrilla (cm)</label>
                                                <input type="number" id="circunferencia_pantorrilla" name="circunferencia_pantorrilla" step="0.01" class="input-neu" 
                                                    value="{{ auth()->user()->ultimaMedida()->circunferencia_pantorrilla }}" placeholder="38.0">
                                            </div>
                                        </div>

                                        <!-- Circunferencias - Última -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label for="circunferencia_cuello" class="block text-sm font-medium text-gray-700 mb-1">Cuello (cm)</label>
                                                <input type="number" id="circunferencia_cuello" name="circunferencia_cuello" step="0.01" class="input-neu" 
                                                    value="{{ auth()->user()->ultimaMedida()->circunferencia_cuello }}" placeholder="38.5">
                                            </div>
                                        </div>

                                        <!-- Información adicional -->
                                        <div class="bg-blue-50 p-3 rounded-lg">
                                            <p class="text-sm text-blue-700">
                                                💡 <strong>Consejo:</strong> Realize la actualizacion de datos encima de la informacion previa.
                                            </p>
                                            <br>
                                            <p class="text-sm text-blue-700">
                                                💡 <strong>Consejo:</strong> Realize la actualizacion en ayunas.
                                            </p>
                                        </div>

                                            <!-- Footer -->
                                        <div class="flex gap-3 mt-6">
                                            <button type="button" onclick="closeModal('registroModal')" class="btn-neu-secondary flex-1">
                                                    Cancelar
                                            </button>
                                            <button type="button" onclick="openConfirmacionUpdate()" class="btn-neu flex-1">
                                                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                    Guardar Registro
                                            </button>
                                        </div>
                                    </form>   
                                </div>
                            </div>
                        </div>
                </div>
            @else
                <div id="registroModal" class="fixed inset-0 z-50 hidden">
                    <div class="modal-backdrop fixed inset-0" onclick="closeModal('registroModal')"></div>
                        <div class="fixed inset-0 flex items-center justify-center p-4">
                            <div class="modal-content neumorphic w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                                <div class="p-6">
                                    <!-- Header -->
                                    <div class="flex items-center justify-between mb-6">
                                        <h3 class="text-xl font-bold text-gray-800 flex items-center">
                                            <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            Registrar Datos Corporales
                                        </h3>
                                        <button onclick="closeModal('registroModal')" class="text-gray-500 hover:text-gray-700">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Formulario -->
                                    <form id="registroForm" action="{{ route('medidas.store') }}" method="POST"  class="space-y-4">
                                        @csrf
                                        
                                        <!-- Información Básica -->
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Peso (kg)</label>
                                                <input type="number" name="peso" step="0.01" class="input-neu" placeholder="70.5" required>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Talla (cm)</label>
                                                <input type="number" name="talla" step="0.01" class="input-neu" placeholder="175.0" required>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Edad</label>
                                                <input type="number" name="edad" class="input-neu" placeholder="28" required>
                                            </div>
                                        </div>

                                        <!-- Género -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Género</label>
                                            <div class="flex space-x-4">
                                                <label class="flex items-center">
                                                    <input type="radio" name="genero" value="M" class="mr-2" required>
                                                    <span class="text-sm text-gray-700">Masculino</span>
                                                </label>
                                                <label class="flex items-center">
                                                    <input type="radio" name="genero" value="F" class="mr-2" required>
                                                    <span class="text-sm text-gray-700">Femenino</span>
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Circunferencias - Fila 1 -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Brazo (cm)</label>
                                                <input type="number" name="circunferencia_brazo" step="0.01" class="input-neu" placeholder="32.5">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Antebrazo (cm)</label>
                                                <input type="number" name="circunferencia_antebrazo" step="0.01" class="input-neu" placeholder="28.0">
                                            </div>
                                        </div>

                                        <!-- Circunferencias - Fila 2 -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Cintura (cm)</label>
                                                <input type="number" name="circunferencia_cintura" step="0.01" class="input-neu" placeholder="85.0">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Caderas (cm)</label>
                                                <input type="number" name="circunferencia_caderas" step="0.01" class="input-neu" placeholder="95.0">
                                            </div>
                                        </div>

                                        <!-- Circunferencias - Fila 3 -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Muslos (cm)</label>
                                                <input type="number" name="circunferencia_muslos" step="0.01" class="input-neu" placeholder="55.0">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Pantorrilla (cm)</label>
                                                <input type="number" name="circunferencia_pantorrilla" step="0.01" class="input-neu" placeholder="38.0">
                                            </div>
                                        </div>

                                        <!-- Circunferencias - Última -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Cuello (cm)</label>
                                                <input type="number" name="circunferencia_cuello" step="0.01" class="input-neu" placeholder="38.5">
                                            </div>
                                        </div>

                                        <!-- Información adicional -->
                                        <div class="bg-blue-50 p-3 rounded-lg">
                                            <p class="text-sm text-blue-700">
                                                💡 <strong>Consejo:</strong> Toma las medidas por la mañana en ayunas para mayor precisión.
                                            </p>
                                        </div>
                                        <!-- Footer -->
                                        <div class="flex gap-3 mt-6">
                                            <button type="button" onclick="closeModal('registroModal')" class="btn-neu-secondary flex-1">
                                                Cancelar
                                            </button>
                                            <button type="button" onclick="openConfirmacionModal()" class="btn-neu flex-1">
                                                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Guardar Registro
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                </div>
            @endif



        <!-- MODAL 4: Ver progreso -->
        <div id="progresoModal" class="fixed inset-0 z-50 hidden">
            <div class="modal-backdrop fixed inset-0" onclick="closeModal('progresoModal')"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="modal-content neumorphic w-full max-w-2xl">
                        <div class="p-6">
                            <!-- Header -->
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl font-bold text-gray-800 flex items-center">
                                    <svg class="w-6 h-6 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    Mi Progreso
                                </h3>
                                <button onclick="closeModal('progresoModal')" class="text-gray-500 hover:text-gray-700">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- Contenido -->
                            <div class="space-y-6">
                                <!-- Resumen de progreso -->
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div class="neumorphic-inset p-4 text-center rounded-lg">
                                        <div class="text-2xl font-bold text-green-600">-5kg</div>
                                        <div class="text-sm text-gray-600">Peso perdido</div>
                                    </div>
                                    <div class="neumorphic-inset p-4 text-center rounded-lg">
                                        <div class="text-2xl font-bold text-blue-600">12</div>
                                        <div class="text-sm text-gray-600">Sesiones/mes</div>
                                    </div>
                                    <div class="neumorphic-inset p-4 text-center rounded-lg">
                                        <div class="text-2xl font-bold text-orange-600">85%</div>
                                        <div class="text-sm text-gray-600">Asistencia</div>
                                    </div>
                                    <div class="neumorphic-inset p-4 text-center rounded-lg">
                                        <div class="text-2xl font-bold text-purple-600">+8%</div>
                                        <div class="text-sm text-gray-600">Fuerza</div>
                                    </div>
                                </div>

                                <!-- Gráfico simulado -->
                                <div class="neumorphic-inset p-4 rounded-lg">
                                    <h4 class="font-semibold text-gray-700 mb-3">Evolución de peso (últimos 30 días)</h4>
                                    <div class="h-32 flex items-end justify-between space-x-1">
                                        <div class="flex-1 bg-green-200 rounded-t" style="height: 60%"></div>
                                        <div class="flex-1 bg-green-300 rounded-t" style="height: 70%"></div>
                                        <div class="flex-1 bg-green-400 rounded-t" style="height: 65%"></div>
                                        <div class="flex-1 bg-green-500 rounded-t" style="height: 55%"></div>
                                        <div class="flex-1 bg-green-600 rounded-t" style="height: 50%"></div>
                                    </div>
                                </div>

                                <!-- Metas -->
                                <div>
                                    <h4 class="font-semibold text-gray-700 mb-3">Metas alcanzadas</h4>
                                    <div class="space-y-2">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                                            <span class="text-sm">Bajar 2kg este mes ✅</span>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                                            <span class="text-sm">Asistir 15 sesiones (12/15)</span>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 bg-red-500 rounded-full mr-3"></div>
                                            <span class="text-sm">Correr 5km sin parar</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="flex gap-3 mt-6">
                                <button onclick="closeModal('progresoModal')" class="btn-neu-secondary flex-1">
                                    Cerrar
                                </button>
                                <button class="btn-neu flex-1">
                                    Exportar Reporte
                                </button>
                            </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de confirmacion de registro --> 
        <div id="confirmacionModal" class="fixed inset-0 z-[60] hidden">
            <div class="modal-backdrop fixed inset-0" onclick="closeConfirmacionModal()"></div> <!-- Agregué esto -->
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="modal-content neumorphic w-full max-w-md">
                    @if($registro)
                        <div class="p-6">
                            <!-- Header -->
                            <div class="flex items-center justify-center mb-4">
                                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- Contenido -->
                            <div class="text-center mb-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">Actualizar Registro</h3>
                                <p class="text-sm text-gray-600">
                                    ¿Estás seguro de que quieres actualizar estos datos?<br>
                                    Esta información se registrará para tu progreso.
                                </p>
                            </div>

                            <!-- Footer CORREGIDO -->
                            <div class="flex gap-3">
                                <button type="button" onclick="closeConfirmacionModal()" class="btn-neu-secondary flex-1">
                                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Cancelar
                                </button>
                                <button type="button" onclick="confirmarUpdate()" class="btn-neu flex-1"> 
                                    <!-- CAMBIO: onclick="confirmarUpdate()" -->
                                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Sí, Actualizar
                                </button>
                            </div>
                        </div>
                    @else 
                        <div class="p-6">
                            <!-- Header -->
                                <div class="flex items-center justify-center mb-4">
                                    <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                </div>

                                <!-- Contenido -->
                                <div class="text-center mb-6">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Confirmar Registro</h3>
                                    <p class="text-sm text-gray-600">
                                        ¿Estás seguro de que quieres guardar estos datos?<br>
                                        Esta información se registrará en tu historial.
                                    </p>
                                </div>

                                <!-- Footer CORREGIDO -->
                                <div class="flex gap-3">
                                    <button type="button" onclick="closeConfirmacionModal()" class="btn-neu-secondary flex-1">
                                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Cancelar
                                    </button>
                                     <button type="button" onclick="confirmarStore()" class="btn-neu flex-1">
                                        <!-- CAMBIO: onclick="confirmarStore()" -->
                                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Sí, Registrar
                                    </button>
                                </div>
                        </div>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Funciones para controlar modales
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Cerrar modal con ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modals = document.querySelectorAll('[id$="Modal"]');
            modals.forEach(modal => {
                if (!modal.classList.contains('hidden')) {
                    closeModal(modal.id);
                }
            });
        }
    });

    // Función para abrir confirmación (para CREAR NUEVO registro)
    function openConfirmacionModal() {
        const form = document.getElementById('registroForm');
        
        if (!form) {
            console.error('Error: No se encontró el formulario con ID "registroForm"');
            alert('Error: No se puede encontrar el formulario');
            return;
        }
        
        if (form.checkValidity()) {
            closeModal('registroModal');
            document.getElementById('confirmacionModal').classList.remove('hidden');
        } else {
            form.reportValidity();
        }
    }

    // Función para abrir confirmación de ACTUALIZACIÓN
    function openConfirmacionUpdate() {
        const form = document.getElementById('registroForm');
        
        if (!form) {
            console.error('Error: No se encontró el formulario con ID "registroForm"');
            return;
        }
        
        if (form.checkValidity()) {
            closeModal('registroModal');
            document.getElementById('confirmacionModal').classList.remove('hidden');
        } else {
            form.reportValidity();
        }
    }

    // Función para cerrar confirmación
    function closeConfirmacionModal() {
        document.getElementById('confirmacionModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        
        // Reabrir el modal de registro/edición
        setTimeout(() => {
            openModal('registroModal');
        }, 300);
    }

    // Función única para enviar formularios (elimina el duplicado)
// FUNCIONES NUEVAS PARA MANEJAR CONFIRMACIÓN ESPECÍFICA
function confirmarUpdate() {
    console.log('=== CONFIRMANDO ACTUALIZACIÓN ===');
    const form = document.getElementById('registroForm');
    
    if (!form) {
        console.error('Error: No se encontró el formulario');
        return;
    }
    
    // Cerrar modal de confirmación
    closeConfirmacionModal();
    
    // Mostrar loading state
    const confirmBtn = document.querySelector('#confirmacionModal button[onclick*="confirmarUpdate"]');
    if (confirmBtn) {
        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Procesando...';
    }
    
    // DEBUG: Mostrar información del formulario
    console.log('Form action:', form.action);
    console.log('Form method:', form.method);
    
    // Enviar formulario
    form.submit();
}

    function confirmarStore() {
        console.log('=== CONFIRMANDO NUEVO REGISTRO ===');
        const form = document.getElementById('registroForm');
        
        if (!form) {
            console.error('Error: No se encontró el formulario');
            return;
        }
        
        // Cerrar modal de confirmación
        closeConfirmacionModal();
        
        // Mostrar loading state
        const confirmBtn = document.querySelector('#confirmacionModal button[onclick*="confirmarStore"]');
        if (confirmBtn) {
            confirmBtn.disabled = true;
            confirmBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Guardando...';
        }
        
        // DEBUG: Mostrar información del formulario
        console.log('Form action:', form.action);
        console.log('Form method:', form.method);
        
        // Enviar formulario
        form.submit();
    }

    // FUNCIÓN PARA DEBUG (puedes eliminar después de probar)
    function debugFormInfo() {
        const form = document.getElementById('registroForm');
        if (form) {
            console.log('=== DEBUG FORMULARIO ===');
            console.log('Action:', form.action);
            console.log('Method:', form.method);
            console.log('CSRF:', form.querySelector('input[name="_token"]')?.value);
            console.log('Method Spoofing:', form.querySelector('input[name="_method"]')?.value);
        }
    }

    // Llama a debug cuando se abra cualquier modal
    document.addEventListener('DOMContentLoaded', function() {
        const modalButtons = document.querySelectorAll('[onclick*="openModal"]');
        modalButtons.forEach(button => {
            const originalOnClick = button.getAttribute('onclick');
            button.setAttribute('onclick', originalOnClick + '; debugFormInfo();');
        });
    });

</script>

@endsection