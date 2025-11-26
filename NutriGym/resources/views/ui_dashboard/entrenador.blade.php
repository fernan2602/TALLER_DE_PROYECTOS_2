@extends('layouts.app')

@section('content')
<div class="bg-gray-100 min-h-screen">
    <div class="header py-4 px-4 md:px-6 lg:px-10">
        <h1 class="font-medium text-xl md:text-2xl">Clientes</h1>
    </div>

    <div class="flex flex-col xl:flex-row gap-4 px-4 md:px-6 lg:px-10 pb-6">
        <!-- Tabla de usuarios -->
        <div class="w-full xl:w-1/3">
            <div class="neumorphic p-4 md:p-6">
                <div class="w-full px-3 mb-4 md:mb-6">
                    <label class="block uppercase tracking-wide text-gray-700 text-sm font-bold mb-2">
                        Buscar cliente
                    </label>
                    <input 
                        class="input-neu w-full" 
                        type="text" 
                        name="name" 
                        placeholder="Nombre del cliente" 
                        required 
                    />
                </div>                       
                <div class="w-full px-3 mb-4 md:mb-6">
                    <button class="btn-neu w-full">
                        Buscar cliente
                    </button>
                </div>
                <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-4">Lista de Usuarios</h3>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[300px]">
                        <thead>
                            <tr class="border-b-2 border-gray-200">
                                <th class="text-left p-3 md:p-4 font-semibold text-gray-700 text-sm md:text-base">ID</th>
                                <th class="text-left p-3 md:p-4 font-semibold text-gray-700 text-sm md:text-base">Nombre</th>
                                <th class="text-left p-3 md:p-4 font-semibold text-gray-700 text-sm md:text-base">Rol</th>
                            </tr>
                        </thead>
                        <tbody id="tablaUsuarios">
                            @foreach($usuarios as $usuario)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition duration-200 cursor-pointer"
                                onclick="mostrarUsuario(event)"
                                data-usuario='@json($usuario)'>
                                <td class="p-3 md:p-4 text-gray-600 text-sm md:text-base">{{ $usuario->id }}</td>
                                <td class="p-3 md:p-4 text-gray-800 font-medium text-sm md:text-base">{{ $usuario->nombre }}</td>
                                <td class="p-3 md:p-4">
                                    <span class="px-2 py-1 rounded-full text-xs md:text-sm font-medium bg-gray-100 text-gray-800">
                                        {{ $usuario->nombre_rol ?? 'Rol ' . $usuario->id_rol }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Panel de detalles -->
        <div class="w-full xl:w-2/3">
            <div class="neumorphic p-4 md:p-6">
                <div id="detallesUsuario">
                    <div id="mensajeVacio" class="text-center py-8 text-gray-500 text-sm md:text-base">
                        Selecciona un usuario para ver sus detalles
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de progreso -->
<div id="progresoModal" class="fixed inset-0 z-50 hidden">
    <div class="modal-backdrop fixed inset-0" onclick="closeModal('progresoModal')"></div>
    <div class="fixed inset-0 flex items-center justify-center p-2 md:p-4">
        <div class="modal-content neumorphic w-full max-w-2xl mx-2 md:mx-0 max-h-[90vh] md:max-h-[95vh] overflow-y-auto">
            <div class="p-4 md:p-6">
                <!-- Header -->
                <div class="flex items-center justify-between mb-4 md:mb-6">
                    <h3 class="text-lg md:text-xl font-bold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 md:w-6 md:h-6 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Mi Progreso
                    </h3>
                    <button onclick="closeModal('progresoModal')" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Contenido dinámico -->
                <div id="progresoContent" class="space-y-4 md:space-y-6">
                    <!-- Loading state -->
                    <div id="progresoLoading" class="text-center py-6 md:py-8">
                        <div class="animate-spin rounded-full h-10 w-10 md:h-12 md:w-12 border-b-2 border-purple-500 mx-auto"></div>
                        <p class="text-gray-600 mt-2 text-sm md:text-base">Cargando tu progreso...</p>
                    </div>

                    <!-- Contenido real (oculto inicialmente) -->
                    <div id="progresoData" class="hidden space-y-4 md:space-y-6">
                        <!-- Resumen de progreso -->
                        <div class="grid grid-cols-2 gap-2 md:gap-4 md:grid-cols-4">
                            <div class="neumorphic-inset p-3 md:p-4 text-center rounded-lg">
                                <div id="pesoPerdido" class="text-lg md:text-2xl font-bold text-green-600">-0kg</div>
                                <div class="text-xs md:text-sm text-gray-600">Peso perdido</div>
                            </div>
                            <div class="neumorphic-inset p-3 md:p-4 text-center rounded-lg">
                                <div id="sesionesMes" class="text-lg md:text-2xl font-bold text-blue-600">0</div>
                                <div class="text-xs md:text-sm text-gray-600">Sesiones/mes</div>
                            </div>
                            <div class="neumorphic-inset p-3 md:p-4 text-center rounded-lg">
                                <div id="asistencia" class="text-lg md:text-2xl font-bold text-orange-600">0%</div>
                                <div class="text-xs md:text-sm text-gray-600">Asistencia</div>
                            </div>
                            <div class="neumorphic-inset p-3 md:p-4 text-center rounded-lg">
                                <div id="incrementoFuerza" class="text-lg md:text-2xl font-bold text-purple-600">+0%</div>
                                <div class="text-xs md:text-sm text-gray-600">Fuerza</div>
                            </div>
                        </div>

                        <!-- Gráfico de evolución de peso -->
                        <div class="neumorphic-inset p-3 md:p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-700 mb-2 md:mb-3 text-sm md:text-base">Evolución de peso (últimas 5 mediciones)</h4>
                            <div id="pesoChart" class="h-24 md:h-32 flex items-end justify-between space-x-1">
                                <!-- El gráfico se generará dinámicamente -->
                            </div>
                        </div>

                        <!-- Metas -->
                        <div>
                            <h4 class="font-semibold text-gray-700 mb-2 md:mb-3 text-sm md:text-base">Mis Metas</h4>
                            <div id="metasList" class="space-y-2">
                                <!-- Las metas se cargarán dinámicamente -->
                            </div>
                        </div>

                        <!-- Estado actual vs inicial -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
                            <div class="neumorphic-inset p-3 md:p-4 rounded-lg">
                                <h4 class="font-semibold text-gray-700 mb-2 text-sm md:text-base">Estado Inicial</h4>
                                <div id="estadoInicial" class="text-xs md:text-sm text-gray-600">
                                    <!-- Datos del estado inicial -->
                                </div>
                            </div>
                            <div class="neumorphic-inset p-3 md:p-4 rounded-lg">
                                <h4 class="font-semibold text-gray-700 mb-2 text-sm md:text-base">Estado Actual</h4>
                                <div id="estadoActual" class="text-xs md:text-sm text-gray-600">
                                    <!-- Datos del estado actual -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Error state -->
                    <div id="progresoError" class="hidden text-center py-6 md:py-8">
                        <div class="text-red-500 text-base md:text-lg mb-2">❌ Error al cargar el progreso</div>
                        <p id="errorMessage" class="text-gray-600 text-sm md:text-base"></p>
                        <button onclick="cargarProgreso()" class="btn-neu bg-blue-500 hover:bg-blue-600 text-white mt-4 text-sm md:text-base">
                            Reintentar
                        </button>
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex flex-col sm:flex-row gap-2 md:gap-3 mt-4 md:mt-6">
                    <button onclick="closeModal('progresoModal')" class="btn-neu bg-gray-500 hover:bg-gray-600 text-white flex-1 text-sm md:text-base">
                        Cerrar
                    </button>
                    <button class="btn-neu bg-blue-500 hover:bg-blue-600 text-white flex-1 text-sm md:text-base" onclick="exportarReporte()">
                        Exportar Reporte
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de objetivos -->
<div id="objetivoModal" class="fixed inset-0 z-50 hidden">
    <div class="modal-backdrop fixed inset-0" onclick="closeModal('objetivoModal')"></div>
    <div class="fixed inset-0 flex items-center justify-center p-2 md:p-4">
        <div class="modal-content neumorphic w-full max-w-2xl mx-2 md:mx-0 max-h-[90vh] md:max-h-[95vh] overflow-y-auto">
            <div class="p-4 md:p-6">
                <!-- Header -->
                <div class="flex items-center justify-between mb-4 md:mb-6">
                    <h3 class="text-lg md:text-xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-bullseye mr-2 text-blue-500 text-lg md:text-xl"></i>
                        Objetivos del Usuario
                    </h3>
                    <button onclick="closeModal('objetivoModal')" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-lg md:text-xl"></i>
                    </button>
                </div>

                <!-- Contenido dinámico -->
                <div id="objetivosContent">
                    <div class="text-center py-6 md:py-8 text-gray-500">
                        <i class="fas fa-spinner fa-spin text-2xl md:text-3xl mb-3 md:mb-4"></i>
                        <p class="text-sm md:text-base">Cargando objetivos...</p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex justify-end mt-4 md:mt-6">
                    <button onclick="closeModal('objetivoModal')" class="btn-neu bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 md:px-6 md:py-2 text-sm md:text-base">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver medidas -->
<div id="openMedidasModal" class="fixed inset-0 z-50 hidden">
    <div class="modal-backdrop fixed inset-0" onclick="closeModal('openMedidasModal')"></div>
    <div class="fixed inset-0 flex items-center justify-center p-2 md:p-4">
        <div class="modal-content neumorphic w-full max-w-4xl mx-2 md:mx-0 max-h-[90vh] md:max-h-[95vh] overflow-y-auto">
            <div class="p-4 md:p-6">
                <!-- Header -->
                <div class="flex items-center justify-between mb-4 md:mb-6">
                    <h3 class="text-lg md:text-xl font-bold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 md:w-6 md:h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Historial de Medidas Corporales
                    </h3>
                    <button onclick="closeModal('openMedidasModal')" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Contenido dinámico -->
                <div id="medidasContent" class="space-y-4 md:space-y-6">
                    <!-- Loading state -->
                    <div id="medidasLoading" class="text-center py-6 md:py-8">
                        <div class="animate-spin rounded-full h-10 w-10 md:h-12 md:w-12 border-b-2 border-blue-500 mx-auto"></div>
                        <p class="text-gray-600 mt-2 text-sm md:text-base">Cargando medidas...</p>
                    </div>

                    <!-- Contenido real (oculto inicialmente) -->
                    <div id="medidasData" class="hidden space-y-3 md:space-y-4">
                        <!-- Las medidas se cargarán aquí dinámicamente -->
                    </div>

                    <!-- Error state -->
                    <div id="medidasError" class="hidden text-center py-6 md:py-8">
                        <div class="text-red-500 text-base md:text-lg mb-2">❌ Error al cargar las medidas</div>
                        <p id="medidasErrorMessage" class="text-gray-600 text-sm md:text-base"></p>
                        <button onclick="cargarMedidasUsuario(lastUsuarioId)" class="btn-neu bg-blue-500 hover:bg-blue-600 text-white mt-4 text-sm md:text-base">
                            Reintentar
                        </button>
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex gap-2 md:gap-3 mt-4 md:mt-6">
                    <button onclick="closeModal('openMedidasModal')" class="btn-neu bg-gray-500 hover:bg-gray-600 text-white flex-1 text-sm md:text-base">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Variable global para almacenar el usuario seleccionado
    let usuarioSeleccionado = null;
    let lastUsuarioId = null;

    function mostrarUsuario(event) {
        // Obtener la fila clickeada
        const fila = event.currentTarget;
        const usuario = JSON.parse(fila.getAttribute('data-usuario'));
        
        // Guardar usuario globalmente
        usuarioSeleccionado = usuario;
        
        // Ocultar mensaje vacío
        document.getElementById('mensajeVacio').style.display = 'none';
        
        // Mostrar detalles del usuario
        document.getElementById('detallesUsuario').innerHTML = `
            <div class="bg-white p-6 rounded-lg">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-2xl font-bold text-gray-800">Detalles del Usuario</h3>
                    <button onclick="limpiarSeleccion()" class="btn-neu bg-gray-500 hover:bg-gray-600 text-white text-sm px-4 py-2">
                        Cerrar
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">ID</label>
                        <p class="text-lg text-gray-800">${usuario.id}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Nombre</label>
                        <p class="text-lg text-gray-800">${usuario.nombre}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Email</label>
                        <p class="text-lg text-gray-800">${usuario.email}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Rol</label>
                        <span class="px-3 py-1 rounded-full text-sm font-medium ${
                        usuario.id_rol == 2 ? 'bg-green-100 text-green-800' : 
                        usuario.id_rol == 3 ? 'bg-blue-100 text-blue-800' : 
                        'bg-gray-100 text-gray-800'}">
                        ${usuario.nombre_rol || 'Rol ' + usuario.id_rol}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Fecha de Nacimiento</label>
                        <p class="text-lg text-gray-800">${new Date(usuario.fecha_nacimiento).toLocaleDateString()}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Fecha de Registro</label>
                        <p class="text-lg text-gray-800">${new Date(usuario.fecha_registro).toLocaleDateString()}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Gasto Energético (GET)</label>
                        <p class="text-lg text-gray-800" id="getUsuario">Calculando...</p>
                    </div>
                </div>
                
                <!-- Botones de acción -->
                <div class="flex space-x-3 mt-6">
                    <button onclick="openObjetivoModal()" class="btn-neu bg-black-500 hover:bg-blue-600 text-white">
                        Ver objetivos
                    </button>
                <button onclick="openMedidasModal(${usuario.id})" class="btn-neu bg-blue-500 hover:bg-blue-600 text-white">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Ver Medidas
                </button>
                    <button onclick="console.log('🖱️ Botón clickeado, usuario.id:', ${usuario.id}); openProgresoModal(${usuario.id})" 
                            class="btn-neu bg-purple-500 hover:bg-purple-600 text-white mt-4">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Ver Progreso del Paciente (ID: ${usuario.id})
                    </button>
                </div>
            </div>
        `;
        
        // Calcular GET después de mostrar los detalles
        calcularGETUsuario(usuario.id);
        
        // Remover highlight de todas las filas y agregar a la seleccionada
        document.querySelectorAll('#tablaUsuarios tr').forEach(tr => {
            tr.classList.remove('bg-blue-50', 'border-l-4', 'border-blue-500');
        });
        fila.classList.add('bg-blue-50', 'border-l-4', 'border-blue-500');
    }

    // Función para calcular GET via AJAX
    function calcularGETUsuario(usuarioId) {
        fetch(`/menu/calcular-get/${usuarioId}`)
            .then(response => response.json())
            .then(data => {
                const getElement = document.getElementById('getUsuario');
                if (data.success) {
                    getElement.innerHTML = `<span class="font-bold text-green-600">${data.get} kcal</span>`;
                } else {
                    console.error('Error GET:', data.message);
                    getElement.innerHTML = `<span class="text-red-500">${data.message || 'No disponible'}</span>`;
                }
            })
            .catch(error => {
                console.error('Error fetch GET:', error);
                document.getElementById('getUsuario').innerHTML = '<span class="text-red-500">Error conexión</span>';
            });
    }

    // Funciones para modales
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    function limpiarSeleccion() {
        // Limpiar usuario seleccionado
        usuarioSeleccionado = null;
        
        // Mostrar mensaje vacío
        document.getElementById('detallesUsuario').innerHTML = `
            <div id="mensajeVacio" class="text-center py-8 text-gray-500">
                Selecciona un usuario para ver sus detalles
            </div>
        `;
        
        // Remover highlight de todas las filas
        document.querySelectorAll('#tablaUsuarios tr').forEach(tr => {
            tr.classList.remove('bg-blue-50', 'border-l-4', 'border-blue-500');
        });
    }

    // Funciones específicas para objetivos
    function openObjetivoModal() {
        if (!usuarioSeleccionado) {
            alert('Por favor selecciona un usuario primero');
            return;
        }
        openModal('objetivoModal');
        cargarObjetivosUsuario(usuarioSeleccionado.id);
    }

    function cargarObjetivosUsuario(usuarioId) {
        const objetivosContent = document.getElementById('objetivosContent');
        
        // Mostrar loading
        objetivosContent.innerHTML = `
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-spinner fa-spin fa-2x mb-4"></i>
                <p>Cargando objetivos...</p>
            </div>
        `;

        // Hacer petición AJAX
        fetch(`/usuarios/${usuarioId}/objetivos`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarObjetivosEnModal(data.objetivos, usuarioSeleccionado.nombre);
                } else {
                    objetivosContent.innerHTML = `
                        <div class="text-center py-8 text-red-500">
                            <i class="fas fa-exclamation-triangle fa-2x mb-4"></i>
                            <p>Error al cargar objetivos</p>
                            <p class="text-sm">${data.message}</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                objetivosContent.innerHTML = `
                    <div class="text-center py-8 text-red-500">
                        <i class="fas fa-exclamation-triangle fa-2x mb-4"></i>
                        <p>Error de conexión</p>
                        <p class="text-sm">${error.message}</p>
                    </div>
                `;
            });
    }

    function mostrarObjetivosEnModal(objetivos, nombreUsuario) {
        const objetivosContent = document.getElementById('objetivosContent');
        
        if (objetivos.length === 0) {
            objetivosContent.innerHTML = `
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-bullseye fa-2x mb-4"></i>
                    <p>El usuario <strong>${nombreUsuario}</strong> no tiene objetivos asignados</p>
                </div>
            `;
            return;
        }

        let html = `
            <div class="mb-4">
                <h4 class="font-semibold text-gray-700">Objetivos de <span class="text-blue-600">${nombreUsuario}</span></h4>
                <p class="text-sm text-gray-600">Total: ${objetivos.length} objetivos</p>
            </div>
            <div class="space-y-4 max-h-96 overflow-y-auto">
        `;

        objetivos.forEach(objetivo => {
            const estadoColor = {
                'activo': 'bg-green-100 text-green-800',
                'completado': 'bg-blue-100 text-blue-800', 
                'pendiente': 'bg-yellow-100 text-yellow-800'
            }[objetivo.estado] || 'bg-gray-100 text-gray-800';

            html += `
                <div class="neumorphic-inset p-4 rounded-lg">
                    <div class="flex justify-between items-start mb-2">
                        <h5 class="font-semibold text-gray-800">${objetivo.nombre}</h5>
                        <span class="px-2 py-1 rounded-full text-xs font-medium ${estadoColor}">
                            ${objetivo.estado}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 mb-3">${objetivo.descripcion}</p>
                    <div class="flex justify-between text-xs text-gray-500">
                        <span>Asignado: ${new Date(objetivo.fecha_asignacion).toLocaleDateString()}</span>
                        ${objetivo.calificacion ? `<span>Calificación: ${objetivo.calificacion}</span>` : ''}
                    </div>
                </div>
            `;
        });

        html += `</div>`;
        objetivosContent.innerHTML = html;
    }

    // Funciones específicas para preferencias
    function openPreferenciaModal() {
        if (!usuarioSeleccionado) {
            alert('Por favor selecciona un usuario primero');
            return;
        }
        openModal('preferenciaModal');
        cargarPreferenciasUsuario(usuarioSeleccionado.id);
    }

    function cargarPreferenciasUsuario(usuarioId) {
        const preferenciasContent = document.getElementById('preferenciasContent');
        
        // Mostrar loading
        preferenciasContent.innerHTML = `
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-spinner fa-spin fa-2x mb-4"></i>
                <p>Cargando preferencias...</p>
            </div>
        `;

        // Hacer petición AJAX
        fetch(`/usuarios/${usuarioId}/preferencias`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarPreferenciasEnModal(data.preferencias, usuarioSeleccionado.nombre);
                } else {
                    preferenciasContent.innerHTML = `
                        <div class="text-center py-8 text-red-500">
                            <i class="fas fa-exclamation-triangle fa-2x mb-4"></i>
                            <p>Error al cargar preferencias</p>
                            <p class="text-sm">${data.message}</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                preferenciasContent.innerHTML = `
                    <div class="text-center py-8 text-red-500">
                        <i class="fas fa-exclamation-triangle fa-2x mb-4"></i>
                        <p>Error de conexión</p>
                        <p class="text-sm">${error.message}</p>
                    </div>
                `;
            });
    }

    function mostrarPreferenciasEnModal(preferencias, nombreUsuario) {
        const preferenciasContent = document.getElementById('preferenciasContent');
        
        if (preferencias.length === 0) {
            preferenciasContent.innerHTML = `
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-heart fa-2x mb-4"></i>
                    <p>El usuario <strong>${nombreUsuario}</strong> no tiene preferencias asignadas</p>
                </div>
            `;
            return;
        }

        let html = `
            <div class="mb-4">
                <h4 class="font-semibold text-gray-700">Preferencias de <span class="text-red-600">${nombreUsuario}</span></h4>
                <p class="text-sm text-gray-600">Total: ${preferencias.length} preferencias</p>
            </div>
            <div class="space-y-3 max-h-96 overflow-y-auto">
        `;

        // Agrupar preferencias por tipo
        const preferenciasPorTipo = {};
        preferencias.forEach(pref => {
            if (!preferenciasPorTipo[pref.tipo]) {
                preferenciasPorTipo[pref.tipo] = [];
            }
            preferenciasPorTipo[pref.tipo].push(pref);
        });

        // Mostrar por categorías
        Object.keys(preferenciasPorTipo).forEach(tipo => {
            const colorTipo = {
                'dieta': 'bg-green-100 text-green-800',
                'alergia': 'bg-red-100 text-red-800',
                'preferencia': 'bg-blue-100 text-blue-800'
            }[tipo] || 'bg-gray-100 text-gray-800';

            html += `
                <div class="mb-4">
                    <div class="flex items-center mb-2">
                        <span class="px-3 py-1 rounded-full text-sm font-medium ${colorTipo} capitalize">
                            ${tipo}
                        </span>
                        <span class="ml-2 text-sm text-gray-500">${preferenciasPorTipo[tipo].length} items</span>
                    </div>
                    <div class="grid grid-cols-1 gap-2 ml-4">
            `;

            preferenciasPorTipo[tipo].forEach(preferencia => {
                html += `
                    <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-200">
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span class="text-gray-700">${preferencia.descripcion}</span>
                        </div>
                        <span class="text-xs text-gray-400">
                            ${new Date(preferencia.fecha_asignacion).toLocaleDateString()}
                        </span>
                    </div>
                `;
            });

            html += `
                    </div>
                </div>
            `;
        });

        html += `</div>`;
        preferenciasContent.innerHTML = html;
    }




    function cargarProgreso(pacienteId = null) {
        const loading = document.getElementById('progresoLoading');
        const data = document.getElementById('progresoData');
        const error = document.getElementById('progresoError');
        
        if (!loading || !data || !error) {
            console.error('Elementos del modal no encontrados');
            return;
        }
        
        loading.classList.remove('hidden');
        data.classList.add('hidden');
        error.classList.add('hidden');

        let url = '/progreso/datos';
        if (pacienteId) {
            url += `/${pacienteId}`;
        }

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error HTTP: ${response.status}`);
                }
                return response.json();
            })
            .then(result => {
                if (result.success) {
                    renderProgreso(result.data);
                    loading.classList.add('hidden');
                    data.classList.remove('hidden');
                } else {
                    throw new Error(result.message || 'Error desconocido del servidor');
                }
            })
            .catch(error => {
                console.error('Error cargando progreso:', error);
                loading.classList.add('hidden');
                const errorDiv = document.getElementById('progresoError');
                if (document.getElementById('errorMessage') && errorDiv) {
                    document.getElementById('errorMessage').textContent = error.message;
                    errorDiv.classList.remove('hidden');
                }
            });
    }

    function openProgresoModal(pacienteId = null) {
        const modal = document.getElementById('progresoModal');
        if (modal) {
            modal.classList.remove('hidden');
            cargarProgreso(pacienteId);
        }
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    function renderProgreso(data) {
        safeSetText('pesoPerdido', `${data.metricas.pesoPerdido > 0 ? '-' : ''}${Math.abs(data.metricas.pesoPerdido)}kg`);
        safeSetText('sesionesMes', data.metricas.sesionesMes);
        safeSetText('asistencia', `${data.metricas.asistencia}%`);
        safeSetText('incrementoFuerza', `${data.metricas.incrementoFuerza > 0 ? '+' : ''}${data.metricas.incrementoFuerza}%`);

        if (data.historialPeso) {
            renderPesoChart(data.historialPeso);
        }

        if (data.metas) {
            renderMetas(data.metas);
        }

        renderEstados(data.estadoInicial, data.estadoActual);
    }

    function safeSetText(elementId, text) {
        const element = document.getElementById(elementId);
        if (element) {
            element.textContent = text;
        }
    }

    function renderPesoChart(historialPeso) {
        const chartContainer = document.getElementById('pesoChart');
        if (!chartContainer) return;
        
        chartContainer.innerHTML = '';

        if (!historialPeso || historialPeso.length === 0) {
            chartContainer.innerHTML = '<p class="text-gray-500 text-center">No hay datos de peso</p>';
            return;
        }

        const pesos = historialPeso.map(item => item.peso).filter(peso => peso != null);
        if (pesos.length === 0) {
            chartContainer.innerHTML = '<p class="text-gray-500 text-center">No hay datos válidos de peso</p>';
            return;
        }

        const maxPeso = Math.max(...pesos);
        const minPeso = Math.min(...pesos);
        const range = maxPeso - minPeso || 1;

        historialPeso.forEach((item) => {
            if (item.peso == null) return;
            
            const height = ((item.peso - minPeso) / range) * 80 + 20;
            const bar = document.createElement('div');
            bar.className = 'flex-1 bg-gradient-to-t from-green-400 to-green-600 rounded-t transition-all duration-500';
            bar.style.height = `${height}%`;
            bar.title = `${item.peso}kg - ${new Date(item.fecha_registro).toLocaleDateString()}`;
            chartContainer.appendChild(bar);
        });
    }

    function renderMetas(metas) {
        const metasContainer = document.getElementById('metasList');
        if (!metasContainer) return;
        
        metasContainer.innerHTML = '';

        if (!metas || metas.length === 0) {
            metasContainer.innerHTML = '<p class="text-gray-500 text-center">No hay metas definidas</p>';
            return;
        }

        metas.forEach(meta => {
            const progressPercentage = Math.min(100, (meta.progreso / meta.objetivo) * 100);
            
            const metaElement = document.createElement('div');
            metaElement.className = 'flex items-center justify-between p-3 neumorphic-inset rounded-lg';
            metaElement.innerHTML = `
                <div class="flex items-center flex-1">
                    <div class="w-3 h-3 rounded-full mr-3 ${meta.completada ? 'bg-green-500' : progressPercentage > 50 ? 'bg-yellow-500' : 'bg-red-500'}"></div>
                    <span class="text-sm flex-1">${meta.descripcion}</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-20 bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full transition-all duration-500" style="width: ${progressPercentage}%"></div>
                    </div>
                    <span class="text-xs text-gray-600 w-12">${meta.progreso}/${meta.objetivo}</span>
                </div>
            `;
            metasContainer.appendChild(metaElement);
        });
    }

    function renderEstados(estadoInicial, estadoActual) {
        const estadoInicialContainer = document.getElementById('estadoInicial');
        const estadoActualContainer = document.getElementById('estadoActual');

        if (estadoInicialContainer) {
            estadoInicialContainer.innerHTML = estadoInicial ? `
                <div class="space-y-1">
                    <div><strong>Peso:</strong> ${estadoInicial.peso}kg</div>
                    <div><strong>Fecha:</strong> ${new Date(estadoInicial.fecha_registro).toLocaleDateString()}</div>
                    ${estadoInicial.estado_fisico ? `<div><strong>Estado físico:</strong> ${estadoInicial.estado_fisico}</div>` : ''}
                </div>
            ` : '<p class="text-gray-500">No hay datos iniciales</p>';
        }

        if (estadoActualContainer) {
            estadoActualContainer.innerHTML = estadoActual ? `
                <div class="space-y-1">
                    <div><strong>Peso:</strong> ${estadoActual.peso}kg</div>
                    <div><strong>Fecha:</strong> ${new Date(estadoActual.fecha_registro).toLocaleDateString()}</div>
                    ${estadoActual.estado_fisico ? `<div><strong>Estado físico:</strong> ${estadoActual.estado_fisico}</div>` : ''}
                </div>
            ` : '<p class="text-gray-500">No hay datos actuales</p>';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const progresoModal = document.getElementById('progresoModal');
        if (progresoModal) {
            progresoModal.addEventListener('click', function(e) {
                if (e.target === this || e.target.classList.contains('modal-backdrop')) {
                    cargarProgreso();
                }
            });
        }
    });

    function exportarReporte() {
        alert('Función de exportación en desarrollo...');
    }



    // Función para abrir el modal de medidas
    function openMedidasModal(usuarioId) {
        lastUsuarioId = usuarioId;
        const modal = document.getElementById('openMedidasModal');
        if (modal) {
            modal.classList.remove('hidden');
            cargarMedidasUsuario(usuarioId);
        }
    }

    // Función para cargar medidas del usuario
    function cargarMedidasUsuario(usuarioId) {
        const loading = document.getElementById('medidasLoading');
        const data = document.getElementById('medidasData');
        const error = document.getElementById('medidasError');
        
        if (!loading || !data || !error) {
            console.error('Elementos del modal no encontrados');
            return;
        }
        
        // Mostrar loading, ocultar otros
        loading.classList.remove('hidden');
        data.classList.add('hidden');
        error.classList.add('hidden');

        fetch(`/entrenador/medidas/${usuarioId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error HTTP: ${response.status}`);
                }
                return response.json();
            })
            .then(result => {
                if (result.success) {
                    mostrarMedidas(result.medidas);
                    loading.classList.add('hidden');
                    data.classList.remove('hidden');
                } else {
                    throw new Error(result.message || 'Error al cargar medidas');
                }
            })
            .catch(error => {
                console.error('Error cargando medidas:', error);
                loading.classList.add('hidden');
                const errorDiv = document.getElementById('medidasError');
                if (document.getElementById('medidasErrorMessage') && errorDiv) {
                    document.getElementById('medidasErrorMessage').textContent = error.message;
                    errorDiv.classList.remove('hidden');
                }
            });
    }

    // Función para mostrar medidas en el modal
    function mostrarMedidas(medidas) {
        const medidasContainer = document.getElementById('medidasData');
        
        if (!medidas || medidas.length === 0) {
            medidasContainer.innerHTML = `
                <div class="text-center py-8">
                    <div class="text-gray-400 text-6xl mb-4">📊</div>
                    <p class="text-gray-500 text-lg">No hay medidas registradas</p>
                    <p class="text-gray-400 text-sm mt-2">Este usuario aún no ha registrado sus medidas corporales.</p>
                </div>
            `;
            return;
        }

        let html = '';
        medidas.forEach((medida, index) => {
            html += `
                <div class="neumorphic-inset p-4 rounded-lg">
                    <div class="flex justify-between items-start mb-3 border-b pb-2">
                        <h4 class="font-semibold text-gray-700 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Medición #${index + 1} - ${new Date(medida.fecha_registro).toLocaleDateString()}
                        </h4>
                        <span class="bg-blue-100 text-blue-800 text-sm font-medium px-2 py-1 rounded">
                            ${medida.peso} kg
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Talla:</span>
                            <span class="font-medium">${medida.talla || 'N/A'} cm</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Edad:</span>
                            <span class="font-medium">${medida.edad || 'N/A'}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Género:</span>
                            <span class="font-medium">${medida.genero === 'M' ? 'Masculino' : medida.genero === 'F' ? 'Femenino' : 'N/A'}</span>
                        </div>
                        ${medida.circunferencia_brazo ? `
                        <div class="flex justify-between">
                            <span class="text-gray-600">Brazo:</span>
                            <span class="font-medium">${medida.circunferencia_brazo} cm</span>
                        </div>
                        ` : ''}
                        ${medida.circunferencia_cintura ? `
                        <div class="flex justify-between">
                            <span class="text-gray-600">Cintura:</span>
                            <span class="font-medium">${medida.circunferencia_cintura} cm</span>
                        </div>
                        ` : ''}
                        ${medida.circunferencia_caderas ? `
                        <div class="flex justify-between">
                            <span class="text-gray-600">Caderas:</span>
                            <span class="font-medium">${medida.circunferencia_caderas} cm</span>
                        </div>
                        ` : ''}
                        ${medida.circunferencia_muslos ? `
                        <div class="flex justify-between">
                            <span class="text-gray-600">Muslos:</span>
                            <span class="font-medium">${medida.circunferencia_muslos} cm</span>
                        </div>
                        ` : ''}
                        ${medida.circunferencia_pantorrilla ? `
                        <div class="flex justify-between">
                            <span class="text-gray-600">Pantorrilla:</span>
                            <span class="font-medium">${medida.circunferencia_pantorrilla} cm</span>
                        </div>
                        ` : ''}
                    </div>
                </div>
            `;
        });

        medidasContainer.innerHTML = html;
    }

    // Función para cerrar el modal (debes tenerla)
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
        }
    }


</script>




@endsection