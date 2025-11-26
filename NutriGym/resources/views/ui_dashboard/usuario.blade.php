@extends('layouts.app')

@section('content')
<!-- Content -->
<div class="p-4 sm:p-6 max-w-6xl mx-auto w-full">
    <!-- Grid de Estadísticas -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6">
        <!-- Card Dietas -->
        <div class="bg-white rounded-lg border border-gray-100 p-4 sm:p-6 shadow-md shadow-black/5 hover:shadow-lg transition-shadow duration-200 w-full">
            <div class="flex justify-between items-start mb-4">
                <div class="flex-1">
                    <div class="text-2xl sm:text-3xl font-semibold text-gray-800 mb-1 break-words" id="contadorObjetivos">
                        {{ $totalMenusUsuario }}
                    </div>
                    <div class="text-xs sm:text-sm font-medium text-gray-500">Dietas</div>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                <a href="javascript:void(0)" 
                    onclick="generarDieta()"
                    class="text-[#f84525] font-medium text-sm hover:text-red-800 transition-colors inline-flex items-center">
                    <i class="fas fa-eye mr-1"></i> Generar Dieta
                </a>
                <a href="javascript:void(0)" 
                    onclick="verDietas()"
                    class="text-[#20ce37] font-medium text-sm hover:text-red-800 transition-colors inline-flex items-center">
                    <i class="fas fa-eye mr-1"></i> Mis dietas
                </a>
            </div>
        </div>

        <!-- Card Objetivos -->
        <div class="bg-white rounded-md border border-gray-100 p-4 sm:p-6 shadow-md shadow-black/5 w-full">
            <div class="flex justify-between items-start mb-4">
                <div class="flex-1">
                    <div class="text-2xl sm:text-3xl font-semibold text-gray-800 mb-1 break-words" id="contadorObjetivos">
                        {{ $totalObjetivosUsuario ?? 0 }}
                    </div>
                    <div class="text-xs sm:text-sm font-medium text-gray-500">Objetivos</div>
                </div>
                <div class="dropdown">
                    <button type="button" class="dropdown-toggle text-gray-400 hover:text-gray-600">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                <a href="javascript:void(0)" 
                    onclick="abrirModalVerObjetivos()" 
                    class="text-[#f84525] font-medium text-sm hover:text-red-800 transition-colors inline-flex items-center">
                    <i class="fas fa-eye mr-1"></i> Ver
                </a>
                <a href="javascript:void(0)" 
                    onclick="abrirModalAsignarObjetivos()" 
                    class="text-[#25f87d] font-medium text-sm hover:text-green-800 transition-colors inline-flex items-center">
                    <i class="fas fa-plus mr-1"></i> Asignar
                </a>
            </div>
        </div>

        <!-- Card Preferencias -->
        <div class="bg-white rounded-lg border border-gray-100 p-4 sm:p-6 shadow-md shadow-black/5 hover:shadow-lg transition-shadow duration-200 w-full">
            <div class="flex justify-between items-start mb-4">
                <div class="flex-1">
                    <div class="text-2xl sm:text-3xl font-semibold text-gray-800 mb-1 break-words" id="contadorPreferencias">
                        {{ $totalPreferenciasUsuario ?? 0 }}
                    </div>
                    <div class="text-xs sm:text-sm font-medium text-gray-500">Preferencias</div>
                </div>
                <div class="dropdown">
                    <button type="button" class="dropdown-toggle text-gray-400 hover:text-gray-600 transition-colors p-1 rounded hover:bg-gray-100">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                <a href="javascript:void(0)" 
                    onclick="abrirModalVerPreferencias()" 
                    class="text-[#f84525] font-medium text-sm hover:text-red-800 transition-colors inline-flex items-center">
                    <i class="fas fa-eye mr-1"></i> Ver
                </a>
                <a href="javascript:void(0)" 
                    onclick="abrirModalAsignarPreferencias()" 
                    class="text-[#25f87d] font-medium text-sm hover:text-green-800 transition-colors inline-flex items-center">
                    <i class="fas fa-plus mr-1"></i> Asignar
                </a>
            </div>
        </div>
    </div>


    <!-- Card de Progreso -->
    <div class="neumorphic p-4 md:p-6 rounded-lg w-full">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4 md:mb-6">
            <h3 class="text-lg md:text-xl font-bold text-gray-800 flex items-center">
                <svg class="w-5 h-5 md:w-6 md:h-6 mr-2 text-purple-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Mi Progreso
            </h3>
            <button onclick="exportarReporte()" class="text-xs sm:text-sm btn-neu bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md self-start md:self-auto">
                Exportar
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
                <div class="grid grid-cols-2 gap-2 md:gap-4 md:grid-cols-4">
                    <div class="neumorphic-inset p-3 md:p-4 text-center rounded-lg">
                        <div id="pesoPerdido" class="text-lg md:text-2xl font-bold text-green-600 truncate">-0kg</div>
                        <div class="text-xs md:text-sm text-gray-600">Peso perdido</div>
                    </div>

                    <div class="neumorphic-inset p-3 md:p-4 text-center rounded-lg">
                        <div id="reduccionGrasa" class="text-lg md:text-2xl font-bold text-red-600 truncate">-0%</div>
                        <div class="text-xs md:text-sm text-gray-600">Reducción grasa</div>
                    </div>
                    <div class="neumorphic-inset p-3 md:p-4 text-center rounded-lg">
                        <div id="gananciaMuscular" class="text-lg md:text-2xl font-bold text-indigo-600 truncate">+0kg</div>
                        <div class="text-xs md:text-sm text-gray-600">Masa muscular</div>
                    </div>
                    <div class="neumorphic-inset p-3 md:p-4 text-center rounded-lg">
                        <div id="mejoraIMC" class="text-lg md:text-2xl font-bold text-teal-600 truncate">+0</div>
                        <div class="text-xs md:text-sm text-gray-600">Mejora IMC</div>
                    </div>
                </div>

                <!-- Gráfico de evolución de peso -->
                <div class="neumorphic-inset p-3 md:p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-700 mb-2 md:mb-3 text-sm md:text-base">Evolución de peso</h4>
                    <div id="pesoChart" class="h-40 sm:h-48 md:h-60 flex items-end justify-between space-x-2 md:space-x-3 px-2 overflow-x-auto">
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
            </div>

            <!-- Error state -->
            <div id="progresoError" class="hidden text-center py-6 md:py-8">
                <div class="text-red-500 text-base md:text-lg mb-2">❌ Error al cargar el progreso</div>
                <p id="errorMessage" class="text-gray-600 text-sm md:text-base"></p>
                <button onclick="cargarProgreso()" class="btn-neu bg-blue-500 hover:bg-blue-600 text-white mt-4 text-sm md:text-base px-3 py-1 rounded-md">
                    Reintentar
                </button>
            </div>
        </div>
    </div>

</div>


<!-- Modal Asignar Objetivos -->
<div id="asignarObjetivosModal" class="fixed inset-0 z-50 hidden">
    <div class="modal-backdrop fixed inset-0 bg-gray-600 bg-opacity-50" onclick="closeModal('asignarObjetivosModal')"></div>
    <div class="fixed inset-0 flex items-start sm:items-center justify-center p-2 sm:p-4">
        <div class="modal-content bg-white rounded-lg shadow-xl w-full max-w-xl sm:max-w-2xl max-h-[90vh] sm:max-h-[80vh] overflow-hidden">
            <div class="p-4 sm:p-6">
                <!-- Header -->
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-bullseye mr-2 text-green-500"></i>
                        Asignar Objetivos
                    </h3>
                    <button onclick="closeModal('asignarObjetivosModal')" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Contenido del Modal -->
                <div class="max-h-[60vh] overflow-y-auto pr-1 sm:pr-2">
                    <!-- Loading -->
                    <div id="loadingObjetivos" class="text-center py-8">
                        <i class="fas fa-spinner fa-spin text-2xl text-green-500"></i>
                        <p class="text-gray-600 mt-2 text-sm">Cargando objetivos...</p>
                    </div>

                    <!-- Lista de Objetivos -->
                    <div id="listaObjetivos" class="space-y-3 hidden">
                        <!-- Los objetivos se cargarán aquí dinámicamente -->
                    </div>

                    <!-- Mensaje de error -->
                    <div id="errorObjetivos" class="text-center py-8 hidden">
                        <i class="fas fa-exclamation-triangle text-2xl text-red-500"></i>
                        <p class="text-gray-600 mt-2">Error al cargar los objetivos</p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex flex-col sm:flex-row gap-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModal('asignarObjetivosModal')" class="btn-neu-secondary flex-1 text-sm sm:text-base py-2">
                        Cancelar
                    </button>
                    <button type="button" onclick="guardarObjetivos()" class="btn-neu bg-green-500 hover:bg-green-600 text-white flex-1 text-sm sm:text-base py-2">
                        <i class="fas fa-save mr-2"></i>
                        Guardar Objetivos
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ver Objetivos Seleccionados -->
<div id="verObjetivosModal" class="fixed inset-0 z-50 hidden">
    <div class="modal-backdrop fixed inset-0 bg-gray-600 bg-opacity-50" onclick="closeModal('verObjetivosModal')"></div>
    <div class="fixed inset-0 flex items-start sm:items-center justify-center p-2 sm:p-4">
        <div class="modal-content bg-white rounded-lg shadow-xl w-full max-w-xl sm:max-w-2xl max-h-[90vh] sm:max-h-[80vh] overflow-hidden">
            <div class="p-4 sm:p-6">
                <!-- Header -->
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-list-check mr-2 text-red-500"></i>
                        Mis Objetivos Asignados
                    </h3>
                    <button onclick="closeModal('verObjetivosModal')" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Contenido del Modal -->
                <div class="max-h-[60vh] overflow-y-auto pr-1 sm:pr-2">
                    <!-- Loading -->
                    <div id="loadingVerObjetivos" class="text-center py-8">
                        <i class="fas fa-spinner fa-spin text-2xl text-red-500"></i>
                        <p class="text-gray-600 mt-2 text-sm">Cargando objetivos...</p>
                    </div>

                    <!-- Lista de Objetivos Seleccionados -->
                    <div id="listaVerObjetivos" class="space-y-3 hidden">
                        <!-- Los objetivos se cargarán aquí dinámicamente -->
                    </div>

                    <!-- Mensaje si no hay objetivos -->
                    <div id="sinObjetivos" class="text-center py-8 hidden">
                        <i class="fas fa-inbox text-3xl text-gray-400 mb-2"></i>
                        <p class="text-gray-600 text-sm sm:text-base">No tienes objetivos asignados</p>
                        <button onclick="abrirModalAsignarObjetivos()" class="mt-3 text-red-500 hover:text-red-700 text-xs sm:text-sm font-medium">
                            <i class="fas fa-plus mr-1"></i> Asignar objetivos
                        </button>
                    </div>

                    <!-- Mensaje de error -->
                    <div id="errorVerObjetivos" class="text-center py-8 hidden">
                        <i class="fas fa-exclamation-triangle text-2xl text-red-500"></i>
                        <p class="text-gray-600 mt-2 text-sm">Error al cargar los objetivos</p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex flex-col sm:flex-row gap-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModal('verObjetivosModal')" class="btn-neu-secondary flex-1 text-sm sm:text-base py-2">
                        Cerrar
                    </button>
                    <button type="button" onclick="abrirModalAsignarObjetivos()" class="btn-neu bg-red-500 hover:bg-red-600 text-white flex-1 text-sm sm:text-base py-2">
                        <i class="fas fa-plus mr-2"></i>
                        Agregar Más
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Asignar Preferencias -->
<div id="asignarPreferenciasModal" class="fixed inset-0 z-50 hidden">
    <div class="modal-backdrop fixed inset-0 bg-gray-600 bg-opacity-50" onclick="closeModal('asignarPreferenciasModal')"></div>
    <div class="fixed inset-0 flex items-start sm:items-center justify-center p-2 sm:p-4">
        <div class="modal-content bg-white rounded-lg shadow-xl w-full max-w-xl sm:max-w-2xl max-h-[90vh] sm:max-h-[80vh] overflow-hidden">
            <div class="p-4 sm:p-6">
                <!-- Header -->
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-bullseye mr-2 text-green-500"></i>
                        Asignar Preferencia
                    </h3>
                    <button onclick="closeModal('asignarPreferenciasModal')" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Contenido del Modal -->
                <div class="max-h-[60vh] overflow-y-auto pr-1 sm:pr-2">
                    <!-- Loading -->
                    <div id="loadingPreferencias" class="text-center py-8">
                        <i class="fas fa-spinner fa-spin text-2xl text-green-500"></i>
                        <p class="text-gray-600 mt-2 text-sm">Cargando Preferencias...</p>
                    </div>

                    <!-- Lista de Preferencias -->
                    <div id="listaPreferencias" class="space-y-3 hidden">
                        <!-- Los preferencias se cargarán aquí dinámicamente -->
                    </div>

                    <!-- Mensaje de error -->
                    <div id="errorPreferencias" class="text-center py-8 hidden">
                        <i class="fas fa-exclamation-triangle text-2xl text-red-500"></i>
                        <p class="text-gray-600 mt-2 text-sm">Error al cargar los preferencias</p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex flex-col sm:flex-row gap-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModal('asignarPreferenciasModal')" class="btn-neu-secondary flex-1 text-sm sm:text-base py-2">
                        Cancelar
                    </button>
                    <button type="button" onclick="guardarPreferencias()" class="btn-neu bg-green-500 hover:bg-green-600 text-white flex-1 text-sm sm:text-base py-2">
                        <i class="fas fa-save mr-2"></i>
                        Guardar Preferencias
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ver Preferencias Seleccionados -->
<div id="verPreferenciasModal" class="fixed inset-0 z-50 hidden">
    <div class="modal-backdrop fixed inset-0 bg-gray-600 bg-opacity-50" onclick="closeModal('verPreferenciasModal')"></div>
    <div class="fixed inset-0 flex items-start sm:items-center justify-center p-2 sm:p-4">
        <div class="modal-content bg-white rounded-lg shadow-xl w-full max-w-xl sm:max-w-2xl max-h-[90vh] sm:max-h-[80vh] overflow-hidden">
            <div class="p-4 sm:p-6">
                <!-- Header -->
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-list-check mr-2 text-red-500"></i>
                        Mis Preferencias Asignados
                    </h3>
                    <button onclick="closeModal('verPreferenciasModal')" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Contenido del Modal -->
                <div class="max-h-[60vh] overflow-y-auto pr-1 sm:pr-2">
                    <!-- Loading -->
                    <div id="loadingVerPreferencias" class="text-center py-8">
                        <i class="fas fa-spinner fa-spin text-2xl text-red-500"></i>
                        <p class="text-gray-600 mt-2 text-sm">Cargando preferencias...</p>
                    </div>

                    <!-- Lista de Preferencias Seleccionados -->
                    <div id="listaVerPreferencias" class="space-y-3 hidden">
                        <!-- Los objetivos se cargarán aquí dinámicamente -->
                    </div>

                    <!-- Mensaje si no hay Preferencias -->
                    <div id="sinPreferencias" class="text-center py-8 hidden">
                        <i class="fas fa-inbox text-3xl text-gray-400 mb-2"></i>
                        <p class="text-gray-600 text-sm sm:text-base">No tienes preferencias asignadas</p>
                        <button onclick="abrirModalAsignarPreferencias()" class="mt-3 text-red-500 hover:text-red-700 text-xs sm:text-sm font-medium">
                            <i class="fas fa-plus mr-1"></i> Asignar Preferencias
                        </button>
                    </div>

                    <!-- Mensaje de error -->
                    <div id="errorVerPreferencias" class="text-center py-8 hidden">
                        <i class="fas fa-exclamation-triangle text-2xl text-red-500"></i>
                        <p class="text-gray-600 mt-2 text-sm">Error al cargar los preferencias</p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex gap-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModal('verPreferenciasModal')" class="btn-neu-secondary flex-1 text-sm sm:text-base py-2">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal generar dieta y seleccionar dieta  -->
