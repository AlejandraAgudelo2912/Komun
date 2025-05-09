@extends('layouts.categories')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.categories.create') }}" 
           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <i class="fas fa-plus mr-2"></i>
            Nueva Categoría
        </a>
    </div>
@endsection
