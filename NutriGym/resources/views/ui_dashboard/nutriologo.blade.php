@extends('layouts.app')

@section('content')
                <span class="px-4 py-2 text-sm font-medium text-gray-700">
                        👋 Hola, {{ Auth::user()->nombre }}
                                {{Auth::user()->id}}
                                {{Auth::user()->id_rol}}
                    </span>
@endsection