<div id="modal-preferencia" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-start sm:items-center justify-center p-2 sm:p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-3xl sm:max-w-4xl max-h-[92vh] overflow-hidden">
        <div class="p-4 md:p-6 flex flex-col h-full">
            <div class="flex justify-between items-center mb-4 flex-wrap gap-2">
                <h3 class="text-lg md:text-xl font-bold text-purple-600 flex items-center">
                    <i class="fas fa-utensils mr-2"></i>
                    🍽️ Plan Nutricional Personalizado
                </h3>
                <button onclick="cerrarModal()" class="text-gray-500 hover:text-gray-700 p-1 rounded-full hover:bg-gray-100 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <div id="resultado-preferencia" class="mt-4 md:mt-6"></div>
            
            <div class="max-h-[60vh] sm:max-h-[65vh] overflow-y-auto mt-2 flex-1">
                <div id="modal-contenido" class="pr-1 sm:pr-2">
                    <!-- Aquí se cargará el contenido dinámicamente -->
                </div>
            </div>
            <div class="mt-4 flex justify-end pt-4 border-t border-gray-200">
                <button onclick="cerrarModal()" 
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 sm:px-6 py-2 rounded-lg transition-colors duration-200 flex items-center text-sm sm:text-base">
                    <i class="fas fa-times mr-2"></i>
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Mis Dietas -->
<div id="dietasModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-hidden">
        <!-- Header -->
        <div class="flex justify-between items-center p-4 md:p-6 border-b">
            <h3 class="text-lg md:text-xl font-semibold text-gray-800">Mis Dietas</h3>
            <div class="flex justify-end p-4 md:p-6  bg-gray-50">
        <button onclick="cerrarDietas()" class="px-4 py-2 bg-gray-500  text-white-500  text-xl">
                Cerrar
            </button>
        </div>

        </div>
        
        <!-- Content -->
        <div id="dietasContent" class="p-4 md:p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
            <!-- Loading -->
            <div class="text-center py-8">
                <i class="fas fa-spinner fa-spin text-blue-500 text-2xl"></i>
                <p class="text-gray-500 mt-2">Cargando dietas...</p>
            </div>
        </div>
        
        <!-- Footer -->
        
    </div>
</div>




