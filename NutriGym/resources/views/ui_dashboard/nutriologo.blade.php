@extends('layouts.app')

@section('content')


<!-- component -->
<div class="bg-gray-100">

        <div class="header my-3 h-12 px-10 flex items-center justify-between">
            <h1 class="font-medium text-2xl">Clientes</h1>
        </div>
</div>

<div class="flex flex-col lg:flex-row gap-4">
    <!-- Tabla de usuarios -->
    <div class="w-full lg:w-1/3">
        <div class="neumorphic p-6">
            <div class="w-full md:w-full px-3 mb-6">
                <label class="block uppercase tracking-wide text-gray-700 text-sm font-bold mb-2" htmlFor="category_name">
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
            <div class="w-full md:w-full px-3 mb-6">
                <button class="btn-neu w-full">
                    Buscar cliente
                </button>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-4">Lista de Usuarios</h3>
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-gray-200">
                        <th class="text-left p-4 font-semibold text-gray-700">ID</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Nombre</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Rol</th>
                    </tr>
                </thead>
                <tbody id="tablaUsuarios">
                    @foreach($usuarios as $usuario)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition duration-200 cursor-pointer"
                        onclick="mostrarUsuario(event)"
                        data-usuario='@json($usuario)'>
                        <td class="p-4 text-gray-600">{{ $usuario->id }}</td>
                        <td class="p-4 text-gray-800 font-medium">{{ $usuario->nombre }}</td>
                        <td class="p-4">
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                @if($usuario->id_rol == 2) bg-green-100 text-green-800
                                @elseif($usuario->id_rol == 3) bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $usuario->nombre_rol ?? 'Rol ' . $usuario->id_rol }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Panel de detalles -->
    <div class="w-full lg:w-2/3">
        <div class="neumorphic p-6">
            <div id="detallesUsuario">
                <div id="mensajeVacio" class="text-center py-8 text-gray-500">
                    Selecciona un usuario para ver sus detalles
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Variable global para almacenar el usuario seleccionado
let usuarioSeleccionado = null;

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
                <button onclick="openPreferenciaModal()" class="btn-neu bg-red-500 hover:bg-blue-600 text-white">
                    Ver Preferencias
                </button>
                <button onclick="openModal('progresoModal')" class="btn-neu bg-blue-500 hover:bg-black-600 text-white">
                    Ver progreso
                </button>
                <button class="btn-neu bg-red-500 hover:bg-red-600 text-white">
                    Eliminar
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
</script>

<!-- Modal de progreso (fuera de la función) -->
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
                    <button onclick="closeModal('progresoModal')" class="btn-neu bg-gray-500 hover:bg-gray-600 text-white flex-1">
                        Cerrar
                    </button>
                    <button class="btn-neu bg-blue-500 hover:bg-blue-600 text-white flex-1">
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
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="modal-content neumorphic w-full max-w-2xl">
            <div class="p-6">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-bullseye mr-2 text-blue-500"></i>
                        Objetivos del Usuario
                    </h3>
                    <button onclick="closeModal('objetivoModal')" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Contenido dinámico -->
                <div id="objetivosContent">
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-spinner fa-spin fa-2x mb-4"></i>
                        <p>Cargando objetivos...</p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex justify-end mt-6">
                    <button onclick="closeModal('objetivoModal')" class="btn-neu bg-gray-500 hover:bg-gray-600 text-white px-6 py-2">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Preferencias -->
<div id="preferenciaModal" class="fixed inset-0 z-50 hidden">
    <div class="modal-backdrop fixed inset-0" onclick="closeModal('preferenciaModal')"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="modal-content neumorphic w-full max-w-2xl">
            <div class="p-6">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-heart mr-2 text-red-500"></i>
                        Preferencias del Usuario
                    </h3>
                    <button onclick="closeModal('preferenciaModal')" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Contenido dinámico -->
                <div id="preferenciasContent">
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-spinner fa-spin fa-2x mb-4"></i>
                        <p>Cargando preferencias...</p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex justify-end mt-6">
                    <button onclick="closeModal('preferenciaModal')" class="btn-neu bg-gray-500 hover:bg-gray-600 text-white px-6 py-2">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection

