<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Solicitudes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Lista de Solicitudes</h3>
                        <a href="{{ route('god.requests.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Crear Nueva Solicitud
                        </a>
                    </div>

                    <form action="{{ route('god.requests.filter') }}" method="GET" class="mb-6 bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Búsqueda -->
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700">Buscar</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Buscar en título o descripción">
                            </div>

                            <!-- Categoría -->
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700">Categoría</label>
                                <select name="category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Todas las categorías</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Estado -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Estado</label>
                                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Todos los estados</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>En progreso</option>
                                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completada</option>
                                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                                </select>
                            </div>

                            <!-- Ubicación -->
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700">Ubicación</label>
                                <input type="text" name="location" id="location" value="{{ request('location') }}" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Filtrar por ubicación">
                            </div>

                            <!-- Filtros adicionales -->
                            <div class="col-span-full grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex items-center">
                                    <input type="checkbox" name="urgent" id="urgent" value="1" {{ request('urgent') ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <label for="urgent" class="ml-2 block text-sm text-gray-700">Solo urgentes</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="no_applicants" id="no_applicants" value="1" {{ request('no_applicants') ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <label for="no_applicants" class="ml-2 block text-sm text-gray-700">Sin solicitantes</label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 flex justify-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Filtrar
                            </button>
                            <a href="{{ route('god.requests.index') }}" class="ml-2 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Limpiar filtros
                            </a>
                        </div>
                    </form>

                    @if($requests->isEmpty())
                        <p class="text-gray-500 text-center py-4">No hay solicitudes disponibles en este momento.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($requests as $request)
                                <div class="border rounded-lg p-4 {{ $request->status === 'pending' ? 'bg-yellow-50' : ($request->status === 'in_progress' ? 'bg-green-50' : 'bg-gray-50') }}">
                                    <div class="flex justify-between items-start mb-4">
                                        <h3 class="text-lg font-semibold">{{ $request->title }}</h3>
                                        <span class="px-3 py-1 rounded text-sm {{ $request->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($request->status === 'in_progress' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </div>
                                    
                                    <p class="text-gray-600 mb-4 line-clamp-2">{{ $request->description }}</p>
                                    
                                    <div class="grid grid-cols-2 gap-2 text-sm mb-4">
                                        <div>
                                            <p class="text-gray-500">Categoría</p>
                                            <p class="font-medium">{{ $request->category->name }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">Ubicación</p>
                                            <p class="font-medium">{{ $request->location }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">Fecha límite</p>
                                            <p class="font-medium">{{ $request->deadline->format('d/m/Y') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">Usuario</p>
                                            <p class="font-medium">{{ $request->user->name }}</p>
                                        </div>
                                    </div>

                                    <div class="flex justify-between items-center">
                                        <a href="{{ route('god.requests.show', $request) }}" class="text-blue-500 hover:text-blue-700">
                                            Ver detalles
                                        </a>
                                        @if($request->status === 'pending')
                                            <div class="flex space-x-2">
                                                <a href="{{ route('god.requests.edit', $request) }}" class="text-gray-500 hover:text-gray-700">
                                                    Editar
                                                </a>
                                                <form action="{{ route('god.requests.destroy', $request) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('¿Estás seguro de que quieres eliminar esta solicitud?')">
                                                        Eliminar
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 