<script>
    // Función para abrir el modal y cargar objetivos
    function abrirModalAsignarObjetivos() {
        const modal = document.getElementById('asignarObjetivosModal');
        if (modal) {
            modal.classList.remove('hidden');
            cargarObjetivos();
        } else {
            console.error('Modal no encontrado');
        }
    }

    // Función para cargar Objetivos y actualizar contador
    function cargarObjetivos() {
        const loading = document.getElementById('loadingObjetivos');
        const lista = document.getElementById('listaObjetivos');
        const error = document.getElementById('errorObjetivos');

        // Verificar que los elementos existan
        if (!loading || !lista || !error) {
            console.error('Elementos del modal no encontrados');
            return;
        }

        // Mostrar loading, ocultar otros
        loading.classList.remove('hidden');
        lista.classList.add('hidden');
        error.classList.add('hidden');

        // Usar la ruta objetivos.index
        fetch('{{ route("objetivos.index") }}', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Respuesta del servidor:', response.status);
            
            if (!response.ok) {
                throw new Error(`Error ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Datos recibidos:', data);
            
            // Verificar si la respuesta fue exitosa
            if (!data.success) {
                throw new Error(data.error || 'Error en la respuesta del servidor');
            }
            
            // Ocultar loading
            if (loading) loading.classList.add('hidden');
            
            // Actualizar el contador de objetivos
            const contador = document.getElementById('contadorObjetivos');
            if (contador && data.total_objetivos_usuario !== undefined) {
                contador.textContent = data.total_objetivos_usuario;
            }
            
            // Mostrar la lista de objetivos
            if (data.objetivos && data.objetivos.length === 0) {
                lista.innerHTML = `
                    <div class="text-center py-4 text-gray-500">
                        <i class="fas fa-inbox text-3xl mb-2"></i>
                        <p>No hay objetivos disponibles</p>
                    </div>
                `;
            } else if (data.objetivos && Array.isArray(data.objetivos)) {
                let html = '';
                data.objetivos.forEach(objetivo => {
                    html += `
                        <div class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <input type="checkbox" id="objetivo_${objetivo.id}" 
                                   value="${objetivo.id}" 
                                   class="mr-3 h-4 w-4 text-green-500 focus:ring-green-400 border-gray-300 rounded">
                            <label for="objetivo_${objetivo.id}" class="flex-1 cursor-pointer">
                                <div class="font-medium text-gray-800">${objetivo.nombre || 'Objetivo'}</div>
                                ${objetivo.descripcion ? `
                                    <div class="text-sm text-gray-600 mt-1">${objetivo.descripcion}</div>
                                ` : ''}
                            </label>
                        </div>
                    `;
                });
                lista.innerHTML = html;
            } else {
                throw new Error('Estructura de datos incorrecta');
            }
            
            // Mostrar lista
            if (lista) lista.classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error al cargar objetivos:', error);
            
            // Manejar errores de forma segura
            if (loading && loading.classList) loading.classList.add('hidden');
            if (error && error.innerHTML) {
                error.innerHTML = `
                    <div class="text-center py-4 text-red-500">
                        <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                        <p>Error: ${error.message}</p>
                        <button onclick="cargarObjetivos()" class="mt-2 text-sm text-blue-500 hover:text-blue-700">
                            Reintentar
                        </button>
                    </div>
                `;
                error.classList.remove('hidden');
            }
        });
    }

    // Función para guardar objetivos
    function guardarObjetivos() {
        const checkboxes = document.querySelectorAll('#listaObjetivos input[type="checkbox"]:checked');
        const objetivosSeleccionados = Array.from(checkboxes).map(checkbox => checkbox.value);
        
        if (objetivosSeleccionados.length === 0) {
            alert('Por favor selecciona al menos un objetivo');
            return;
        }

        // Mostrar loading
        const botonGuardar = document.querySelector('button[onclick="guardarObjetivos()"]');
        const textoOriginal = botonGuardar.innerHTML;
        botonGuardar.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Guardando...';
        botonGuardar.disabled = true;

        // Enviar al servidor
        fetch('{{ route("objetivos.guardar") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                objetivos: objetivosSeleccionados
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                
                // Actualizar contador
                const contador = document.getElementById('contadorObjetivos');
                if (contador) {
                    const actual = parseInt(contador.textContent) || 0;
                    contador.textContent = actual + data.total_asignados;
                }
                
                closeModal('asignarObjetivosModal');
                
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error de conexión');
        })
        .finally(() => {
            botonGuardar.innerHTML = textoOriginal;
            botonGuardar.disabled = false;
        });
    }

    
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    // Función para abrir el modal y cargar objetivos seleccionados
    function abrirModalVerObjetivos() {
        document.getElementById('verObjetivosModal').classList.remove('hidden');
        cargarObjetivosSeleccionados();
    }

    // Función para cargar objetivos seleccionados del usuario
    function cargarObjetivosSeleccionados() {
        const loading = document.getElementById('loadingVerObjetivos');
        const lista = document.getElementById('listaVerObjetivos');
        const sinObjetivos = document.getElementById('sinObjetivos');
        const error = document.getElementById('errorVerObjetivos');

        // Mostrar loading, ocultar otros
        loading.classList.remove('hidden');
        lista.classList.add('hidden');
        sinObjetivos.classList.add('hidden');
        error.classList.add('hidden');

        // Cargar objetivos seleccionados
        fetch('{{ route("objetivos.seleccionados") }}', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            loading.classList.add('hidden');
            
            if (data.success && data.objetivos && data.objetivos.length > 0) {
                let html = '';
                data.objetivos.forEach(objetivo => {
                    html += `
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-4 border border-gray-200 rounded-lg bg-white shadow-sm gap-3">
                            <div class="flex-1">
                                <div class="font-medium text-gray-800">${objetivo.nombre || 'Objetivo'}</div>
                                ${objetivo.descripcion ? `
                                    <div class="text-sm text-gray-600 mt-1">${objetivo.descripcion}</div>
                                ` : ''}
                                <div class="flex items-center mt-2 text-xs text-gray-500">
                                    <i class="fas fa-calendar mr-1"></i>
                                    <span>Asignado: ${objetivo.fecha_asignacion || 'N/A'}</span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2 self-start sm:self-auto">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    ${objetivo.estado || 'Activo'}
                                </span>
                                <button onclick="eliminarObjetivo(${objetivo.id})" class="text-red-500 hover:text-red-700 p-1 rounded text-xs sm:text-sm" title="Eliminar objetivo">
                                    <i class="fas fa-trash text-sm"></i> <span class="hidden sm:inline">Eliminar</span>
                                </button>
                            </div>
                        </div>
                    `;
                });
                lista.innerHTML = html;
                lista.classList.remove('hidden');
            } else {
                sinObjetivos.classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error al cargar objetivos seleccionados:', error);
            loading.classList.add('hidden');
            error.innerHTML = `
                <div class="text-center py-4 text-red-500">
                    <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                    <p>Error: ${error.message}</p>
                    <button onclick="cargarObjetivosSeleccionados()" class="mt-2 text-sm text-blue-500 hover:text-blue-700">
                        Reintentar
                    </button>
                </div>
            `;
            error.classList.remove('hidden');
        });
    }

    // Función para eliminar objetivo (opcional)
    function eliminarObjetivo(objetivoId) {
        if (confirm('¿Estás seguro de que quieres eliminar este objetivo?')) {
            fetch(`/objetivos/${objetivoId}/eliminar`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Objetivo eliminado correctamente');
                    cargarObjetivosSeleccionados(); // Recargar la lista
                    
                    // Actualizar contador
                    const contador = document.getElementById('contadorObjetivos');
                    if (contador) {
                        const actual = parseInt(contador.textContent) || 0;
                        contador.textContent = Math.max(0, actual - 1);
                    }
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al eliminar el objetivo');
            });
        }
    }



    // Función para abrir el modal y cargar preferencias
    function abrirModalAsignarPreferencias() {
        const modal = document.getElementById('asignarPreferenciasModal');
        if (modal) {
            modal.classList.remove('hidden');
            cargarPreferencias();
        } else {
            console.error('Modal no encontrado');
        }
    }

    // Función para cargar Preferencias y actualizar contador
    function cargarPreferencias() {
        const loading = document.getElementById('loadingPreferencias');
        const lista = document.getElementById('listaPreferencias');
        const error = document.getElementById('errorPreferencias');

        // Verificar que los elementos existan
        if (!loading || !lista || !error) {
            console.error('Elementos del modal no encontrados');
            return;
        }

        // Mostrar loading, ocultar otros
        loading.classList.remove('hidden');
        lista.classList.add('hidden');
        error.classList.add('hidden');

        // Usar la ruta preferencias.index
        fetch('{{ route("preferencias.index") }}', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Respuesta del servidor:', response.status);
            
            if (!response.ok) {
                throw new Error(`Error ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Datos recibidos:', data);
            
            // Verificar si la respuesta fue exitosa
            if (!data.success) {
                throw new Error(data.error || 'Error en la respuesta del servidor');
            }
            
            // Ocultar loading
            if (loading) loading.classList.add('hidden');
            
            // Actualizar el contador de preferencias
            const contador = document.getElementById('contadorPreferencias');
            if (contador && data.total_preferencias_usuario !== undefined) {
                contador.textContent = data.total_preferencias_usuario;
            }
            
            // Mostrar la lista de preferencias
            if (data.preferencias && data.preferencias.length === 0) {
                lista.innerHTML = `
                    <div class="text-center py-4 text-gray-500">
                        <i class="fas fa-inbox text-3xl mb-2"></i>
                        <p>No hay preferencias disponibles</p>
                    </div>
                `;
            } else if (data.preferencias && Array.isArray(data.preferencias)) {
                let html = '';
                data.preferencias.forEach(preferencia => {
                    html += `
                        <div class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <input type="checkbox" id="preferencia_${preferencia.id}" 
                                   value="${preferencia.id}" 
                                   class="mr-3 h-4 w-4 text-green-500 focus:ring-green-400 border-gray-300 rounded">
                            <label for="preferencia_${preferencia.id}" class="flex-1 cursor-pointer">
                                <div class="font-medium text-gray-800">${preferencia.tipo || 'Preferencia'}</div>
                                ${preferencia.descripcion ? `
                                    <div class="text-sm text-gray-600 mt-1">${preferencia.descripcion}</div>
                                ` : ''}
                            </label>
                        </div>
                    `;
                });
                lista.innerHTML = html;
            } else {
                throw new Error('Estructura de datos incorrecta');
            }
            
            // Mostrar lista
            if (lista) lista.classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error al cargar preferencias:', error);
            
            // Manejar errores de forma segura
            if (loading && loading.classList) loading.classList.add('hidden');
            if (error && error.innerHTML) {
                error.innerHTML = `
                    <div class="text-center py-4 text-red-500">
                        <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                        <p>Error: ${error.message}</p>
                        <button onclick="cargarPreferencias()" class="mt-2 text-sm text-blue-500 hover:text-blue-700">
                            Reintentar
                        </button>
                    </div>
                `;
                error.classList.remove('hidden');
            }
        });
    }

    // Función para guardar preferencias
    function guardarPreferencias() {
        const checkboxes = document.querySelectorAll('#listaPreferencias input[type="checkbox"]:checked');
        const preferenciasSeleccionados = Array.from(checkboxes).map(checkbox => checkbox.value);
        
        if (preferenciasSeleccionados.length === 0) {
            alert('Por favor selecciona al menos una preferencias');
            return;
        }

        // Mostrar loading
        const botonGuardar = document.querySelector('button[onclick="guardarPreferencias()"]');
        const textoOriginal = botonGuardar.innerHTML;
        botonGuardar.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Guardando...';
        botonGuardar.disabled = true;

        // Enviar al servidor
        fetch('{{ route("preferencias.guardar") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                preferencias: preferenciasSeleccionados
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                
                // Actualizar contador
                const contador = document.getElementById('contadorPreferencias');
                if (contador) {
                    const actual = parseInt(contador.textContent) || 0;
                    contador.textContent = actual + data.total_asignados;
                }
                
                closeModal('asignarPreferenciasModal');
                
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error de conexión');
        })
        .finally(() => {
            botonGuardar.innerHTML = textoOriginal;
            botonGuardar.disabled = false;
        });
    }

    
    
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    // Función para abrir el modal y cargar preferencias seleccionadas
    function abrirModalVerPreferencias() {
        const modal = document.getElementById('verPreferenciasModal');
        if (modal) {
            modal.classList.remove('hidden');
            cargarPreferenciasSeleccionados();
        } else {
            console.error('Modal ver preferencias no encontrado');
        }
    }

    // Función para cargar preferencias seleccionados del usuario
    function cargarPreferenciasSeleccionados() {
        const loading = document.getElementById('loadingVerPreferencias');
        const lista = document.getElementById('listaVerPreferencias');
        const sinPreferencias = document.getElementById('sinPreferencias');
        const error = document.getElementById('errorVerPreferencias');

        if (!loading || !lista || !sinPreferencias || !error) {
            console.error('Elementos del modal ver preferencias no encontrados');
            return;
        }

        // Mostrar loading, ocultar otros
        loading.classList.remove('hidden');
        lista.classList.add('hidden');
        sinPreferencias.classList.add('hidden');
        error.classList.add('hidden');

        // Cargar objetivos seleccionados
        fetch('{{ route("preferencias.seleccionados") }}', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            loading.classList.add('hidden');
            
            if (data.success && data.preferencias && data.preferencias.length > 0) {
                let html = '';
                data.preferencias.forEach(preferencia => {
                    html += `
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-4 border border-gray-200 rounded-lg bg-white shadow-sm gap-3">
                            <div class="flex-1">
                                <div class="font-medium text-gray-800">${preferencia.tipo || 'preferencia'}</div>
                                ${preferencia.descripcion ? `
                                    <div class="text-sm text-gray-600 mt-1">${preferencia.descripcion}</div>
                                ` : ''}
                                <div class="flex items-center mt-2 text-xs text-gray-500">
                                    <i class="fas fa-calendar mr-1"></i>
                                    <span>Asignado: ${preferencia.fecha || 'N/A'}</span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2 self-start sm:self-auto">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    ${preferencia.estado || 'Activo'}
                                </span>
                                <button onclick="eliminarPreferencia(${preferencia.id})" class="text-red-500 hover:text-red-700 p-1 rounded text-xs sm:text-sm" title="Eliminar objetivo">
                                    <i class="fas fa-trash text-sm"></i> <span class="hidden sm:inline">Eliminar</span>
                                </button>
                            </div>
                        </div>
                    `;
                });
                lista.innerHTML = html;
                lista.classList.remove('hidden');
            } else {
                sinPreferencias.classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error al cargar preferencias seleccionados:', error);
            loading.classList.add('hidden');
            error.innerHTML = `
                <div class="text-center py-4 text-red-500">
                    <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                    <p>Error: ${error.message}</p>
                    <button onclick="cargarPreferenciasSeleccionados()" class="mt-2 text-sm text-blue-500 hover:text-blue-700">
                        Reintentar
                    </button>
                </div>
            `;
            error.classList.remove('hidden');
        });
    }

    // Función para eliminar preferencia 
    function eliminarPreferencia(preferenciaId) {
        if (confirm('¿Estás seguro de que quieres eliminar este preferencia?')) {
            fetch(`/preferencias/${preferenciaId}/eliminar`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Preferencia eliminado correctamente');
                    cargarPreferenciasSeleccionados(); // Recargar la lista
                    
                    // Actualizar contador
                    const contador = document.getElementById('contadorPreferencias');
                    if (contador) {
                        const actual = parseInt(contador.textContent) || 0;
                        contador.textContent = Math.max(0, actual - 1);
                    }
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al eliminar el preferencia');
            });
        }
    }


