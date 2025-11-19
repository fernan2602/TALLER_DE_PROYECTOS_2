@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header Responsive -->
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">Panel de Control</h1>
        <p class="text-gray-600 text-sm md:text-base">Gestiona y genera planes personalizados</p>
    </div>

    <!-- Botones de Acción - Responsive -->
    <div class="flex flex-col sm:flex-row gap-3 mb-6">
        <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-3">
            <button onclick="probarGemini()" 
                    class="w-full bg-green-500 hover:bg-green-600 text-white py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-md flex items-center justify-center">
                <i class="fas fa-vial mr-2 text-sm md:text-base"></i>
                <span class="text-sm md:text-base">Probar Gemini</span>
            </button>
            
            <button onclick="generarDieta()" 
                    class="w-full bg-purple-500 hover:bg-purple-600 text-white py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-md flex items-center justify-center">
                <i class="fas fa-user-check mr-2 text-sm md:text-base"></i>
                <span class="text-sm md:text-base">Mensaje Personalizado</span>
            </button>
        </div>
    </div>

    <!-- Selector de Usuario Responsive -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            <i class="fas fa-user mr-2"></i>Seleccionar Usuario
        </label>
        <select onchange="usuarioSeleccionado = this.value" 
                class="w-full md:w-64 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            <option value="1">Usuario 1</option>
            <option value="2">Usuario 2</option>
            <option value="3">Usuario 3</option>
        </select>
    </div>

    <!-- Contenedor para mostrar resultados (visible en escritorio) -->
    <div id="resultado-preferencia" class="mt-4 hidden lg:block"></div>

    <!-- Modal para mostrar resultados (siempre visible al generar contenido) -->
    <div id="modal-preferencia" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-hidden">
            <div class="p-4 md:p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg md:text-xl font-bold text-purple-600 flex items-center">
                        <i class="fas fa-user-check mr-2"></i>
                        💪 Plan Nutricional Personalizado
                    </h3>
                    <button onclick="cerrarModal()" class="text-gray-500 hover:text-gray-700 p-1 rounded-full hover:bg-gray-100 transition-colors">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
                <div class="max-h-[60vh] overflow-y-auto">
                    <div id="modal-contenido" class="pr-2">
                        <!-- Aquí se cargará el contenido dinámicamente -->
                    </div>
                </div>
                <div class="mt-4 flex justify-end pt-4 border-t border-gray-200">
                    <button onclick="cerrarModal()" 
                            class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors duration-200 flex items-center">
                        <i class="fas fa-times mr-2"></i>
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Variable global para almacenar el usuario seleccionado
    let usuarioSeleccionado = "1";

    // Funciones de prueba Gemini
    function probarGemini() {
        // Mostrar loading state
        const botones = document.querySelectorAll('button');
        botones[0].innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Probando...';
        botones[0].disabled = true;

        fetch('/dashboard/prueba-gemini')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarAlerta('✅ Gemini funciona correctamente', 'success');
                } else {
                    mostrarAlerta('❌ Error: ' + data.mensaje, 'error');
                }
            })
            .catch(error => {
                console.error('Error de conexión:', error);
                mostrarAlerta('⚠️ Error de conexión con el servidor', 'error');
            })
            .finally(() => {
                // Restaurar botón
                botones[0].innerHTML = '<i class="fas fa-vial mr-2"></i>Probar Gemini';
                botones[0].disabled = false;
            });
    }

    function generarDieta() {
        if (!usuarioSeleccionado) {
            mostrarAlerta('❌ Selecciona un usuario primero', 'error');
            return;
        }

        // Mostrar loading
        const botones = document.querySelectorAll('button');
        const botonGenerar = botones[1];
        const textoOriginal = botonGenerar.innerHTML;
        botonGenerar.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Generando...';
        botonGenerar.disabled = true;

        fetch(`/dashboard/generar-dieta/${usuarioSeleccionado}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error del servidor: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.mensaje_personalizado) {
                    // MOSTRAR SIEMPRE EN MODAL (tanto móvil como escritorio)
                    mostrarResultadoPreferencia(data);
                    mostrarAlerta('✅ Mensaje generado exitosamente', 'success');
                } else {
                    mostrarAlerta('❌ No se pudo generar el mensaje', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarAlerta('⚠️ Error de conexión con el servidor', 'error');
            })
            .finally(() => {
                // Restaurar botón
                botonGenerar.innerHTML = textoOriginal;
                botonGenerar.disabled = false;
            });
    }

    function mostrarResultadoPreferencia(data) {
        const contenidoHTML = generarContenidoHTML(data);
        
        // Siempre mostrar en modal
        mostrarModal(contenidoHTML);
        
        // También mostrar en contenedor principal (solo en escritorio)
        const divResultado = document.getElementById('resultado-preferencia');
        divResultado.innerHTML = contenidoHTML;
    }

    function generarContenidoHTML(data) {
        return `
            <div class="bg-gradient-to-r from-green-50 to-blue-50 border-l-4 border-purple-500 p-4 md:p-6 rounded-lg shadow-md">
                <div class="flex flex-col md:flex-row items-start">
                    <div class="flex-shrink-0 mb-3 md:mb-0 md:mr-4">
                        <i class="fas fa-user-circle text-purple-500 text-2xl md:text-3xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-700 text-justify italic mb-4 text-sm md:text-base leading-relaxed">"${data.mensaje_personalizado}"</p>
                        ${generarSeccionAlimentos(data)}
                        ${generarInformacionNutricional(data)}
                    </div>
                </div>
            </div>
        `;
    }

    function generarSeccionAlimentos(data) {
        const mostrarAlimentosComida = (comida, titulo, icono, color) => {
            let alimentos = data.alimentos_por_comida[comida];
            const calorias = data.calorias_por_comida[comida] || 0;
            
            if (alimentos && !Array.isArray(alimentos)) {
                alimentos = Object.values(alimentos);
            }
            
            if (!alimentos || !Array.isArray(alimentos) || alimentos.length === 0) {
                return `
                    <div class="mb-4">
                        <h5 class="font-semibold text-gray-800 mb-2 flex items-center text-sm md:text-base">
                            <i class="${icono} ${color} mr-2"></i>
                            ${titulo} - ${calorias} cal
                        </h5>
                        <div class="bg-white rounded-lg p-3 border text-center text-gray-500 text-sm">
                            No hay alimentos disponibles
                        </div>
                    </div>
                `;
            }

            let alimentosHTML = alimentos.map(alimento => `
                <div class="flex justify-between items-center text-xs md:text-sm">
                    <span class="text-gray-700 truncate mr-2">${alimento.nombre}</span>
                    <span class="bg-${color.split('-')[1]}-100 text-${color.split('-')[1]}-800 text-xs px-2 py-1 rounded whitespace-nowrap">${alimento.calorias} cal</span>
                </div>
            `).join('');

            return `
                <div class="mb-4">
                    <h5 class="font-semibold text-gray-800 mb-2 flex items-center text-sm md:text-base">
                        <i class="${icono} ${color} mr-2"></i>
                        ${titulo} - ${calorias} cal
                    </h5>
                    <div class="bg-white rounded-lg p-3 border space-y-2">
                        ${alimentosHTML}
                    </div>
                </div>
            `;
        };

        return `
            ${mostrarAlimentosComida('desayuno', 'Desayuno', 'fas fa-sun', 'text-yellow-500')}
            ${mostrarAlimentosComida('almuerzo', 'Almuerzo', 'fas fa-utensils', 'text-orange-500')}
            ${mostrarAlimentosComida('cena', 'Cena', 'fas fa-moon', 'text-blue-500')}
            ${data.calorias_totales ? `
                <div class="mt-4 pt-4 border-t border-gray-200 bg-green-50 rounded-lg p-3">
                    <div class="flex justify-between items-center font-semibold text-sm md:text-base">
                        <span class="text-gray-800 flex items-center">
                            <i class="fas fa-fire text-red-500 mr-2"></i>
                            Total Calórico Diario:
                        </span>
                        <span class="text-green-600">${data.calorias_totales} calorías</span>
                    </div>
                </div>
            ` : ''}
        `;
    }

    function generarInformacionNutricional(data) {
        let infoHTML = '';
        
        if (data.GET || data.calorias_recomendadas) {
            infoHTML += `
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <h4 class="font-semibold text-gray-800 mb-3 flex items-center text-sm md:text-base">
                        <i class="fas fa-chart-line text-purple-500 mr-2"></i>
                        Información Nutricional Adicional
                    </h4>
            `;

            // GET y Calorías Recomendadas
            if (data.GET || data.calorias_recomendadas) {
                infoHTML += `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                        ${data.GET ? `
                            <div class="bg-blue-50 rounded-lg p-3">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-700 font-medium flex items-center">
                                        <i class="fas fa-burn text-blue-500 mr-2"></i>
                                        GET:
                                    </span>
                                    <span class="text-blue-600 font-semibold">${data.GET} cal</span>
                                </div>
                            </div>
                        ` : ''}
                        ${data.calorias_recomendadas ? `
                            <div class="bg-purple-50 rounded-lg p-3">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-700 font-medium flex items-center">
                                        <i class="fas fa-bullseye text-purple-500 mr-2"></i>
                                        Recomendadas:
                                    </span>
                                    <span class="text-purple-600 font-semibold">${data.calorias_recomendadas} cal</span>
                                </div>
                            </div>
                        ` : ''}
                    </div>
                `;
            }

            // Diferencia de Calorías y Estado
            if (data.diferencia_calorias !== undefined && data.estado_calorias) {
                const estadoColor = data.estado_calorias === 'óptimo' ? 'text-green-600' : 
                                   data.estado_calorias === 'bajo' ? 'text-yellow-600' : 'text-red-600';
                const estadoBg = data.estado_calorias === 'óptimo' ? 'bg-green-50' : 
                                data.estado_calorias === 'bajo' ? 'bg-yellow-50' : 'bg-red-50';
                
                infoHTML += `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                        <div class="${estadoBg} rounded-lg p-3">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-700 font-medium flex items-center">
                                    <i class="fas fa-balance-scale ${estadoColor} mr-2"></i>
                                    Diferencia:
                                </span>
                                <span class="${estadoColor} font-semibold">${data.diferencia_calorias} cal</span>
                            </div>
                        </div>
                        <div class="${estadoBg} rounded-lg p-3">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-700 font-medium flex items-center">
                                    <i class="fas fa-chart-bar ${estadoColor} mr-2"></i>
                                    Estado:
                                </span>
                                <span class="${estadoColor} font-semibold capitalize">${data.estado_calorias}</span>
                            </div>
                        </div>
                    </div>
                `;
            }

            // Objetivos del Usuario
            if (data.objetivos_usuario && data.objetivos_usuario.length > 0) {
                infoHTML += `
                    <div class="bg-indigo-50 rounded-lg p-3 mb-3">
                        <h5 class="font-semibold text-gray-800 mb-2 flex items-center text-sm md:text-base">
                            <i class="fas fa-tasks text-indigo-500 mr-2"></i>
                            Tus Objetivos:
                        </h5>
                        <div class="space-y-1">
                            ${data.objetivos_usuario.map(objetivo => `
                                <div class="flex items-center text-xs md:text-sm text-gray-700">
                                    <i class="fas fa-check-circle text-indigo-400 mr-2 text-xs"></i>
                                    ${objetivo}
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;
            }

            infoHTML += `</div>`;
        }
        
        return infoHTML;
    }

    function mostrarModal(contenido) {
        const modal = document.getElementById('modal-preferencia');
        const modalContenido = document.getElementById('modal-contenido');
        
        modalContenido.innerHTML = contenido;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Prevenir scroll del body cuando el modal está abierto
        document.body.style.overflow = 'hidden';
    }

    function cerrarModal() {
        const modal = document.getElementById('modal-preferencia');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        
        // Restaurar scroll del body
        document.body.style.overflow = 'auto';
    }

    function mostrarAlerta(mensaje, tipo = 'info') {
        const tipos = {
            error: 'bg-red-100 border-red-400 text-red-700',
            success: 'bg-green-100 border-green-400 text-green-700',
            info: 'bg-blue-100 border-blue-400 text-blue-700'
        };
        
        const iconos = {
            error: 'fa-exclamation-triangle',
            success: 'fa-check-circle',
            info: 'fa-info-circle'
        };
        
        const alertaHTML = `
            <div class="border-l-4 ${tipos[tipo]} p-4 mb-4 rounded-lg shadow-sm">
                <div class="flex items-start">
                    <div class="flex-shrink-0 mt-0.5">
                        <i class="fas ${iconos[tipo]}"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm">${mensaje}</p>
                    </div>
                </div>
            </div>
        `;
        
        // Insertar al inicio del contenedor de resultados
        const divResultado = document.getElementById('resultado-preferencia');
        divResultado.innerHTML = alertaHTML;
        
        // Auto-remover después de 5 segundos
        setTimeout(() => {
            if (divResultado.innerHTML === alertaHTML) {
                divResultado.innerHTML = '';
            }
        }, 5000);
    }

    // Mejora: Cerrar modal con ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            cerrarModal();
        }
    });

    // Mejora: Cerrar modal haciendo click fuera del contenido
    document.getElementById('modal-preferencia').addEventListener('click', function(event) {
        if (event.target === this) {
            cerrarModal();
        }
    });

</script>
@endsection