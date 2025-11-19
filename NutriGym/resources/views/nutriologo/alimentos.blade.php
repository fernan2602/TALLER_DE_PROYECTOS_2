@extends('layouts.app')

@section('content')

<!-- Registro de alimentos -->
<div class="w-full md:w-full px-3 mb-6">
    <label class="block uppercase tracking-wide text-gray-700 text-sm font-bold mb-2" htmlFor="category_name">
        Buscar cliente
    </label>
    <input 
        class="input-neu w-full" 
        type="text" 
        name="name" 
        placeholder="Nombre del del alimento" 
        required 
    />
</div>
<!-- Boton de agregar alimento  --> 
<div class="w-full md:w-full px-3 mb-6">
    <button onclick="abrirModalAlimento()" class="btn-neu w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200">
        Agregar Alimento
    </button>
</div>

<!-- component -->
<div class="overflow-hidden rounded-lg border border-gray-200 shadow-md m-5">
  <div class="overflow-x-auto">
    <table class="min-w-full border-collapse bg-white text-left text-sm text-black">
      <thead class="bg-gray-50">
        <tr>  
          <th scope="col" class="px-3 py-3 font-medium text-gray-900 text-xs sm:px-4 sm:py-3 sm:text-sm">Nombre</th>
          <th scope="col" class="px-3 py-3 font-medium text-gray-900 text-xs sm:px-4 sm:py-3 sm:text-sm">Categoria</th>
          <th scope="col" class="px-3 py-3 font-medium text-gray-900 text-xs sm:px-4 sm:py-3 sm:text-sm">Proteina</th>
          <th scope="col" class="px-3 py-3 font-medium text-gray-900 text-xs sm:px-4 sm:py-3 sm:text-sm">Carbohidratos</th>
          <th scope="col" class="px-3 py-3 font-medium text-gray-900 text-xs sm:px-4 sm:py-3 sm:text-sm">Grasas</th>
          <th scope="col" class="px-3 py-3 font-medium text-gray-900 text-xs sm:px-4 sm:py-3 sm:text-sm">Fibra</th>
          <th scope="col" class="px-3 py-3 font-medium text-gray-900 text-xs sm:px-4 sm:py-3 sm:text-sm">Azucar</th>
          <th scope="col" class="px-3 py-3 font-medium text-gray-900 text-xs sm:px-4 sm:py-3 sm:text-sm">Calorias</th>
          <th scope="col" class="px-3 py-3 font-medium text-gray-900 text-xs sm:px-4 sm:py-3 sm:text-sm">Sodio</th>
          <th scope="col" class="px-3 py-3 font-medium text-gray-900 text-xs sm:px-4 sm:py-3 sm:text-sm">Potasio</th>
          <th scope="col" class="px-3 py-3 font-medium text-gray-900 text-xs sm:px-4 sm:py-3 sm:text-sm">Calcio</th>
          <th scope="col" class="px-3 py-3 font-medium text-gray-900 text-xs sm:px-4 sm:py-3 sm:text-sm">Hierro</th>
          <th scope="col" class="px-3 py-3 font-medium text-gray-900 text-xs sm:px-4 sm:py-3 sm:text-sm">Unidad</th>
          <th scope="col" class="px-3 py-3 font-medium text-gray-900 text-xs sm:px-4 sm:py-3 sm:text-sm">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($alimentos as $alimento)
        <tr data-id="{{ $alimento->id }}" class="bg-white border-b dark:bg-gray-50 dark:border-gray-700">
          <td class="px-3 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-black text-xs sm:px-4 sm:py-3 sm:text-sm">
            {{ $alimento->nombre }}
          </td>
          <td class="px-3 py-3 editable text-xs sm:px-4 sm:py-3 sm:text-sm" data-field="categoria">
            {{ $alimento->categoria }}
          </td>
          <td class="px-3 py-3 editable text-xs sm:px-4 sm:py-3 sm:text-sm" data-field="proteina">
            {{ $alimento->proteina }}
          </td>
          <td class="px-3 py-3 editable text-xs sm:px-4 sm:py-3 sm:text-sm" data-field="carbohidratos">
            {{ $alimento->carbohidratos }}
          </td>
          <td class="px-3 py-3 editable text-xs sm:px-4 sm:py-3 sm:text-sm" data-field="grasas">
            {{ $alimento->grasas }}
          </td>
          <td class="px-3 py-3 editable text-xs sm:px-4 sm:py-3 sm:text-sm" data-field="fibra">
            {{ $alimento->fibra }}
          </td>
          <td class="px-3 py-3 editable text-xs sm:px-4 sm:py-3 sm:text-sm" data-field="azucar">
            {{ $alimento->azucar }}
          </td>
          <td class="px-3 py-3 editable text-xs sm:px-4 sm:py-3 sm:text-sm" data-field="calorias">
            {{ $alimento->calorias }}
          </td>
          <td class="px-3 py-3 editable text-xs sm:px-4 sm:py-3 sm:text-sm" data-field="sodio">
            {{ $alimento->sodio }}
          </td>
          <td class="px-3 py-3 editable text-xs sm:px-4 sm:py-3 sm:text-sm" data-field="potasio">
            {{ $alimento->potasio }}
          </td>
          <td class="px-3 py-3 editable text-xs sm:px-4 sm:py-3 sm:text-sm" data-field="calcio">
            {{ $alimento->calcio }}
          </td>
          <td class="px-3 py-3 editable text-xs sm:px-4 sm:py-3 sm:text-sm" data-field="hierro">
            {{ $alimento->hierro }}
          </td>   
          <td class="px-3 py-3 editable text-xs sm:px-4 sm:py-3 sm:text-sm" data-field="unidad_medida">
            {{ $alimento->unidad_medida }}
          </td>
          
          <!-- Columna de acciones -->
          <td class="px-3 py-3 text-xs sm:px-4 sm:py-3 sm:text-sm">
            <button class="save-btn hidden text-green-600 hover:text-green-900 text-xs sm:text-sm" onclick="guardarCambiosDesdeBoton()">
              💾
            </button>
            <button class="cancel-btn hidden text-red-600 hover:text-red-900 text-xs sm:text-sm" 
                onclick="eliminarAlimento()"
                ontouchend="eliminarAlimento()">
                🗑️ 
            </button>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>


