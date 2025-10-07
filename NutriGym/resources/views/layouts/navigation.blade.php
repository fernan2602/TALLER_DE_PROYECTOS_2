<nav class="bg-white shadow-sm border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            
            <!-- Logo y nombre -->
            <div class="flex items-center space-x-3">
                <a href="{{ Route::has('dashboard') ? route('dashboard') : '#' }}" class="flex items-center space-x-3">
                    <x-application-logo class="h-8 w-auto text-indigo-600" />
                    <span class="hidden sm:block text-lg font-semibold text-gray-800">NutriGym</span>
                </a>
            </div>

            <!-- Menú desktop -->
            <div class="hidden md:flex items-center space-x-1">
                @auth
                    <!-- Usuario AUTENTICADO -->
                    <span class="px-4 py-2 text-sm font-medium text-gray-700">
                        👋 Hola, {{ Auth::user()->nombre }}
                                {{Auth::user()->id}}

                        
                    </span>
                @else
                    <!-- Usuario NO autenticado -->
                    @if(Route::has('dashboard'))
                        <a href="{{ route('registrar_usuario') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition">
                            👤 Registrar Usuario
                        </a>
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition">
                            👤 Iniciar Sesión
                        </a>
                    @endif
                @endauth
            </div>

            <!-- Botón móvil -->
            <div class="md:hidden">
                <button id="mobile-menu-button" class="p-2 rounded-lg text-gray-600 hover:bg-gray-100 transition">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Menú móvil mejorado -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-200 shadow-lg">
            <div class="px-4 pt-2 pb-4 space-y-2">
                @if(Route::has('dashboard'))
                    <a href="{{ route('registrar_usuario') }}" class="block px-4 py-3 text-base font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-lg transition">
                        👤 Registrar Usuario
                    </a>
                    <a href="{{ route('login') }}" class="block px-4 py-3 text-base font-medium text-gray-700 hover:text-indigo-700 hover:bg-indigo-50 rounded-lg transition">
                        👤 Iniciar Seción
                    </a>
                @endif
            </div>
        </div>
    </div>
</nav>

<script>
    // Menú móvil con cierre al hacer clic en un link
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    });

    // Cerrar menú al hacer clic en un enlace (opcional)
    document.querySelectorAll('#mobile-menu a').forEach(link => {
        link.addEventListener('click', () => {
            document.getElementById('mobile-menu').classList.add('hidden');
        });
    });
</script>