<nav class="bg-white shadow-sm border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            
            <!-- Logo -->
            <div class="flex items-center space-x-3">
                @auth
                <a href="{{ Route::has('dashboard') ? route('dashboard') : url('/') }}" class="flex items-center space-x-3">
                    <x-application-logo class="h-8 w-auto text-indigo-600" />
                    <span class="hidden sm:block text-lg font-semibold text-gray-800">NutriGym</span>
                </a>
                @else
                
                <a href="{{ Route::has('user') ? route('user') : url('/') }}" class="flex items-center space-x-3">
                    <x-application-logo class="h-8 w-auto text-indigo-600" />
                    <span class="hidden sm:block text-lg font-semibold text-gray-800">NutriGym</span>
                </a>
                @endauth
            </div>

            <!-- Menú desktop -->
            <div class="hidden md:flex items-center space-x-1">
                @auth
                    <!-- Usuario AUTENTICADO - Administrador -->
                    <span class="px-4 py-2 text-sm font-medium text-gray-700">
                        👋 Hola, {{ Auth::user()->nombre }} (ID: {{ Auth::user()->id }})
                    </span>
                    
                    @if(Route::has('control'))
                        <a href="{{ route('control') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-lg transition">
                            👥 Ver clientes
                        </a>
                    @endif
                    
                    @if(Route::has('cuenta'))
                        <a href="{{ route('cuenta') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-lg transition">
                            👤 Ver cuenta
                        </a>
                    @endif
                    
                    @if(Route::has('logout'))
                        <a href="{{ route('logout') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-lg transition">
                            🚪 Cerrar sesión
                        </a>
                    @endif

                @else
                    <!-- Usuario NO autenticado -->
                    @if(Route::has('registrar_usuario'))
                        <a href="{{ route('registrar_usuario') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition">
                            👤 Registrar Usuario
                        </a>
                    @endif
                    
                    @if(Route::has('login'))
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition">
                            🔑 Iniciar Sesión
                        </a>
                    @endif
                @endauth
            </div>

            <!-- Botón menú móvil -->
            <div class="md:hidden">
                <button id="mobile-menu-button" type="button" class="p-2 rounded-md text-gray-700 hover:bg-gray-100">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Menú móvil -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-200 shadow-lg">
            <div class="px-4 pt-2 pb-4 space-y-2">
                @auth
                    <!-- Usuario AUTENTICADO - Móvil -->
                    <div class="px-4 py-2 text-sm font-medium text-gray-700 border-b border-gray-100">
                        👋 Hola, {{ Auth::user()->nombre }}
                    </div>
                    
                    @if(Route::has('control'))
                        <a href="{{ route('control') }}" class="block px-4 py-3 text-base font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-lg transition">
                            👥 Ver clientes
                        </a>
                    @endif
                    
                    @if(Route::has('cuenta'))
                        <a href="{{ route('cuenta') }}" class="block px-4 py-3 text-base font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-lg transition">
                            👤 Ver cuenta
                        </a>
                    @endif
                    
                    @if(Route::has('logout'))
                        <a href="{{ route('logout') }}" class="block px-4 py-3 text-base font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-lg transition">
                            🚪 Cerrar sesión
                        </a>
                    @endif

                @else
                    <!-- Usuario NO autenticado - Móvil -->
                    @if(Route::has('registrar_usuario'))
                        <a href="{{ route('registrar_usuario') }}" class="block px-4 py-3 text-base font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-lg transition">
                            👤 Registrar Usuario
                        </a>
                    @endif
                    
                    @if(Route::has('login'))
                        <a href="{{ route('login') }}" class="block px-4 py-3 text-base font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-lg transition">
                            🔑 Iniciar Sesión
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</nav>

<script>
    // Menú móvil
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    });

    // Cerrar menú al hacer clic en un enlace
    document.querySelectorAll('#mobile-menu a').forEach(link => {
        link.addEventListener('click', () => {
            document.getElementById('mobile-menu').classList.add('hidden');
        });
    });
</script>