<div id="modalAlimento" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-md bg-white">
        <!-- Header del modal -->
        <div class="flex justify-between items-center pb-3 border-b">
            <h3 class="text-2xl font-bold text-gray-800">Registrar Nuevo Alimento</h3>
            <button onclick="cerrarModalAlimento()" class="text-gray-500 hover:text-gray-700 text-3xl">
                &times;
            </button>
        </div>

        <!-- Formulario -->
        <form id="formAlimento" class="mt-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <!-- Columna 1 -->
    <div class="space-y-4">
        <!-- Nombre -->
        <label class="block">
            <span class="block text-sm font-medium text-gray-700 mb-1">Nombre del Alimento *</span>
            <input type="text" name="nombre" required 
                   autocomplete="name"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                   placeholder="Ej: Manzana, Pollo, Arroz...">
        </label>

        <!-- Categoría -->
        <label class="block">
            <span class="block text-sm font-medium text-gray-700 mb-1">Categoría *</span>
            <select name="categoria" required 
                    autocomplete="off"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Seleccionar categoría</option>
                <option value="Fruta">Fruta</option>
                <option value="Verdura">Verdura</option>
                <option value="Proteína">Proteína</option>
                <option value="Carbohidrato">Carbohidrato</option>
                <option value="Lácteo">Lácteo</option>
                <option value="Grasa">Grasa</option>
                <option value="Bebida">Bebida</option>
                <option value="Otro">Otro</option>
            </select>
        </label>

        <!-- Unidad de Medida -->
        <label class="block">
            <span class="block text-sm font-medium text-gray-700 mb-1">Unidad de Medida *</span>
            <select name="unidad_medida" required 
                    autocomplete="off"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Seleccionar unidad</option>
                <option value="g">Gramos (g)</option>
            </select>
        </label>

        <!-- Calorías -->
        <label class="block">
            <span class="block text-sm font-medium text-gray-700 mb-1">Calorías (g)</span>
            <input type="number" step="0.01" name="calorias" 
                   autocomplete="off"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                   placeholder="Ej: 52.0">
        </label>

        <!-- Proteína -->
        <label class="block">
            <span class="block text-sm font-medium text-gray-700 mb-1">Proteína (g)</span>
            <input type="number" step="0.01" name="proteina" 
                   autocomplete="off"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                   placeholder="Ej: 0.3">
        </label>

        <!-- Carbohidratos -->
        <label class="block">
            <span class="block text-sm font-medium text-gray-700 mb-1">Carbohidratos (g)</span>
            <input type="number" step="0.01" name="carbohidratos" 
                   autocomplete="off"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                   placeholder="Ej: 14.0">
        </label>

        <!-- Grasas -->
        <label class="block">
            <span class="block text-sm font-medium text-gray-700 mb-1">Grasas (g)</span>
            <input type="number" step="0.01" name="grasas" 
                   autocomplete="off"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                   placeholder="Ej: 0.2">
        </label>
    </div>

    <!-- Columna 2 -->
    <div class="space-y-4">
        <!-- Fibra -->
        <label class="block">
            <span class="block text-sm font-medium text-gray-700 mb-1">Fibra (g)</span>
            <input type="number" step="0.01" name="fibra" 
                   autocomplete="off"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                   placeholder="Ej: 2.4">
        </label>

        <!-- Azúcar -->
        <label class="block">
            <span class="block text-sm font-medium text-gray-700 mb-1">Azúcar (g)</span>
            <input type="number" step="0.01" name="azucar" 
                   autocomplete="off"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                   placeholder="Ej: 10.0">
        </label>

        <!-- Sodio -->
        <label class="block">
            <span class="block text-sm font-medium text-gray-700 mb-1">Sodio (g)</span>
            <input type="number" step="0.01" name="sodio" 
                   autocomplete="off"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                   placeholder="Ej: 1.0">
        </label>

        <!-- Potasio -->
        <label class="block">
            <span class="block text-sm font-medium text-gray-700 mb-1">Potasio (g)</span>
            <input type="number" step="0.01" name="potasio" 
                   autocomplete="off"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                   placeholder="Ej: 107.0">
        </label>

        <!-- Calcio -->
        <label class="block">
            <span class="block text-sm font-medium text-gray-700 mb-1">Calcio (g)</span>
            <input type="number" step="0.01" name="calcio" 
                   autocomplete="off"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                   placeholder="Ej: 6.0">
        </label>

        <!-- Hierro -->
        <label class="block">
            <span class="block text-sm font-medium text-gray-700 mb-1">Hierro (g)</span>
            <input type="number" step="0.01" name="hierro" 
                   autocomplete="off"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                   placeholder="Ej: 0.1">
        </label>
    </div>
