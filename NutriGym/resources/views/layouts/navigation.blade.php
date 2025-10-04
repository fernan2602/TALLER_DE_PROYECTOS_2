<nav class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between h-16 items-center">
        <!-- Logo -->
        <div class="flex-shrink-0">
            <a href="{{ Route::has('dashboard') ? route('dashboard') : '#' }}">
                <x-application-logo class="h-9 w-auto text-gray-800" />
            </a>
        </div>

        <!-- Links -->
        <div class="flex space-x-4">
            @if(Route::has('dashboard'))
                <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900">Dashboard</a>
            @endif
        </div>

        <!-- Usuario / login -->
        <div>
        @auth
            <span>{{ auth()->user()->name }}</span>
            @if(Route::has('logout'))
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit">Cerrar sesión</button>
                </form>
            @endif
        @else
            <a href="{{ route('registrar_usuario') }}">Registrarse</a>
        @endauth
        </div>
    </div>
</nav>
