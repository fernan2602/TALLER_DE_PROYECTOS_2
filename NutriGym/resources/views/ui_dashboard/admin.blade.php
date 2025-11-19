@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <h5>🔄 Sistema de Backup</h5>
    </div>
    <div class="card-body">
        <p class="text-muted">Haz clic en el botón para crear un backup completo del sistema.</p>
        
        <button id="backupBtn" class="btn btn-primary">
            <i class="fas fa-database"></i> Generar Backup Ahora
        </button>
        
        <div id="backupResult" class="mt-3" style="display: none;"></div>
        <div id="backupLoading" class="mt-3" style="display: none;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Generando backup...</span>
            </div>
            <span class="ms-2">Generando backup, por favor espere...</span>
        </div>
    </div>
</div>

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
document.getElementById('backupBtn').addEventListener('click', function() {
    const btn = this;
    const resultDiv = document.getElementById('backupResult');
    const loadingDiv = document.getElementById('backupLoading');
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generando...';
    loadingDiv.style.display = 'block';
    resultDiv.style.display = 'none';
    
    fetch('{{ route("backup.create") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        loadingDiv.style.display = 'none';
        resultDiv.style.display = 'block';
        
        if (data.success) {
            resultDiv.innerHTML = `
                <div class="alert alert-success">
                    <h6>✅ Backup Completado Exitosamente</h6>
                    <p>${data.message}</p>
                    <div class="mt-2">
                        <strong>Carpeta:</strong> ${data.backup_folder}<br>
                        <strong>Tamaño:</strong> ${data.backup_size}
                    </div>
                    <small class="text-muted">${data.note}</small>
                </div>
            `;
        } else {
            resultDiv.innerHTML = `
                <div class="alert alert-danger">
                    <h6>❌ Error en el Backup</h6>
                    <p>${data.message}</p>
                </div>
            `;
        }
    })
    .catch(error => {
        loadingDiv.style.display = 'none';
        resultDiv.style.display = 'block';
        resultDiv.innerHTML = `
            <div class="alert alert-danger">
                <h6>❌ Error de Conexión</h6>
                <p>${error.message}</p>
            </div>
        `;
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-database"></i> Generar Backup Ahora';
    });
});

    function mostrarUsuario(event) {
        // Obtener la fila clickeada
        const fila = event.currentTarget;
        const usuario = JSON.parse(fila.getAttribute('data-usuario'));
        
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
                </div>
                
                <!-- Botones de acción -->
                <div class="flex space-x-3 mt-6">
                    <button onclick="openModal('progresoModal')" class="btn-neu bg-blue-500 hover:bg-blue-600 text-white">
                        Ver progreso
                    </button>
                    <button class="btn-neu bg-red-500 hover:bg-red-600 text-white">
                        Eliminar
                    </button>
                </div>
            </div>
        `;
        
        // Remover highlight de todas las filas y agregar a la seleccionada
        document.querySelectorAll('#tablaUsuarios tr').forEach(tr => {
            tr.classList.remove('bg-blue-50', 'border-l-4', 'border-blue-500');
        });
        fila.classList.add('bg-blue-50', 'border-l-4', 'border-blue-500');
    }

    // Funciones para el modal de progreso
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    function limpiarSeleccion() {
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



@endsection

