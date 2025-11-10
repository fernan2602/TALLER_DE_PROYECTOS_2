@extends('layouts.app')

@section('content')

<!-- En la sección de botones de acción del dashboard -->
<div class="flex space-x-3 mt-6">
    <!-- ... tus botones existentes ... -->
    
    <button onclick="probarGemini()" class="btn-neu bg-green-500 hover:bg-green-600 text-white">
        <i class="fas fa-vial mr-2"></i>
        Probar Gemini
    </button>
    
    <button onclick="consultarPreferencia()" class="btn-neu bg-purple-500 hover:bg-purple-600 text-white">
        <i class="fas fa-user-check mr-2"></i>
        Mensaje Personalizado
    </button>
</div>

<!-- Contenedor para mostrar resultados -->
<div id="resultado-preferencia" class="mt-4"></div>

<!-- Modal para mostrar resultados (opcional pero mejor) -->
<div id="modal-preferencia" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-purple-600">💪 Mensaje Personalizado</h3>
                <button onclick="cerrarModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="modal-contenido">
                <!-- Aquí se cargará el contenido dinámicamente -->
            </div>
            <div class="mt-4 flex justify-end">
                <button onclick="cerrarModal()" class="btn-neu bg-gray-500 hover:bg-gray-600 text-white px-4 py-2">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>


{{-- ... tu código HTML existente ... --}}

<script>
// Variable global para almacenar el usuario seleccionado
let usuarioSeleccionado = "1";

// ... tu función mostrarUsuario existente ...

// Funciones de prueba Gemini
function probarGemini() {
    fetch('/dashboard/prueba-gemini')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('✅ Gemini funciona:', data.respuesta);
                alert('Gemini API: ' + data.respuesta);
            } else {
                console.error('❌ Error Gemini:', data.mensaje);
                alert('Error: ' + data.mensaje);
            }
        })
        .catch(error => {
            console.error('Error de conexión:', error);
            alert('Error de conexión con el servidor');
        });
}

function consultarPreferencia() {
    if (!usuarioSeleccionado) {
        mostrarAlerta('❌ Selecciona un usuario primero', 'error');
        return;
    }

    // Mostrar loading
    const boton = event.target;
    const textoOriginal = boton.innerHTML;
    boton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Generando mensaje...';
    boton.disabled = true;

    fetch(`/dashboard/buscar-preferencia/${usuarioSeleccionado}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error del servidor: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            // ✅ CAMBIO AQUÍ - Ya no verifica data.success
            if (data.mensaje_personalizado) {
                mostrarResultadoPreferencia(data);
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
            boton.innerHTML = textoOriginal;
            boton.disabled = false;
        });
}

function mostrarResultadoPreferencia(data) {
    const divResultado = document.getElementById('resultado-preferencia');
    
    let contenidoHTML = `
        <div class="bg-gradient-to-r from-green-50 to-blue-50 border-l-4 border-purple-500 p-4 rounded shadow-md">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-user-circle text-purple-500 text-2xl mr-3"></i>
                </div>
                <div class="flex-1">
                    <p class="text-gray-700 text-justify italic mb-4">"${data.mensaje_personalizado}"</p>
    `;

    // ✅ FUNCIÓN MEJORADA PARA MANEJAR OBJETOS Y ARRAYS
    const mostrarAlimentosComida = (comida, titulo, icono, color) => {
        let alimentos = data.alimentos_por_comida[comida];
        const calorias = data.calorias_por_comida[comida] || 0;
        
        // Convertir objeto a array si es necesario
        if (alimentos && !Array.isArray(alimentos)) {
            alimentos = Object.values(alimentos);
        }
        
        if (!alimentos || !Array.isArray(alimentos) || alimentos.length === 0) {
            return `
                <div class="mb-4">
                    <h5 class="font-semibold text-gray-800 mb-2 flex items-center">
                        <i class="${icono} ${color} mr-2"></i>
                        ${titulo} - ${calorias} cal
                    </h5>
                    <div class="bg-white rounded-lg p-3 border text-center text-gray-500">
                        No hay alimentos disponibles
                    </div>
                </div>
            `;
        }

        let html = `
            <div class="mb-4">
                <h5 class="font-semibold text-gray-800 mb-2 flex items-center">
                    <i class="${icono} ${color} mr-2"></i>
                    ${titulo} - ${calorias} cal
                </h5>
                <div class="bg-white rounded-lg p-3 border space-y-2">
        `;

        alimentos.forEach(alimento => {
            html += `
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-700">${alimento.nombre}</span>
                        <span class="bg-${color.split('-')[1]}-100 text-${color.split('-')[1]}-800 text-xs px-2 py-1 rounded">${alimento.calorias} cal</span>
                    </div>
            `;
        });

        html += `
                </div>
            </div>
        `;

        return html;
    };

    // Mostrar cada comida
    contenidoHTML += mostrarAlimentosComida('desayuno', 'Desayuno', 'fas fa-sun', 'text-yellow-500');
    contenidoHTML += mostrarAlimentosComida('almuerzo', 'Almuerzo', 'fas fa-utensils', 'text-orange-500');
    contenidoHTML += mostrarAlimentosComida('cena', 'Cena', 'fas fa-moon', 'text-blue-500');

    // Calorías totales
    if (data.calorias_totales) {
        contenidoHTML += `
                    <div class="mt-4 pt-4 border-t border-gray-200 bg-green-50 rounded-lg p-3">
                        <div class="flex justify-between items-center font-semibold">
                            <span class="text-gray-800 flex items-center">
                                <i class="fas fa-fire text-red-500 mr-2"></i>
                                Total Calórico Diario:
                            </span>
                            <span class="text-green-600 text-lg">${data.calorias_totales} calorías</span>
                        </div>
                    </div>
        `;
    }

    contenidoHTML += `
                </div>
            </div>
        </div>
    `;

    divResultado.innerHTML = contenidoHTML;
    
    if (typeof mostrarModal === 'function') {
        mostrarModal(contenidoHTML);
    }
}

function mostrarModal(contenido) {
    const modal = document.getElementById('modal-preferencia');
    const modalContenido = document.getElementById('modal-contenido');
    
    modalContenido.innerHTML = contenido;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function cerrarModal() {
    const modal = document.getElementById('modal-preferencia');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function mostrarAlerta(mensaje, tipo = 'info') {
    const tipos = {
        error: 'bg-red-100 border-red-400 text-red-700',
        success: 'bg-green-100 border-green-400 text-green-700',
        info: 'bg-blue-100 border-blue-400 text-blue-700'
    };
    
    const alertaHTML = `
        <div class="border-l-4 ${tipos[tipo]} p-4 mb-4 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas ${tipo === 'error' ? 'fa-exclamation-triangle' : tipo === 'success' ? 'fa-check-circle' : 'fa-info-circle'}"></i>
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

// ... el resto de tus funciones JavaScript existentes ...
</script>