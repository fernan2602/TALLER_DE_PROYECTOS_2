@extends('layouts.app')

@section('content')

<div class="bg-white border border-gray-200 rounded-lg shadow-sm">
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-4 py-3 rounded-t-lg">
        <h5 class="text-white font-semibold text-lg flex items-center">
            <i class="fas fa-database mr-2"></i>
            Sistema de Backup
        </h5>
    </div>
    <div class="p-4">
        <p class="text-gray-600 mb-4">Haz clic en el botón para crear un backup completo del sistema.</p>
        
        <button id="backupBtn" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2.5 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center">
            <i class="fas fa-database mr-2"></i>
            Generar Backup Ahora
        </button>
        
        <div id="backupResult" class="mt-3" style="display: none;"></div>
        <div id="backupLoading" class="mt-3" style="display: none;">
            <div class="flex items-center justify-center">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500 mr-2"></div>
                <span class="text-gray-600">Generando backup, por favor espere...</span>
            </div>
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
    let usuarioSeleccionado = null;
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
                </div>
                
                <!-- Botones de acción -->
                <div class="flex flex-col sm:flex-row flex-wrap gap-3 mt-6">

                    ${
                        usuario.id_rol == 4 
                        ? `
                            <button onclick="openObjetivoModal()" 
                                    class="btn-neu bg-black-500 hover:bg-blue-600 text-white w-full sm:w-auto text-sm">
                                Ver objetivos
                            </button>

                            <button onclick="openPreferenciaModal()" 
                                    class="btn-neu bg-red-500 hover:bg-blue-600 text-white w-full sm:w-auto text-sm">
                                Ver Preferencias
                            </button>

                            <button onclick="openProgresoModal(${usuario.id})"
                                    class="btn-neu bg-purple-500 hover:bg-purple-600 text-white w-full sm:w-auto text-sm">
                                Ver Progreso del Paciente (ID: ${usuario.id})
                            </button>

                            <button onclick="openDietasModal(${usuario.id})"
                                    class="btn-neu bg-green-500 hover:bg-green-600 text-white w-full sm:w-auto text-sm">
                                Ver Dieta (ID: ${usuario.id})
                            </button>
                        `
                        : `
                            <button class="btn-neu bg-gray-500 hover:bg-gray-600 text-white w-full sm:w-auto text-sm">
                                Este usuario no tiene opciones avanzadas
                            </button>
                        `
                    }

                    <button class="btn-neu bg-red-500 hover:bg-red-600 text-white w-full sm:w-auto text-sm">
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
        
        objetivosContent.innerHTML = `
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-spinner fa-spin fa-2x mb-4"></i>
                <p class="text-sm sm:text-base">Cargando objetivos...</p>
            </div>
        `;

        fetch(`/usuarios/${usuarioId}/objetivos`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarObjetivosEnModal(data.objetivos, usuarioSeleccionado.nombre);
                } else {
                    objetivosContent.innerHTML = `
                        <div class="text-center py-8 text-red-500">
                            <i class="fas fa-exclamation-triangle fa-2x mb-4"></i>
                            <p class="text-sm sm:text-base">Error al cargar objetivos</p>
                            <p class="text-xs sm:text-sm">${data.message}</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                objetivosContent.innerHTML = `
                    <div class="text-center py-8 text-red-500">
                        <i class="fas fa-exclamation-triangle fa-2x mb-4"></i>
                        <p class="text-sm sm:text-base">Error de conexión</p>
                        <p class="text-xs sm:text-sm">${error.message}</p>
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
                    <p class="text-sm sm:text-base">El usuario <strong>${nombreUsuario}</strong> no tiene objetivos asignados</p>
                </div>
            `;
            return;
        }

        let html = `
            <div class="mb-4">
                <h4 class="font-semibold text-gray-700 text-sm sm:text-base">Objetivos de <span class="text-blue-600">${nombreUsuario}</span></h4>
                <p class="text-xs sm:text-sm text-gray-600">Total: ${objetivos.length} objetivos</p>
            </div>
            <div class="space-y-4 max-h-96 overflow-y-auto pr-1 sm:pr-2">
        `;

        objetivos.forEach(objetivo => {
            const estadoColor = {
                'activo': 'bg-green-100 text-green-800',
                'completado': 'bg-blue-100 text-blue-800', 
                'pendiente': 'bg-yellow-100 text-yellow-800'
            }[objetivo.estado] || 'bg-gray-100 text-gray-800';

            html += `
                <div class="neumorphic-inset p-3 sm:p-4 rounded-lg">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-2 gap-2">
                        <h5 class="font-semibold text-gray-800 text-sm sm:text-base">${objetivo.nombre}</h5>
                        <span class="self-start px-2 py-1 rounded-full text-[10px] sm:text-xs font-medium ${estadoColor}">
                            ${objetivo.estado}
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-gray-600 mb-3">${objetivo.descripcion}</p>
                    <div class="flex justify-between text-[10px] sm:text-xs text-gray-500">
                        <span>Asignado: ${new Date(objetivo.fecha_asignacion).toLocaleDateString()}</span>
                        ${objetivo.calificacion ? `<span>Calificación: ${objetivo.calificacion}</span>` : ''}
                    </div>
                </div>
            `;
        });

        html += `</div>`;
        objetivosContent.innerHTML = html;
    }

    // ---------- PROGRESO ----------
    function cargarProgreso(pacienteId = null) {
        const loading = document.getElementById('progresoLoading');
        const data = document.getElementById('progresoData');
        const error = document.getElementById('progresoError');
        
        if (!loading || !data || !error) {
            console.error('Elementos del progreso no encontrados');
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

    function renderPesoChart(historialPeso) {
        const chartContainer = document.getElementById('pesoChart');
        if (!chartContainer) return;
        
        chartContainer.innerHTML = '';

        if (!historialPeso || historialPeso.length === 0) {
            chartContainer.innerHTML = '<p class="text-gray-500 text-center text-xs md:text-sm w-full">No hay datos de peso</p>';
            return;
        }

        historialPeso.sort((a, b) => new Date(a.fecha_registro) - new Date(b.fecha_registro));
        
        const pesos = historialPeso.map(item => item.peso).filter(peso => peso != null);
        if (pesos.length === 0) {
            chartContainer.innerHTML = '<p class="text-gray-500 text-center text-xs md:text-sm w-full">No hay datos válidos de peso</p>';
            return;
        }

        const maxPeso = Math.max(...pesos);
        const minPeso = Math.min(...pesos);
        const rangoTotal = maxPeso - minPeso;
        const rangoVisual = rangoTotal > 5 ? rangoTotal : 10;
        const pesoBase = minPeso - 2;

        const chartWrapper = document.createElement('div');
        chartWrapper.className = 'w-full h-40 sm:h-48 md:h-64 flex items-end justify-between space-x-2 md:space-x-4 px-1 sm:px-2';

        historialPeso.forEach((item, index) => {
            if (item.peso == null) return;
            
            const alturaPorcentaje = ((item.peso - pesoBase) / rangoVisual) * 90;
            const barContainer = document.createElement('div');
            barContainer.className = 'flex flex-col items-center justify-end flex-1 h-full relative';

            const bar = document.createElement('div');
            const esUltimo = index === historialPeso.length - 1;
            const esPrimero = index === 0;
            
            bar.className = `w-3 sm:w-4 md:w-5 rounded-t-lg transition-all duration-700 cursor-pointer shadow ${
                esUltimo ? 'bg-gradient-to-t from-red-400 via-red-500 to-red-600 ring-1 ring-yellow-300' :
                esPrimero ? 'bg-gradient-to-t from-green-400 via-green-500 to-green-600' :
                'bg-gradient-to-t from-blue-400 via-blue-500 to-blue-600'
            } hover:scale-110`;
            bar.style.height = '0%';
            
            const valueLabel = document.createElement('div');
            valueLabel.className = 'text-[10px] sm:text-xs md:text-sm font-bold text-gray-800 mb-1';
            valueLabel.textContent = `${item.peso}kg`;
            
            const dateLabel = document.createElement('div');
            dateLabel.className = 'text-[9px] sm:text-[10px] text-gray-600 mt-1 text-center';
            const fecha = new Date(item.fecha_registro);
            dateLabel.textContent = fecha.toLocaleDateString('es-ES', { 
                month: 'short', 
                day: 'numeric' 
            });
            
            barContainer.appendChild(valueLabel);
            barContainer.appendChild(bar);
            barContainer.appendChild(dateLabel);
            chartWrapper.appendChild(barContainer);

            setTimeout(() => {
                bar.style.height = `${alturaPorcentaje}%`;
            }, index * 150);
        });
        
        chartContainer.appendChild(chartWrapper);

        const legend = document.createElement('div');
        legend.className = 'flex flex-wrap justify-center space-x-2 sm:space-x-4 mt-3 text-[9px] sm:text-[10px]';
        legend.innerHTML = `
            <div class="flex items-center space-x-1 mt-1">
                <div class="w-3 h-3 bg-green-500 rounded"></div>
                <span>Inicio</span>
            </div>
            <div class="flex items-center space-x-1 mt-1">
                <div class="w-3 h-3 bg-blue-500 rounded"></div>
                <span>Progreso</span>
            </div>
            <div class="flex items-center space-x-1 mt-1">
                <div class="w-3 h-3 bg-red-500 rounded"></div>
                <span>Actual</span>
            </div>
        `;
        chartContainer.appendChild(legend);
    }

    function renderMetas(metas) {
        const metasContainer = document.getElementById('metasList');
        if (!metasContainer) return;
        
        metasContainer.innerHTML = '';

        if (!metas || metas.length === 0) {
            metasContainer.innerHTML = '<p class="text-gray-500 text-center text-sm md:text-base">No hay metas definidas</p>';
            return;
        }

        const metasOrdenadas = metas.sort((a, b) => {
            if (a.completada && !b.completada) return -1;
            if (!a.completada && b.completada) return 1;
            
            const porcentajeA = a.porcentaje !== undefined ? a.porcentaje : Math.min(100, (a.progreso / a.objetivo) * 100);
            const porcentajeB = b.porcentaje !== undefined ? b.porcentaje : Math.min(100, (b.progreso / b.objetivo) * 100);
            
            return porcentajeB - porcentajeA;
        });

        metasOrdenadas.forEach(meta => {
            const isCompleted = meta.completada;
            
            const metaElement = document.createElement('div');
            metaElement.className = `p-3 md:p-4 rounded-lg border-2 transition-all duration-300 ${
                isCompleted ? 'bg-green-50 border-green-200' : 'bg-white border-gray-200'
            }`;
            
            metaElement.innerHTML = `
                <div class="flex items-center">
                    <div class="flex-shrink-0 mr-3">
                        <div class="w-3 h-3 rounded-full ${
                            isCompleted ? 'bg-green-500' : 'bg-blue-500'
                        }"></div>
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold text-gray-800 text-xs sm:text-sm md:text-base ${
                            isCompleted ? 'line-through text-green-700' : ''
                        }">
                            ${meta.descripcion}
                            ${isCompleted ? ' ✅' : ''}
                        </div>
                        ${meta.objetivo_origen ? `
                            <div class="text-[10px] sm:text-xs text-gray-500 mt-1">${meta.objetivo_origen}</div>
                        ` : ''}
                    </div>
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
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <strong class="text-sm md:text-base text-gray-700">Peso:</strong>
                        <span class="text-sm md:text-base font-bold text-gray-900">${estadoInicial.peso}kg</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <strong class="text-sm md:text-base text-gray-700">Fecha:</strong>
                        <span class="text-xs md:text-sm text-gray-600">${new Date(estadoInicial.fecha_registro).toLocaleDateString()}</span>
                    </div>
                    ${estadoInicial.estado_fisico ? `
                        <div class="flex justify-between items-center">
                            <strong class="text-sm md:text-base text-gray-700">Estado físico:</strong>
                            <span class="text-xs md:text-sm px-2 py-1 rounded-full font-semibold ${
                                estadoInicial.estado_fisico >= 8 ? 'bg-green-100 text-green-800' :
                                estadoInicial.estado_fisico >= 6 ? 'bg-yellow-100 text-yellow-800' :
                                'bg-red-100 text-red-800'
                            }">${estadoInicial.estado_fisico}/10</span>
                        </div>
                    ` : ''}
                </div>
            ` : '<p class="text-gray-500 text-sm md:text-base text-center">No hay datos iniciales</p>';
        }

        if (estadoActualContainer) {
            estadoActualContainer.innerHTML = estadoActual ? `
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <strong class="text-sm md:text-base text-gray-700">Peso:</strong>
                        <span class="text-sm md:text-base font-bold ${
                            estadoInicial && estadoActual.peso < estadoInicial.peso ? 'text-green-600' : 
                            estadoInicial && estadoActual.peso > estadoInicial.peso ? 'text-red-600' : 
                            'text-gray-900'
                        }">${estadoActual.peso}kg</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <strong class="text-sm md:text-base text-gray-700">Fecha:</strong>
                        <span class="text-xs md:text-sm text-gray-600">${new Date(estadoActual.fecha_registro).toLocaleDateString()}</span>
                    </div>
                    ${estadoActual.estado_fisico ? `
                        <div class="flex justify-between items-center">
                            <strong class="text-sm md:text-base text-gray-700">Estado físico:</strong>
                            <span class="text-xs md:text-sm px-2 py-1 rounded-full font-semibold ${
                                estadoActual.estado_fisico >= 8 ? 'bg-green-100 text-green-800' :
                                estadoActual.estado_fisico >= 6 ? 'bg-yellow-100 text-yellow-800' :
                                'bg-red-100 text-red-800'
                            }">${estadoActual.estado_fisico}/10</span>
                        </div>
                    ` : ''}
                    ${estadoInicial && estadoActual ? `
                        <div class="border-t pt-3 mt-2">
                            <div class="flex justify-between items-center text-xs sm:text-sm">
                                <span class="text-gray-600 font-medium">Cambio total:</span>
                                <span class="font-bold ${
                                    estadoActual.peso < estadoInicial.peso ? 'text-green-600' : 
                                    estadoActual.peso > estadoInicial.peso ? 'text-red-600' : 
                                    'text-gray-600'
                                }">
                                    ${estadoActual.peso < estadoInicial.peso ? '▼' : 
                                    estadoActual.peso > estadoInicial.peso ? '▲' : '='} 
                                    ${Math.abs(estadoActual.peso - estadoInicial.peso).toFixed(1)}kg
                                </span>
                            </div>
                        </div>
                    ` : ''}
                </div>
            ` : '<p class="text-gray-500 text-sm md:text-base text-center">No hay datos actuales</p>';
        }
    }

    function renderProgreso(data) {
        safeSetText('pesoPerdido', `${data.metricas.pesoPerdido > 0 ? '-' : ''}${Math.abs(data.metricas.pesoPerdido).toFixed(1)}kg`);    
        safeSetText('reduccionGrasa', `${data.metricas.reduccionGrasa > 0 ? '-' : '+'}${Math.abs(data.metricas.reduccionGrasa).toFixed(1)}%`);
        safeSetText('gananciaMuscular', `${data.metricas.gananciaMuscular > 0 ? '+' : ''}${data.metricas.gananciaMuscular.toFixed(1)}kg`);
        safeSetText('mejoraIMC', `${data.metricas.mejoraIMC > 0 ? '+' : ''}${data.metricas.mejoraIMC.toFixed(2)}`);
        safeSetText('progresoGeneral', `${Math.round(data.metricas.progresoGeneral)}%`);

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

    function exportarReporte() {
        alert('Función de exportación en desarrollo...');
    }

    // ---------- PREFERENCIAS ----------
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
        
        preferenciasContent.innerHTML = `
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-spinner fa-spin fa-2x mb-4"></i>
                <p class="text-sm sm:text-base">Cargando preferencias...</p>
            </div>
        `;

        fetch(`/usuarios/${usuarioId}/preferencias`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarPreferenciasEnModal(data.preferencias, usuarioSeleccionado.nombre);
                } else {
                    preferenciasContent.innerHTML = `
                        <div class="text-center py-8 text-red-500">
                            <i class="fas fa-exclamation-triangle fa-2x mb-4"></i>
                            <p class="text-sm sm:text-base">Error al cargar preferencias</p>
                            <p class="text-xs sm:text-sm">${data.message}</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                preferenciasContent.innerHTML = `
                    <div class="text-center py-8 text-red-500">
                        <i class="fas fa-exclamación-triangle fa-2x mb-4"></i>
                        <p class="text-sm sm:text-base">Error de conexión</p>
                        <p class="text-xs sm:text-sm">${error.message}</p>
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
                    <p class="text-sm sm:text-base">El usuario <strong>${nombreUsuario}</strong> no tiene preferencias asignadas</p>
                </div>
            `;
            return;
        }

        let html = `
            <div class="mb-4">
                <h4 class="font-semibold text-gray-700 text-sm sm:text-base">Preferencias de <span class="text-red-600">${nombreUsuario}</span></h4>
                <p class="text-xs sm:text-sm text-gray-600">Total: ${preferencias.length} preferencias</p>
            </div>
            <div class="space-y-3 max-h-96 overflow-y-auto pr-1 sm:pr-2">
        `;

        const preferenciasPorTipo = {};
        preferencias.forEach(pref => {
            if (!preferenciasPorTipo[pref.tipo]) {
                preferenciasPorTipo[pref.tipo] = [];
            }
            preferenciasPorTipo[pref.tipo].push(pref);
        });

        Object.keys(preferenciasPorTipo).forEach(tipo => {
            const colorTipo = {
                'dieta': 'bg-green-100 text-green-800',
                'alergia': 'bg-red-100 text-red-800',
                'preferencia': 'bg-blue-100 text-blue-800'
            }[tipo] || 'bg-gray-100 text-gray-800';

            html += `
                <div class="mb-4">
                    <div class="flex flex-wrap items-center mb-2 gap-2">
                        <span class="px-3 py-1 rounded-full text-xs sm:text-sm font-medium ${colorTipo} capitalize">
                            ${tipo}
                        </span>
                        <span class="text-xs sm:text-sm text-gray-500">${preferenciasPorTipo[tipo].length} items</span>
                    </div>
                    <div class="grid grid-cols-1 gap-2 ml-2 sm:ml-4">
            `;

            preferenciasPorTipo[tipo].forEach(preferencia => {
                html += `
                    <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-200">
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3 text-xs sm:text-sm"></i>
                            <span class="text-xs sm:text-sm text-gray-700">${preferencia.descripcion}</span>
                        </div>
                        <span class="text-[10px] sm:text-xs text-gray-400">
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

    function openDietasModal(usuarioId) {
        document.getElementById('dietasModal').classList.remove('hidden');
        
        const dietasContainer = document.getElementById('dietasContent');
        dietasContainer.innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-spinner fa-spin text-blue-500 text-2xl mb-2"></i>
                <p class="text-gray-500 mt-2 text-sm sm:text-base">Cargando dietas...</p>
            </div>
        `;
        
        fetch(`/usuario/${usuarioId}/dietas`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la petición');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    renderDietas(data.data);
                } else {
                    mostrarErrorDietas(data.message || 'Error al cargar las dietas');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarErrorDietas('Error de conexión');
            });
    }

    function mostrarErrorDietas(mensaje) {
        const dietasContainer = document.getElementById('dietasContent');
        dietasContainer.innerHTML = `
            <div class="text-center py-8 text-red-600">
                <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                <p class="text-sm sm:text-base">${mensaje}</p>
                <button onclick="cerrarDietas()" class="mt-4 px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 text-xs sm:text-sm">
                    Cerrar
                </button>
            </div>
        `;
    }

    function renderDietas(menus) {
        const dietasContainer = document.getElementById('dietasContent');
        
        if (!menus || menus.length === 0) {
            dietasContainer.innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-utensils text-gray-400 text-4xl mb-4"></i>
                    <p class="text-gray-500 text-sm sm:text-base">No tienes dietas asignadas</p>
                </div>
            `;
            return;
        }

        const menusPorTipo = {
            'desayuno': [],
            'almuerzo': [], 
            'cena': [],
            'general': []
        };

        menus.forEach(menu => {
            const tipo = menu.tipo || 'general';
            if (menusPorTipo[tipo]) {
                menusPorTipo[tipo].push(menu);
            }
        });

        dietasContainer.innerHTML = `
            <div class="space-y-6 sm:space-y-8">
                ${Object.entries(menusPorTipo).map(([tipo, menusDelTipo]) => {
                    if (menusDelTipo.length === 0) return '';
                    
                    return `
                    <div class="border border-gray-200 rounded-xl p-4 sm:p-6 bg-white">
                        <!-- Header del tipo -->
                        <div class="flex flex-wrap items-center mb-4 sm:mb-6 pb-3 sm:pb-4 border-b border-gray-100 gap-2 sm:gap-3">
                            <i class="fas ${getIconoTipo(tipo)} text-xl sm:text-2xl text-${getColorTipo(tipo)}-500"></i>
                            <div>
                                <h4 class="font-bold text-gray-800 text-base sm:text-lg md:text-xl capitalize">${tipo}</h4>
                                <p class="text-gray-600 text-xs sm:text-sm">${menusDelTipo.length} menú(s) asignado(s)</p>
                            </div>
                        </div>

                        <!-- Menús de este tipo -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                            ${menusDelTipo.map(menu => {
                                const alimentosMenu = menu.alimentos || [];
                                const caloriasTotales = menu.calorias || alimentosMenu.reduce((sum, al) => sum + (al.calorias || 0), 0);
                                
                                return `
                                <div class="border border-gray-200 rounded-lg p-4 sm:p-5 bg-gray-50 hover:shadow-md transition-shadow">
                                    <!-- Header del menú -->
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-3 sm:mb-4 gap-2">
                                        <div class="flex-1">
                                            <h5 class="font-semibold text-gray-800 text-sm sm:text-base md:text-lg mb-1">
                                                ${menu.nombre || `Menú ${tipo}`}
                                            </h5>
                                            <div class="flex items-center text-[10px] sm:text-xs text-gray-500">
                                                <i class="fas fa-calendar mr-1"></i>
                                                ${new Date(menu.fecha_asignacion).toLocaleDateString('es-ES')}
                                            </div>
                                        </div>
                                        <span class="self-start bg-${getColorTipo(tipo)}-100 text-${getColorTipo(tipo)}-800 text-xs sm:text-sm font-semibold px-3 py-1 rounded-full whitespace-nowrap">
                                            ${caloriasTotales} kcal
                                        </span>
                                    </div>

                                    ${menu.descripcion ? `
                                        <p class="text-xs sm:text-sm text-gray-600 mb-3 sm:mb-4">${menu.descripcion}</p>
                                    ` : ''}

                                    <!-- Alimentos del menú -->
                                    <div class="mb-3 sm:mb-4">
                                        <h6 class="font-medium text-gray-700 text-xs sm:text-sm mb-2 sm:mb-3 flex items-center">
                                            <i class="fas fa-utensil-spoon mr-2 text-xs sm:text-sm"></i>
                                            Alimentos (${alimentosMenu.length})
                                        </h6>
                                        <div class="space-y-2">
                                            ${alimentosMenu.map(alimento => `
                                                <div class="flex justify-between items-center text-[11px] sm:text-xs md:text-sm p-2 bg-white rounded border border-gray-100">
                                                    <span class="font-medium text-gray-800 flex-1 pr-2">
                                                        ${alimento.alimento?.nombre || 'Alimento'}
                                                    </span>
                                                    <div class="text-right text-[10px] sm:text-xs text-gray-600 whitespace-nowrap">
                                                        ${alimento.cantidad || ''} ${alimento.unidad || ''}
                                                        ${alimento.calorias ? ` • ${alimento.calorias} kcal` : ''}
                                                    </div>
                                                </div>
                                            `).join('')}
                                        </div>
                                    </div>

                                    <!-- Footer del menú -->
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pt-3 border-t border-gray-200 gap-2 sm:gap-3 text-[11px] sm:text-xs text-gray-500">
                                        <div class="flex items-center space-x-2 sm:space-x-3">
                                            <span class="bg-white px-2 py-1 rounded border">
                                                ID: ${menu.id}
                                            </span>
                                            <button onclick="validarMenu(${menu.id})" 
                                                    class="w-8 h-8 flex items-center justify-center rounded border-2 ${
                                                        menu.validado 
                                                            ? 'bg-green-500 border-green-600 text-white' 
                                                            : 'bg-gray-200 border-gray-300 text-gray-400'
                                                    } hover:scale-110 transition-all duration-200"
                                                    title="${menu.validado ? 'Menú validado - Click para invalidar' : 'Menú pendiente - Click para validar'}">
                                                <i class="fas ${menu.validado ? 'fa-check' : 'fa-times'} text-xs"></i>
                                            </button>
                                        </div>
                                        <span class="text-[10px] sm:text-xs text-gray-500 text-right w-full sm:w-auto">
                                            Asignación: ${menu.id_usuario}
                                        </span>
                                    </div>
                                </div>
                                `;
                            }).join('')}
                        </div>
                    </div>
                    `;
                }).join('')}
            </div>
        `;
    }

    function validarMenu(menuId) {
        console.log(`Validar/Invalidar menú ID: ${menuId}`);
        
        fetch(`/menu/${menuId}/toggle-validacion`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const usuarioId = data.menu.id_usuario;
                openDietasModal(usuarioId);
                mostrarNotificacion(`Menú ${data.menu.validado ? 'validado' : 'invalidado'} correctamente`, 'success');
            } else {
                mostrarNotificacion('Error al validar el menú', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarNotificacion('Error de conexión', 'error');
        });
    }

    function mostrarNotificacion(mensaje, tipo) {
        const notificacion = document.createElement('div');
        notificacion.className = `fixed top-4 right-4 p-3 sm:p-4 rounded-lg shadow-lg z-50 text-xs sm:text-sm ${
            tipo === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        notificacion.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${tipo === 'success' ? 'fa-check' : 'fa-exclamation-triangle'} mr-2"></i>
                <span>${mensaje}</span>
            </div>
        `;
        
        document.body.appendChild(notificacion);
        
        setTimeout(() => {
            notificacion.remove();
        }, 3000);
    }

    function getIconoTipo(tipo) {
        const iconos = {
            'desayuno': 'fa-coffee',
            'almuerzo': 'fa-utensils', 
            'cena': 'fa-moon',
            'general': 'fa-apple-alt'
        };
        return iconos[tipo] || 'fa-apple-alt';
    }

    function getColorTipo(tipo) {
        const colores = {
            'desayuno': 'orange',
            'almuerzo': 'blue',
            'cena': 'purple',
            'general': 'gray'
        };
        return colores[tipo] || 'gray';
    }

    function cerrarDietas() {
        document.getElementById('dietasModal').classList.add('hidden');
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


</script>


<!-- Modal de progreso -->
<div id="progresoModal" class="fixed inset-0 z-50 hidden">
    <div class="modal-backdrop fixed inset-0 bg-black/40" onclick="closeModal('progresoModal')"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="modal-content neumorphic w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-lg">
            <div class="p-4 sm:p-6">
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Mi Progreso
                    </h3>
                    <button onclick="closeModal('progresoModal')" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div id="progresoContent" class="space-y-4 sm:space-y-6">
                    <div id="progresoLoading" class="text-center py-6 sm:py-8">
                        <div class="animate-spin rounded-full h-10 w-10 sm:h-12 sm:w-12 border-b-2 border-purple-500 mx-auto"></div>
                        <p class="text-gray-600 mt-2 text-sm sm:text-base">Cargando tu progreso...</p>
                    </div>

                    <div id="progresoData" class="hidden space-y-4 sm:space-y-6">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
                            <div class="neumorphic-inset p-3 sm:p-4 text-center rounded-lg">
                                <div id="pesoPerdido" class="text-lg sm:text-2xl font-bold text-green-600">-0kg</div>
                                <div class="text-xs sm:text-sm text-gray-600">Peso perdido</div>
                            </div>
                            <div class="neumorphic-inset p-3 sm:p-4 text-center rounded-lg">
                                <div id="sesionesMes" class="text-lg sm:text-2xl font-bold text-blue-600">0</div>
                                <div class="text-xs sm:text-sm text-gray-600">Sesiones/mes</div>
                            </div>
                            <div class="neumorphic-inset p-3 sm:p-4 text-center rounded-lg">
                                <div id="asistencia" class="text-lg sm:text-2xl font-bold text-orange-600">0%</div>
                                <div class="text-xs sm:text-sm text-gray-600">Asistencia</div>
                            </div>
                            <div class="neumorphic-inset p-3 sm:p-4 text-center rounded-lg">
                                <div id="incrementoFuerza" class="text-lg sm:text-2xl font-bold text-purple-600">+0%</div>
                                <div class="text-xs sm:text-sm text-gray-600">Fuerza</div>
                            </div>
                        </div>

                        <div class="neumorphic-inset p-3 sm:p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-700 mb-2 sm:mb-3 text-sm sm:text-base">Evolución de peso (últimas 5 mediciones)</h4>
                            <div id="pesoChart" class="h-32 sm:h-40 flex items-end justify-between space-x-1">
                            </div>
                        </div>

                        <div>
                            <h4 class="font-semibold text-gray-700 mb-2 sm:mb-3 text-sm sm:text-base">Mis Metas</h4>
                            <div id="metasList" class="space-y-2">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="neumorphic-inset p-3 sm:p-4 rounded-lg">
                                <h4 class="font-semibold text-gray-700 mb-2 text-sm sm:text-base">Estado Inicial</h4>
                                <div id="estadoInicial" class="text-xs sm:text-sm text-gray-600">
                                </div>
                            </div>
                            <div class="neumorphic-inset p-3 sm:p-4 rounded-lg">
                                <h4 class="font-semibold text-gray-700 mb-2 text-sm sm:text-base">Estado Actual</h4>
                                <div id="estadoActual" class="text-xs sm:text-sm text-gray-600">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="progresoError" class="hidden text-center py-6 sm:py-8">
                        <div class="text-red-500 text-base sm:text-lg mb-2">❌ Error al cargar el progreso</div>
                        <p id="errorMessage" class="text-gray-600 text-sm sm:text-base"></p>
                        <button onclick="cargarProgreso()" class="btn-neu bg-blue-500 hover:bg-blue-600 text-white mt-4 text-sm sm:text-base">
                            Reintentar
                        </button>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 mt-6">
                    <button onclick="closeModal('progresoModal')" class="btn-neu bg-gray-500 hover:bg-gray-600 text-white flex-1 text-sm sm:text-base">
                        Cerrar
                    </button>
                    <button class="btn-neu bg-blue-500 hover:bg-blue-600 text-white flex-1 text-sm sm:text-base" onclick="exportarReporte()">
                        Exportar Reporte
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de objetivos -->
<div id="objetivoModal" class="fixed inset-0 z-50 hidden">
    <div class="modal-backdrop fixed inset-0 bg-black/40" onclick="closeModal('objetivoModal')"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="modal-content neumorphic w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-lg">
            <div class="p-4 sm:p-6">
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-bullseye mr-2 text-blue-500"></i>
                        Objetivos del Usuario
                    </h3>
                    <button onclick="closeModal('objetivoModal')" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div id="objetivosContent">
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-spinner fa-spin fa-2x mb-4"></i>
                        <p class="text-sm sm:text-base">Cargando objetivos...</p>
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button onclick="closeModal('objetivoModal')" class="btn-neu bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 text-sm sm:text-base">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Preferencias -->
<div id="preferenciaModal" class="fixed inset-0 z-50 hidden">
    <div class="modal-backdrop fixed inset-0 bg-black/40" onclick="closeModal('preferenciaModal')"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="modal-content neumorphic w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-lg">
            <div class="p-4 sm:p-6">
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-heart mr-2 text-red-500"></i>
                        Preferencias del Usuario
                    </h3>
                    <button onclick="closeModal('preferenciaModal')" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div id="preferenciasContent">
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-spinner fa-spin fa-2x mb-4"></i>
                        <p class="text-sm sm:text-base">Cargando preferencias...</p>
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button onclick="closeModal('preferenciaModal')" class="btn-neu bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 text-sm sm:text-base">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Mis Dietas -->
<div id="dietasModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col">
        <!-- Header -->
        <div class="flex flex-wrap justify-between items-center gap-2 p-3 sm:p-4 md:p-6 border-b">
            <h3 class="text-base sm:text-lg md:text-xl font-semibold text-gray-800">
                Mis Dietas
            </h3>
            <div class="flex justify-end p-0 sm:p-1 md:p-2 bg-gray-50 md:bg-transparent rounded">
                <button onclick="cerrarDietas()" class="px-3 sm:px-4 py-1.5 sm:py-2 bg-gray-500 text-white rounded text-xs sm:text-sm hover:bg-gray-600">
                    Cerrar
                </button>
            </div>
        </div>
        
        <!-- Content -->
        <div id="dietasContent" class="p-3 sm:p-4 md:p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
            <div class="text-center py-8">
                <i class="fas fa-spinner fa-spin text-blue-500 text-2xl"></i>
                <p class="text-gray-500 mt-2 text-sm sm:text-base">Cargando dietas...</p>
            </div>
        </div>
    </div>
</div>


@endsection