</div>

            <!-- Nota -->
            <div class="mt-4 p-3 bg-blue-50 rounded-md">
                <p class="text-sm text-blue-700">
                    <strong>Nota:</strong> Todos los valores nutricionales son por 100 gramos o 100 ml, excepto cuando se especifica otra unidad.
                </p>
            </div>

            <!-- Botones de acción -->
            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">
                <button type="button" onclick="cerrarModalAlimento()" 
                        class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-200">
                    Cancelar
                </button>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-200">
                    Guardar Alimento
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Variables globales
    let editingCell = null;
    let originalValue = '';
    let currentRowId = null;

    // Hacer celdas editables con doble click
    document.addEventListener('DOMContentLoaded', function() {
        let modalAlimento = document.getElementById('modalAlimento');
        let formAlimento = document.getElementById('formAlimento');

        const editableCells = document.querySelectorAll('.editable');
        
        editableCells.forEach(cell => {
            cell.addEventListener('dblclick', function(e) {
                e.stopPropagation();
                iniciarEdicion(this);
            });
        });

        // Click fuera de la celda para cancelar edición
        document.addEventListener('click', function(e) {
            if (editingCell && !editingCell.contains(e.target)) {
                cancelarEdicion();
            }
        });

            // Event listeners para celdas editables
    
            document.querySelectorAll('.editable').forEach(cell => {
        // Para dispositivos táctiles
        cell.addEventListener('touchend', function(e) {
            e.preventDefault();
            if (!editingCell) { // Solo si no hay edición en curso
                iniciarEdicion(this);
            }
        });
        
        // Para desktop
        cell.addEventListener('click', function(e) {
            if (!editingCell) { // Solo si no hay edición en curso
                iniciarEdicion(this);
            }
        });
    });

    // Event listeners para botones (móvil y desktop)
    document.querySelectorAll('.save-btn').forEach(btn => {
        btn.addEventListener('touchend', function(e) {
            e.preventDefault();
            guardarCambiosDesdeBoton();
        });
    });

    document.querySelectorAll('.cancel-btn').forEach(btn => {
        btn.addEventListener('touchend', function(e) {
            e.preventDefault();
            eliminarAlimento(); // Cambiar a función de eliminar
        });
    });

    });


    // Iniciar edición de una celda
    function iniciarEdicion(cell) {
        console.log('Iniciando edición para celda:', cell);
        
        // Obtener la fila padre
        const row = cell.closest('tr');
        if (!row) {
            console.error('No se pudo encontrar la fila padre');
            return;
        }
        
        // Obtener el ID de la fila (ASEGÚRATE de que tus tr tienen data-id)
        currentRowId = row.getAttribute('data-id');
        console.log('Current Row ID:', currentRowId);
        
        if (!currentRowId) {
            console.error('No se encontró data-id en la fila');
            alert('Error: No se puede identificar el alimento.');
            return;
        }
        
        originalValue = cell.textContent.trim();
        editingCell = cell;
        
        // Crear input
        const input = document.createElement('input');
        input.type = 'text';
        input.value = originalValue;
        input.className = 'w-full px-2 py-1 border border-blue-500 rounded focus:outline-none focus:ring-2 focus:ring-blue-300';
        
        // Limpiar celda y agregar input
        cell.textContent = '';
        cell.appendChild(input);
        input.focus();
        
        // MOSTRAR BOTONES - aquí llamas a la función
        mostrarBotonesEdicion(row);
        
        // Event listener para Enter y Escape
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                const field = cell.getAttribute('data-field');
                guardarCambios(field, input.value, currentRowId);
            } else if (e.key === 'Escape') {
                cancelarEdicion();
            }
        });
    }

    // Enviar cambios al servidor
    function guardarCambios() {
        // Obtener el field desde la celda en edición
        const field = editingCell.getAttribute('data-field');
        const input = editingCell.querySelector('input');
        const nuevoValor = input ? input.value : '';
        
        console.log('Guardando cambios:', {
            field: field,
            nuevoValor: nuevoValor,
            currentRowId: currentRowId
        });
        
        if (!currentRowId) {
            alert('Error: No se puede identificar el alimento.');
            cancelarEdicion();
            return;
        }
        
        // Obtener CSRF token
        const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
        if (!csrfTokenMeta) {
            alert('Error de seguridad. Recarga la página.');
            cancelarEdicion();
            return;
        }
        
        const csrfToken = csrfTokenMeta.getAttribute('content');
        
        // Hacer la petición
        fetch(`/alimentos/${currentRowId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                field: field,
                value: nuevoValor
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error del servidor: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Éxito
                editingCell.textContent = nuevoValor;
                editingCell.classList.remove('bg-blue-50', 'border', 'border-blue-300');
                editingCell.classList.add('bg-green-50');
                
                setTimeout(() => {
                    editingCell.classList.remove('bg-gray-50');
                }, 1000);
            } else {
                throw new Error(data.message || 'Error del servidor');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al guardar: ' + error.message);
            editingCell.textContent = originalValue;
        })
        .finally(() => {
            finalizarEdicion();
        });
    }

    // Mostrar botones
    function mostrarBotonesEdicion(row) {
        // Ocultar botones en todas las filas
        document.querySelectorAll('.save-btn, .cancel-btn').forEach(btn => {
            btn.classList.add('hidden');
        });
        
        // Mostrar botones en la fila actual
        const saveBtn = row.querySelector('.save-btn');
        const cancelBtn = row.querySelector('.cancel-btn');
        
        if (saveBtn) saveBtn.classList.remove('hidden');
        if (cancelBtn) cancelBtn.classList.remove('hidden');
    }

    // Cancelar edición
    function cancelarEdicion() {
        if (editingCell) {
            console.log('Cancelando edición');
            editingCell.textContent = originalValue;
            editingCell.classList.remove('bg-blue-50', 'border', 'border-blue-300');
            finalizarEdicion();
        }
    }

    // Finalizar edición (ocultar botones)
    function finalizarEdicion() {
        console.log('Finalizando edición');
        
        // Ocultar todos los botones de guardar/cancelar
        const saveButtons = document.querySelectorAll('.save-btn');
        const cancelButtons = document.querySelectorAll('.cancel-btn');
        
        saveButtons.forEach(btn => btn.classList.add('hidden'));
        cancelButtons.forEach(btn => btn.classList.add('hidden'));
        
        // Resetear variables globales
        editingCell = null;
        originalValue = '';
        currentRowId = null;
    }

    // Cambios desde boton 
    function guardarCambiosDesdeBoton() {
        if (!editingCell || !currentRowId) {
            console.error('No hay celda en edición o ID no definido');
            return;
        }
        
        const field = editingCell.getAttribute('data-field');
        const input = editingCell.querySelector('input');
        const nuevoValor = input ? input.value : '';
        
        guardarCambios(field, nuevoValor, currentRowId);
    }

    //
    // Abrir modal
    function abrirModalAlimento() {
        modalAlimento.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevenir scroll del body
    }

    // Cerrar modal
    function cerrarModalAlimento() {
        modalAlimento.classList.add('hidden');
        document.body.style.overflow = 'auto'; // Restaurar scroll
        formAlimento.reset(); // Limpiar formulario
    }


    // Cerrar modal al hacer clic fuera
    modalAlimento.addEventListener('click', function(e) {
        if (e.target === modalAlimento) {
            cerrarModalAlimento();
        }
    });

    // Manejar envío del formulario
    formAlimento.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Recoger datos del formulario
        const formData = new FormData(formAlimento);
        const datos = Object.fromEntries(formData.entries());
        
        // Validar campos requeridos
        if (!datos.nombre || !datos.categoria || !datos.unidad_medida) {
            alert('Por favor, complete los campos requeridos (*)');
            return;
        }
        
        // Convertir valores numéricos
        const camposNumericos = ['calorias', 'proteina', 'carbohidratos', 'grasas', 'fibra', 'azucar', 'sodio', 'potasio', 'calcio', 'hierro', 'colesterol'];
        camposNumericos.forEach(campo => {
            if (datos[campo]) {
                datos[campo] = parseFloat(datos[campo]);
            } else {
                datos[campo] = 0;
            }
        });
        
        // Enviar datos al servidor
        guardarAlimento(datos);
    });

    // Función para guardar el alimento
    function guardarAlimento(datos) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch('/alimentos', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(datos)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error del servidor: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                cerrarModalAlimento();
                // Recargar la tabla o agregar el nuevo alimento dinámicamente
                location.reload(); // Opción simple - recargar página
            } else {
                throw new Error(data.message || 'Error al guardar');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al guardar el alimento: ' + error.message);
        });
    }

    // Tecla Escape para cerrar modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modalAlimento.classList.contains('hidden')) {
            cerrarModalAlimento();
        }
    });


    // Función para eliminar alimento
    function eliminarAlimento() {
        if (!currentRowId) {
            console.error('No hay alimento seleccionado para eliminar');
            return;
        }

        if (!confirm('¿Estás seguro de que quieres eliminar este alimento?')) {
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch(`/alimentos/${currentRowId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error del servidor: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Alimento eliminado correctamente');
                location.reload(); // Recargar para ver cambios
            } else {
                throw new Error(data.message || 'Error al eliminar');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar: ' + error.message);
        })
        .finally(() => {
            finalizarEdicion();
        });
    }



</script>


@endSection









