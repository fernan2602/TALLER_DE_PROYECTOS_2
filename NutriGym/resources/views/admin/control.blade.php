@extends('layouts.app')

@section('content')

 <!-- Contenedor de las 4 secciones -->
<div class="grid grid-cols-2 gap-4 mt-2 p-4">
    <!-- Sección 1 - Gráfica de Usuarios -->
    <div class="bg-white p-4 rounded-md">
        <h2 class="text-gray-600 text-lg font-semibold pb-4">Usuarios</h2>
        <div class="chart-container" style="position: relative; height:200px; width:200px">
            <!-- El canvas para la gráfica -->
            <canvas id="usersChart"></canvas>
        </div>
    </div>

        <!-- Sección 2 - Gráfica de Comercios -->
    <div class="bg-white p-4 rounded-md">
        <h2 class="text-gray-600 text-lg font-semibold pb-4">Comercios</h2>
        <div class="chart-container" style="position: relative; height:200px; width:200px">
            <!-- El canvas para la gráfica -->
            <canvas id="commercesChart"></canvas>
        </div>
    </div>

    <!-- Sección 3 - Tabla de Autorizaciones Pendientes -->
    <div class="bg-white p-4 rounded-md">
        <h2 class="text-gray-600 text-lg font-semibold pb-4">Autorizaciones Pendientes</h2>
        <table class="w-full table-auto">
            <thead>
                <tr class="text-sm leading-normal">
                    <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Foto</th>
                    <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Nombre</th>
                    <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light text-right">Rol</th>
                </tr>
            </thead>
            <tbody>
                <tr class="hover:bg-grey-lighter">
                    <td class="py-4 px-6 border-b border-grey-light"><img src="https://via.placeholder.com/40" alt="Foto Perfil" class="rounded-full h-10 w-10"></td>
                    <td class="py-4 px-6 border-b border-grey-light">Juan Pérez</td>
                    <td class="py-4 px-6 border-b border-grey-light text-right">Administrador</td>
                </tr>
                <!-- Añade más filas aquí como la anterior para cada autorización pendiente -->
            </tbody>
        </table>
    </div>

    <!-- Sección 4 - Tabla de Transacciones -->
    <div class="bg-white p-4 rounded-md">
        <h2 class="text-gray-600 text-lg font-semibold pb-4">Transacciones</h2>
        <table class="w-full table-auto">
            <thead>
                <tr class="text-sm leading-normal">
                    <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Nombre</th>
                    <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Fecha</th>
                    <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light text-right">Monto</th>
                </tr>
            </thead>
            <tbody>
                <tr class="hover:bg-grey-lighter">
                    <td class="py-4 px-6 border-b border-grey-light">Carlos Sánchez</td>
                    <td class="py-4 px-6 border-b border-grey-light">27/07/2023</td>
                    <td class="py-4 px-6 border-b border-grey-light text-right">$1500</td>
                </tr>
                <!-- Añade más filas aquí como la anterior para cada transacción -->
            </tbody>
        </table>
    </div>
@endsection
<script>
    function expandSidebar() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.querySelector('.ml-16');

        if (sidebar.style.width === '16rem') {
            sidebar.style.width = '4rem';
            mainContent.style.marginLeft = '4rem';
            sidebar.classList.remove('text-left', 'px-6');
            sidebar.classList.add('text-center', 'px-0');
        } else {
            sidebar.style.width = '16rem';
            mainContent.style.marginLeft = '16rem';
            sidebar.classList.add('text-left', 'px-6');
            sidebar.classList.remove('text-center', 'px-0');
        }

        const labels = sidebar.querySelectorAll('span');
        labels.forEach(label => label.classList.toggle('opacity-0'));
    }

    function highlightSidebarItem(element) {
    const buttons = document.querySelectorAll("#sidebar button");
    buttons.forEach(btn => {
        btn.classList.remove('bg-gradient-to-r', 'from-cyan-400', 'to-cyan-500', 'text-white', 'w-48', 'ml-0');
        btn.firstChild.nextSibling.classList.remove('text-white');
    });
    element.classList.add('bg-gradient-to-r', 'from-cyan-400', 'to-cyan-500', 'w-56', 'h-10','ml-0');
    element.firstChild.nextSibling.classList.add('text-white');
    }

    // Para la gráfica de Usuarios
    var ctx = document.getElementById('usersChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Usuarios Nuevos', 'Usuarios Registrados'],
            datasets: [{
                data: [50, 50],
                backgroundColor: ['cyan', 'yellow'],
            }]
        },
        options: {
            responsive: true,
        }
    });

    // Para la gráfica de Comercios
    var ctx2 = document.getElementById('commercesChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Comercios Nuevos', 'Comercios Registrados'],
            datasets: [{
                data: [60, 40],
                backgroundColor: ['cyan', 'yellow'],
            }]
        },
        options: {
            responsive: true,
        }
    });
</script>
