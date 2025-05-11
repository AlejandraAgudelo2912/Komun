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
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 