<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        </div>
    </div>
</x-app-layout>
            

                <span class="px-4 py-2 text-sm font-medium text-gray-700">
                        👋 Hola, {{ Auth::user()->nombre }}
                                {{Auth::user()->id}}
                                {{Auth::user()->id_rol}}
                    </span>
            