function generarDieta() {
    // Mostrar loading
    const botones = document.querySelectorAll('button');
    const botonGenerar = botones[1];
    const textoOriginal = botonGenerar.innerHTML;
    botonGenerar.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Generando...';
    botonGenerar.disabled = true;

    fetch(`/dashboard/generar-dieta`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error del servidor: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data.mensaje_personalizado) {
                mostrarResultadoPreferencia(data);
                mostrarAlerta('✅ Plan nutricional generado exitosamente', 'success');
            } else {
                mostrarAlerta('❌ No se pudo generar el plan nutricional', 'error');
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
        <div class="space-y-6">
            <!-- Mensaje Personalizado -->
            <div class="bg-gradient-to-r from-purple-50 to-blue-50 border-l-4 border-purple-500 p-4 md:p-6 rounded-lg">
                <div class="flex items-start">
                    <div class="flex-shrink-0 mr-4">
                        <i class="fas fa-user-circle text-purple-500 text-2xl"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-purple-700 mb-2 flex items-center">
                            <i class="fas fa-comment-medical mr-2"></i>
                            Mensaje Personalizado
                        </h4>
                        <p class="text-gray-700 text-justify italic leading-relaxed text-sm md:text-base">"${data.mensaje_personalizado}"</p>
                    </div>
                </div>
            </div>

            <!-- Sección de Menús -->
            ${generarSeccionMenus(data)}

            <!-- Información Nutricional -->
            ${generarInformacionNutricional(data)}
        </div>
    `;
}

function generarSeccionMenus(data) {
    return `
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <!-- Header de Menús -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-4 py-3 border-b border-gray-200">
                <h3 class="font-bold text-gray-800 flex items-center text-sm md:text-base">
                    <i class="fas fa-clipboard-list mr-2 text-green-500"></i>
                    Menús del Día
                </h3>
            </div>

            <div class="p-4 space-y-6">
                <!-- Desayuno -->
                ${generarMenuComida(data, 'desayuno', 'Desayuno', 'fas fa-sun', 'yellow', '☀️')}
                
                <!-- Almuerzo -->
                ${generarMenuComida(data, 'almuerzo', 'Almuerzo', 'fas fa-utensils', 'orange', '🍽️')}
                
                <!-- Cena -->
                ${generarMenuComida(data, 'cena', 'Cena', 'fas fa-moon', 'blue', '🌙')}

                <!-- Resumen Calórico -->
                ${data.calorias_totales ? `
                    <div class="bg-gradient-to-r from-red-50 to-orange-50 rounded-lg p-4 border border-red-100">
                        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3">
                            <div class="flex items-center">
                                <i class="fas fa-fire text-red-500 text-xl mr-3"></i>
                                <div>
                                    <div class="font-semibold text-gray-800 text-sm md:text-base">Total Calórico Diario</div>
                                    <div class="text-xs md:text-sm text-gray-600">Suma de todas las comidas</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-xl md:text-2xl font-bold text-red-600">${data.calorias_totales}</div>
                                <div class="text-xs md:text-sm text-gray-500">calorías</div>
                            </div>
                        </div>
                    </div>
                ` : ''}
            </div>
        </div>
    `;
}

function generarMenuComida(data, comida, titulo, icono, color, emoji) {
    let alimentos = data.alimentos_por_comida[comida];
    const calorias = data.calorias_por_comida[comida] || 0;
    
    if (alimentos && !Array.isArray(alimentos)) {
        alimentos = Object.values(alimentos);
    }
    
    const tieneAlimentos = alimentos && Array.isArray(alimentos) && alimentos.length > 0;
    const colorClasses = {
        yellow: 'bg-yellow-500 text-yellow-600 bg-yellow-100 border-yellow-200',
        orange: 'bg-orange-500 text-orange-600 bg-orange-100 border-orange-200', 
        blue: 'bg-blue-500 text-blue-600 bg-blue-100 border-blue-200'
    };

    return `
        <div class="border border-gray-200 rounded-lg overflow-hidden">
            <!-- Header del Menú -->
            <div class="bg-gradient-to-r from-${color}-50 to-${color}-25 px-4 py-3 border-b border-${color}-200">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-${color}-100 flex items-center justify-center mr-3 flex-shrink-0">
                            <i class="${icono} text-${color}-500"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 flex items-center text-sm md:text-base">
                                ${emoji} ${titulo}
                            </h4>
                            <p class="text-xs md:text-sm text-gray-600">${calorias} calorías</p>
                        </div>
                    </div>
                    <button 
                        onclick="seleccionarMenuCompleto('${comida}', [${alimentos.map(a => a.id).join(',')}],'${calorias}')"
                        class="bg-${color}-500 hover:bg-${color}-600 text-white px-3 sm:px-4 py-2 rounded-lg transition-colors flex items-center justify-center text-xs sm:text-sm w-full md:w-auto"
                        id="btn-menu-${comida}">
                        <i class="fas fa-check mr-2"></i>
                        Seleccionar Menú
                    </button>
                </div>
            </div>

            <!-- Contenido del Menú -->
            <div class="p-4">
                ${!tieneAlimentos ? `
                    <div class="text-center py-6 text-gray-500 text-sm">
                        <i class="fas fa-inbox text-3xl mb-2"></i>
                        <p>No hay alimentos disponibles para ${titulo.toLowerCase()}</p>
                    </div>
                ` : `
                    <div class="grid gap-3">
                        ${alimentos.map(alimento => `
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-${color}-100 flex items-center justify-center mr-3 flex-shrink-0">
                                        <i class="fas fa-utensil-spoon text-${color}-500 text-xs"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-800 text-sm md:text-base">${alimento.nombre}</div>
                                        <div class="text-xs text-gray-500">Alimento</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-semibold text-${color}-600 text-sm md:text-base">${alimento.calorias}</div>
                                    <div class="text-xs text-gray-500">calorías</div>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                `}
            </div>
        </div>
    `;
}

function generarInformacionNutricional(data) {
    let infoHTML = '';
    
    if (data.GET || data.calorias_recomendadas || data.objetivos_usuario) {
        infoHTML += `
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                <!-- Header Información Nutricional -->
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 px-4 py-3 border-b border-gray-200">
                    <h3 class="font-bold text-gray-800 flex items-center text-sm md:text-base">
                        <i class="fas fa-chart-line mr-2 text-indigo-500"></i>
                        Información Nutricional
                    </h3>
                </div>

                <div class="p-4 space-y-4">
        `;

        // Métricas Principales
        if (data.GET || data.calorias_recomendadas) {
            infoHTML += `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    ${data.GET ? `
                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-burn text-blue-500 text-xl mr-3"></i>
                                    <div>
                                        <div class="font-semibold text-gray-800 text-sm md:text-base">GET</div>
                                        <div class="text-xs md:text-sm text-gray-600">Gasto Energético Total</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-xl md:text-2xl font-bold text-blue-600">${data.GET}</div>
                                    <div class="text-xs md:text-sm text-gray-500">cal</div>
                                </div>
                            </div>
                        </div>
                    ` : ''}
                    
                    ${data.calorias_recomendadas ? `
                        <div class="bg-purple-50 rounded-lg p-4 border border-purple-100">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-bullseye text-purple-500 text-xl mr-3"></i>
                                    <div>
                                        <div class="font-semibold text-gray-800 text-sm md:text-base">Recomendadas</div>
                                        <div class="text-xs md:text-sm text-gray-600">Calorías diarias</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-xl md:text-2xl font-bold text-purple-600">${data.calorias_recomendadas}</div>
                                    <div class="text-xs md:text-sm text-gray-500">cal</div>
                                </div>
                            </div>
                        </div>
                    ` : ''}
                </div>
            `;
        }

        // Estado y Diferencia
        if (data.diferencia_calorias !== undefined && data.estado_calorias) {
            const estadoConfig = {
                'óptimo': { color: 'green', icon: 'fa-check-circle' },
                'bajo': { color: 'yellow', icon: 'fa-exclamation-triangle' },
                'alto': { color: 'red', icon: 'fa-exclamation-circle' }
            };
            
            const estado = estadoConfig[data.estado_calorias] || estadoConfig.óptimo;
            
            infoHTML += `
                <div class="bg-${estado.color}-50 rounded-lg p-4 border border-${estado.color}-100">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <div class="flex items-center">
                            <i class="fas ${estado.icon} text-${estado.color}-500 text-xl mr-3"></i>
                            <div>
                                <div class="font-semibold text-gray-800 text-sm md:text-base">Estado Nutricional</div>
                                <div class="text-xs md:text-sm text-gray-600">Balance calórico</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-lg md:text-xl font-bold text-${estado.color}-600 capitalize">${data.estado_calorias}</div>
                            <div class="text-xs md:text-sm ${data.diferencia_calorias >= 0 ? 'text-green-600' : 'text-red-600'}">
                                ${data.diferencia_calorias >= 0 ? '+' : ''}${data.diferencia_calorias} cal
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        // Objetivos del Usuario
        if (data.objetivos_usuario && data.objetivos_usuario.length > 0) {
            infoHTML += `
                <div class="bg-indigo-50 rounded-lg p-4 border border-indigo-100">
                    <h5 class="font-semibold text-gray-800 mb-3 flex items-center text-sm md:text-base">
                        <i class="fas fa-tasks text-indigo-500 mr-2"></i>
                        Tus Objetivos
                    </h5>
                    <div class="grid gap-2">
                        ${data.objetivos_usuario.map(objetivo => `
                            <div class="flex items-center p-2 bg-white rounded border border-indigo-100">
                                <i class="fas fa-check-circle text-indigo-400 mr-3"></i>
                                <span class="text-gray-700 text-xs md:text-sm">${objetivo}</span>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;
        }

        infoHTML += `
                </div>
            </div>
        `;
    }
    
    return infoHTML;
}

// Funciones del Modal (se mantienen igual)
function mostrarModal(contenido) {
    const modal = document.getElementById('modal-preferencia');
    const modalContenido = document.getElementById('modal-contenido');
    
    modalContenido.innerHTML = contenido;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function cerrarModal() {
    const modal = document.getElementById('modal-preferencia');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = 'auto';
    location.reload();
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

let menusSeleccionados = {};

function seleccionarMenuCompleto(tipoMenu, alimentosIds, calorias) {
    const boton = document.getElementById(`btn-menu-${tipoMenu}`);
    
    // Verificar si el menú ya está seleccionado
    if (menusSeleccionados[tipoMenu]) {
        // Deseleccionar menú
        delete menusSeleccionados[tipoMenu];
        boton.classList.remove('bg-blue-500', 'hover:bg-blue-600');
        boton.classList.add(`bg-${getColorByTipo(tipoMenu)}-500`, `hover:bg-${getColorByTipo(tipoMenu)}-600`);
        boton.innerHTML = '<i class="fas fa-check mr-2"></i> Seleccionar Menú';
    } else {
        // Seleccionar menú
        menusSeleccionados[tipoMenu] = {
            tipo: tipoMenu,
            calorias : calorias,
            alimentos: alimentosIds,
            fecha: new Date().toISOString().split('T')[0] // Fecha actual
        };
        boton.classList.remove(`bg-${getColorByTipo(tipoMenu)}-500`, `hover:bg-${getColorByTipo(tipoMenu)}-600`);
        boton.classList.add('bg-blue-500', 'hover:bg-blue-600');
        boton.innerHTML = '<i class="fas fa-check-circle mr-2"></i> Menú Seleccionado';
        
        // GUARDAR AUTOMÁTICAMENTE al seleccionar
        guardarMenuSeleccionado(tipoMenu, alimentosIds,calorias);
    }
    

}

// Función para guardar el menú en el backend
async function guardarMenuSeleccionado(tipoMenu, alimentosIds, calorias) {
    try {
        const response = await fetch('/menus', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                tipo: tipoMenu,
                fecha_asignacion: new Date().toISOString().split('T')[0],
                alimentos: alimentosIds,
                calorias : calorias
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            console.log(`✅ Menú ${tipoMenu} guardado correctamente`);
            mostrarNotificacion(`Menú de ${tipoMenu} guardado`, 'success');
        } else {
            console.error('Error al guardar menú:', result.message);
            mostrarNotificacion(`Error al guardar menú: ${result.message}`, 'error');
            
            // Revertir selección si hay error
            const boton = document.getElementById(`btn-menu-${tipoMenu}`);
            delete menusSeleccionados[tipoMenu];
            boton.classList.remove('bg-blue-500', 'hover:bg-blue-600');
            boton.classList.add(`bg-${getColorByTipo(tipoMenu)}-500`, `hover:bg-${getColorByTipo(tipoMenu)}-600`);
            boton.innerHTML = '<i class="fas fa-check mr-2"></i> Seleccionar Menú';
        }
    } catch (error) {
        console.error('Error de conexión:', error);
        mostrarNotificacion('Error de conexión al guardar el menú', 'error');
        
        // Revertir selección si hay error de conexión
        const boton = document.getElementById(`btn-menu-${tipoMenu}`);
        delete menusSeleccionados[tipoMenu];
        boton.classList.remove('bg-blue-500', 'hover:bg-blue-600');
        boton.classList.add(`bg-${getColorByTipo(tipoMenu)}-500`, `hover:bg-${getColorByTipo(tipoMenu)}-600`);
        boton.innerHTML = '<i class="fas fa-check mr-2"></i> Seleccionar Menú';
    }
}

// Función para obtener color por tipo de menú
function getColorByTipo(tipoMenu) {
    const colores = {
        'desayuno': 'yellow',
        'almuerzo': 'orange', 
        'cena': 'blue',
        'otro': 'gray'
    };
    return colores[tipoMenu] || 'gray';
}



// Función para notificaciones
function mostrarNotificacion(mensaje, tipo = 'info') {
    // Puedes usar Toast, SweetAlert, o simplemente un alert
    if (tipo === 'success') {
        // Usar Toast de Tailwind si tienes, o simple alert
        alert(`✅ ${mensaje}`);
    } else if (tipo === 'error') {
        alert(`❌ ${mensaje}`);
    } else {
        alert(`ℹ️ ${mensaje}`);
    }
}


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

// Cargar progreso automáticamente al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    cargarProgreso();
});



function renderPesoChart(historialPeso) {
    const chartContainer = document.getElementById('pesoChart');
    if (!chartContainer) return;
    
    chartContainer.innerHTML = '';

    if (!historialPeso || historialPeso.length === 0) {
        chartContainer.innerHTML = '<p class="text-gray-500 text-center text-xs md:text-sm w-full">No hay datos de peso</p>';
        return;
    }

    // Ordenar por fecha para asegurar correcta visualización
    historialPeso.sort((a, b) => new Date(a.fecha_registro) - new Date(b.fecha_registro));
    
    const pesos = historialPeso.map(item => item.peso).filter(peso => peso != null);
    if (pesos.length === 0) {
        chartContainer.innerHTML = '<p class="text-gray-500 text-center text-xs md:text-sm w-full">No hay datos válidos de peso</p>';
        return;
    }

    // Calcular rangos para mejor visualización
    const maxPeso = Math.max(...pesos);
    const minPeso = Math.min(...pesos);
    const rangoTotal = maxPeso - minPeso;
    
    // Ajustar el rango para que no sea demasiado pequeño
    const rangoVisual = rangoTotal > 5 ? rangoTotal : 10;
    const pesoBase = minPeso - 2; // Margen inferior

    // Crear contenedor principal
    const chartWrapper = document.createElement('div');
    chartWrapper.className = 'w-full h-40 sm:h-48 md:h-64 flex items-end justify-between space-x-2 md:space-x-4 px-2 sm:px-4';

    historialPeso.forEach((item, index) => {
        if (item.peso == null) return;
        
        // Calcular altura basada en el rango real
        const alturaPorcentaje = ((item.peso - pesoBase) / rangoVisual) * 90;
        const barContainer = document.createElement('div');
        barContainer.className = 'flex flex-col items-center justify-end flex-1 h-full group relative';
        
        // Línea de conexión entre puntos (para tendencia)
        if (index > 0) {
            const line = document.createElement('div');
            line.className = 'absolute top-1/2 h-0.5 bg-gray-300 -z-10 hidden sm:block';
            line.style.left = '-50%';
            line.style.right = '150%';
            line.style.top = '50%';
            barContainer.appendChild(line);
        }
        
        // Barra/Punto principal
        const bar = document.createElement('div');
        const esUltimo = index === historialPeso.length - 1;
        const esPrimero = index === 0;
        
        bar.className = `w-4 sm:w-6 md:w-8 rounded-t-lg transition-all duration-700 cursor-pointer shadow-lg ${
            esUltimo ? 'bg-gradient-to-t from-red-400 via-red-500 to-red-600 ring-2 ring-yellow-400' :
            esPrimero ? 'bg-gradient-to-t from-green-400 via-green-500 to-green-600' :
            'bg-gradient-to-t from-blue-400 via-blue-500 to-blue-600'
        } hover:scale-110 hover:shadow-xl`;
        bar.style.height = '0%';
        
        // Valor del peso
        const valueLabel = document.createElement('div');
        valueLabel.className = 'text-[10px] sm:text-xs md:text-sm font-bold text-gray-800 mb-1 sm:mb-2 transition-all duration-300';
        valueLabel.textContent = `${item.peso}kg`;
        
        // Fecha
        const dateLabel = document.createElement('div');
        dateLabel.className = 'text-[10px] sm:text-xs text-gray-600 mt-1 sm:mt-2 text-center font-medium';
        const fecha = new Date(item.fecha_registro);
        dateLabel.textContent = fecha.toLocaleDateString('es-ES', { 
            month: 'short', 
            day: 'numeric' 
        });
        
        // Indicador de progreso
        if (index > 0) {
            const cambio = item.peso - historialPeso[index - 1].peso;
            const cambioElement = document.createElement('div');
            cambioElement.className = `text-[10px] sm:text-xs font-bold mb-1 ${
                cambio < 0 ? 'text-green-600' : 
                cambio > 0 ? 'text-red-600' : 
                'text-gray-500'
            }`;
            cambioElement.textContent = cambio < 0 ? `▼${Math.abs(cambio).toFixed(1)}` : 
                                      cambio > 0 ? `▲${cambio.toFixed(1)}` : '=';
            barContainer.appendChild(cambioElement);
        }
        
        barContainer.appendChild(valueLabel);
        barContainer.appendChild(bar);
        barContainer.appendChild(dateLabel);
        chartWrapper.appendChild(barContainer);

        // Animación de entrada
        setTimeout(() => {
            bar.style.height = `${alturaPorcentaje}%`;
        }, index * 150);
    });
    
    chartContainer.appendChild(chartWrapper);

    // Leyenda de colores
    const legend = document.createElement('div');
    legend.className = 'flex flex-wrap justify-center space-x-2 sm:space-x-4 mt-3 sm:mt-4 text-[10px] sm:text-xs';
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

    // Ordenar metas: completadas primero, luego por porcentaje descendente
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
                    <div class="font-semibold text-gray-800 text-sm md:text-base ${
                        isCompleted ? 'line-through text-green-700' : ''
                    }">
                        ${meta.descripcion}
                        ${isCompleted ? ' ✅' : ''}
                    </div>
                    ${meta.objetivo_origen ? `
                        <div class="text-xs text-gray-500 mt-1">${meta.objetivo_origen}</div>
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
                        estadoActual.peso < estadoInicial?.peso ? 'text-green-600' : 
                        estadoActual.peso > estadoInicial?.peso ? 'text-red-600' : 
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
                        <div class="flex justify-between items-center text-sm">
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
    // Métricas originales
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

function verDietas() {
    // Mostrar loading
    document.getElementById('dietasModal').classList.remove('hidden');
    
    // Hacer petición al controlador
    fetch('/mis-dietas')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderDietas(data.data);
            } else {
                mostrarError('Error al cargar las dietas');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarError('Error de conexión');
        });
}

function renderDietas(menus) {
    const dietasContainer = document.getElementById('dietasContent');
    
    if (!menus || menus.length === 0) {
        dietasContainer.innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-utensils text-gray-400 text-4xl mb-4"></i>
                <p class="text-gray-500">No tienes dietas asignadas</p>
            </div>
        `;
        return;
    }

    // Agrupar menús por tipo
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
        <div class="space-y-8">
            ${Object.entries(menusPorTipo).map(([tipo, menusDelTipo]) => {
                if (menusDelTipo.length === 0) return '';
                
                return `
                <div class="border border-gray-200 rounded-xl p-6 bg-white">
                    <!-- Header del tipo -->
                    <div class="flex items-center mb-6 pb-4 border-b border-gray-100">
                        <i class="fas ${getIconoTipo(tipo)} text-2xl mr-3 text-${getColorTipo(tipo)}-500"></i>
                        <div>
                            <h4 class="font-bold text-gray-800 text-xl capitalize">${tipo}</h4>
                            <p class="text-gray-600 text-sm">${menusDelTipo.length} menú(s) asignado(s)</p>
                        </div>
                    </div>

                    <!-- Menús de este tipo -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        ${menusDelTipo.map(menu => {
                            const alimentosMenu = menu.alimentos || [];
                            const caloriasTotales = menu.calorias || alimentosMenu.reduce((sum, al) => sum + (al.calorias || 0), 0);
                            
                            return `
                            <div class="border border-gray-200 rounded-lg p-5 bg-gray-50 hover:shadow-md transition-shadow">
                                <!-- Header del menú -->
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex-1">
                                        <h5 class="font-semibold text-gray-800 text-lg mb-1">
                                            ${menu.nombre || `Menú ${tipo}`}
                                        </h5>
                                        <div class="flex items-center text-xs text-gray-500">
                                            <i class="fas fa-calendar mr-1"></i>
                                            ${new Date(menu.fecha_asignacion).toLocaleDateString('es-ES')}
                                        </div>
                                    </div>
                                    <span class="bg-${getColorTipo(tipo)}-100 text-${getColorTipo(tipo)}-800 text-sm font-semibold px-3 py-1 rounded-full">
                                        ${caloriasTotales} kcal
                                    </span>
                                </div>

                                ${menu.descripcion ? `
                                    <p class="text-sm text-gray-600 mb-4">${menu.descripcion}</p>
                                ` : ''}

                                <!-- Alimentos del menú -->
                                <div class="mb-4">
                                    <h6 class="font-medium text-gray-700 text-sm mb-3 flex items-center">
                                        <i class="fas fa-utensil-spoon mr-2"></i>
                                        Alimentos (${alimentosMenu.length})
                                    </h6>
                                    <div class="space-y-2">
                                        ${alimentosMenu.map(alimento => `
                                            <div class="flex justify-between items-center text-sm p-2 bg-white rounded border border-gray-100">
                                                <span class="font-medium text-gray-800 flex-1">
                                                    ${alimento.alimento?.nombre || 'Alimento'}
                                                </span>
                                                <div class="text-right text-xs text-gray-600">
                                                    ${alimento.cantidad || ''} ${alimento.unidad || ''}
                                                    ${alimento.calorias ? ` • ${alimento.calorias} kcal` : ''}
                                                </div>
                                            </div>
                                        `).join('')}
                                    </div>
                                </div>

                                <!-- Footer del menú -->
                                <div class="flex justify-between items-center pt-3 border-t border-gray-200 text-xs text-gray-500">
                                    <div class="flex items-center space-x-2">
                                        <span class="bg-white px-2 py-1 rounded border">
                                            ID: ${menu.id}
                                        </span>
                                        <!-- Indicador de validación -->
                                        <span class="flex items-center ${menu.validado ? 'text-green-600' : 'text-red-600'}">
                                            <i class="fas ${menu.validado ? 'fa-check-circle' : 'fa-times-circle'} mr-1"></i>
                                            ${menu.validado ? 'Validado' : 'Pendiente'}
                                        </span>
                                    </div>
                                    <span>Asignación: ${menu.id_usuario}</span>
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

// Funciones auxiliares (se mantienen igual)
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

function mostrarError(mensaje) {
    const dietasContainer = document.getElementById('dietasContent');
    dietasContainer.innerHTML = `
        <div class="text-center py-8 text-red-600">
            <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
            <p>${mensaje}</p>
        </div>
    `;
}




</script>
@endsection
