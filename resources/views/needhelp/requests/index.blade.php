<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Mis Solicitudes') }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('needhelp.requests.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Nueva Solicitud
                </a>
                <x-welcome-button />
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Formulario de Filtros -->
                    <form action="{{ route('needhelp.requests.filter') }}" method="GET" class="mb-6 bg-gray-50 p-4 rounded-lg">
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
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>En progreso</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completada</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                                </select>
                            </div>

                            <!-- Ubicación -->
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700">Ubicación</label>
                                <input type="text" name="location" id="location" value="{{ request('location') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Filtrar por ubicación">
                            </div>
                        </div>

                        <div class="mt-4 flex items-center space-x-4">
                            <!-- Filtros de checkbox -->
                            <div class="flex items-center">
                                <input type="checkbox" name="urgent" id="urgent" value="1" {{ request('urgent') ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <label for="urgent" class="ml-2 block text-sm text-gray-900">Solo urgentes</label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="no_applicants" id="no_applicants" value="1" {{ request('no_applicants') ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <label for="no_applicants" class="ml-2 block text-sm text-gray-900">Sin aplicaciones</label>
                            </div>

                            <!-- Botones -->
                            <div class="flex space-x-2">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Filtrar
                                </button>
                                <a href="{{ route('needhelp.requests.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Limpiar
                                </a>
                            </div>
                        </div>
                    </form>

                    @if($requests->isEmpty())
                        <p class="text-gray-500 text-center py-4">No tienes solicitudes creadas.</p>
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
                                            <p class="text-gray-500">Aplicaciones</p>
                                            <p class="font-medium">{{ $request->applicants->count() }}</p>
                                        </div>
                                    </div>

                                    <div class="flex justify-between items-center">
                                        <a href="{{ route('needhelp.requests.show', $request) }}" class="text-blue-500 hover:text-blue-700">
                                            Ver detalles
                                        </a>
                                        @if($request->status === 'pending')
                                            <div class="flex space-x-2">
                                                <a href="{{ route('needhelp.requests.edit', $request) }}" class="text-gray-500 hover:text-gray-700">
                                                    Editar
                                                </a>
                                                <form action="{{ route('needhelp.requests.destroy', $request) }}" method="POST" class="inline">
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

                        <!-- Paginación -->
                        <div class="mt-6">
                            {{ $requests->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 