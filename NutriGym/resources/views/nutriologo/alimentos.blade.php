@extends('layouts.app')

@section('content')

<!-- component -->
<div class="overflow-hidden rounded-lg border border-gray-200 shadow-md m-5">
  <table class="w-full border-collapse bg-white text-left text-sm text-gray-500">
    <thead class="bg-gray-50">
      <tr>  
        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Nombre</th>
        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Categoria</th>
        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Proteina</th>
        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Carbohidratos</th>
        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Grasas</th>
        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Fibra</th>
        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Azucar</th>
        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Calorias</th>
        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Sodio</th>
        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Potasio</th>
        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Calcio</th>
        <th scope="col" class="px-6 py-4 font-medium text-gray-900">hierro</th>
        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Unidad</th>
        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Acciones</th>
      </tr>
    </thead>
    <!-- En tu archivo Blade -->
    <tbody class="divide-y divide-gray-100 border-t border-gray-100">
        @forelse($alimentos as $alimento)
        <tr class="hover:bg-gray-50" data-id="{{$alimento->id}}">
            <td class="px-6 py-4 editable"  data-field=font-medium text-gray-900">{{ $alimento->nombre }}</td>
            <td class="px-6 py-4 editable"  data-field="categoria">{{ $alimento->categoria ?? '-' }}</td>
            <td class="px-6 py-4 editable"  data-field="proteina">{{ number_format($alimento->proteina, 2) }}</td>
            <td class="px-6 py-4 editable"  data-field="carbohidratos">{{ number_format($alimento->carbohidratos, 2) }}</td>
            <td class="px-6 py-4 editable"  data-field="grasas">{{ number_format($alimento->grasas, 2) }}</td>
            <td class="px-6 py-4 editable"  data-field="fibra">{{ number_format($alimento->fibra, 2) }}</td>
            <td class="px-6 py-4 editable"  data-field="azucar">{{ number_format($alimento->azucar, 2) }}</td>
            <td class="px-6 py-4 editable font-semibold"  data-field= "calorias">{{ number_format($alimento->calorias, 2) }}</td>
            <td class="px-6 py-4 editable"  data-field="sodio">{{ number_format($alimento->sodio, 2) }}</td>
            <td class="px-6 py-4 editable"  data-field="potasio">{{ number_format($alimento->potasio, 2) }}</td>
            <td class="px-6 py-4 editable"  data-field="calcio">{{ number_format($alimento->calcio, 2) }}</td>
            <td class="px-6 py-4 editable"  data-field="hierro">{{ number_format($alimento->hierro, 2) }}</td>
            <td class="px-6 py-4 editable"  data-field="unidad_medida">{{ $alimento->unidad_medida ?? '-' }}</td>
            <td class="px-6 py-4 ">
                <div class="flex justify-center gap-2">
                    <button onclick="guardarCambios()" 
                            class="btn-neu bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-lg transition-colors"
                            title="Editar alimento">
                        <i class="fas fa-edit mr-1"></i>
                        Editar
                    </button>
                    <button onclick="cancelarEdicion()" 
                            class="btn-neu bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition-colors"
                            title="Eliminar alimento">
                        <i class="fas fa-trash mr-1"></i>
                        Cancelar edicion
                    </button>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="14" class="px-6 py-4 text-center text-gray-500">
                No hay alimentos registrados
            </td>
        </tr>
        @endforelse
    </tbody>
  </table>
</div>

<script>
    // Variables globales
let editingCell = null;
let originalValue = '';
let currentRowId = null;

// Hacer celdas editables con doble click
document.addEventListener('DOMContentLoaded', function() {
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
});

// Iniciar edición de una celda
function iniciarEdicion(cell) {
    // Si ya hay una celda en edición, cancelarla primero
    if (editingCell) {
        cancelarEdicion();
    }
    
    // Verificar que la celda existe
    if (!cell) {
        console.error('Celda no encontrada');
        return;
    }
    
    editingCell = cell;
    originalValue = cell.textContent.trim();
    currentRowId = cell.closest('tr')?.dataset?.id;
    
    console.log('Iniciando edición para celda:', cell, 'Valor original:', originalValue);
    
    // Crear input
    const input = document.createElement('input');
    input.type = 'text';
    input.value = originalValue;
    input.className = 'w-full px-2 py-1 border border-blue-500 rounded focus:outline-none focus:ring-2 focus:ring-blue-300';
    
    // Reemplazar contenido de la celda con el input
    cell.innerHTML = '';
    cell.appendChild(input);
    cell.classList.add('bg-blue-50', 'border', 'border-blue-300');
    
    // Enfocar y seleccionar todo el texto
    input.focus();
    input.select();
    
    // Mostrar botones de guardar/cancelar si existen
    if (currentRowId) {
        const row = document.querySelector(`tr[data-id="${currentRowId}"]`);
        if (row) {
            const saveBtn = row.querySelector('.save-btn');
            const cancelBtn = row.querySelector('.cancel-btn');
            
            if (saveBtn) saveBtn.classList.remove('hidden');
            if (cancelBtn) cancelBtn.classList.remove('hidden');
        }
    }
    
    // Manejar teclas
    input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            guardarCambios();
        } else if (e.key === 'Escape') {
            cancelarEdicion();
        }
    });
}

// Guardar cambios
function guardarCambios() {
    if (!editingCell) {
        console.error('No hay celda en edición');
        return;
    }
    
    const input = editingCell.querySelector('input');
    if (!input) {
        console.error('Input no encontrado');
        return;
    }
    
    const nuevoValor = input.value.trim();
    const field = editingCell.dataset.field;
    
    console.log('Guardando cambios:', { field, nuevoValor, currentRowId });
    
    // Validar que tenemos un ID de fila
    if (!currentRowId) {
        alert('Error: No se pudo identificar el alimento');
        cancelarEdicion();
        return;
    }
    
    // Validar números para campos numéricos
    const camposNumericos = ['proteina', 'carbohidratos', 'grasas', 'fibra', 'azucar', 'calorias', 'sodio', 'potasio', 'calcio', 'hierro'];
    if (camposNumericos.includes(field)) {
        if (isNaN(nuevoValor) || nuevoValor === '') {
            alert('Por favor ingresa un valor numérico válido');
            return;
        }
    }
    
    // Enviar cambios al servidor
    fetch(`/nutriologo/alimentos/${currentRowId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
            // Actualizar celda visualmente
            editingCell.textContent = camposNumericos.includes(field) ? 
                parseFloat(nuevoValor).toFixed(2) : nuevoValor;
            editingCell.classList.remove('bg-blue-50', 'border', 'border-blue-300');
            editingCell.classList.add('bg-green-50');
            
            setTimeout(() => {
                if (editingCell) {
                    editingCell.classList.remove('bg-green-50');
                }
            }, 1000);
            
        } else {
            alert('Error al guardar: ' + (data.message || 'Error desconocido'));
            if (editingCell) {
                editingCell.textContent = originalValue;
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error de conexión: ' + error.message);
        if (editingCell) {
            editingCell.textContent = originalValue;
        }
    })
    .finally(() => {
        finalizarEdicion();
    });
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
    if (currentRowId) {
        const row = document.querySelector(`tr[data-id="${currentRowId}"]`);
        if (row) {
            const saveBtn = row.querySelector('.save-btn');
            const cancelBtn = row.querySelector('.cancel-btn');
            
            if (saveBtn) saveBtn.classList.add('hidden');
            if (cancelBtn) cancelBtn.classList.add('hidden');
        }
    }
    
    editingCell = null;
    originalValue = '';
    currentRowId = null;
}
</script>


@endSection









