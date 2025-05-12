<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Solicitudes Disponibles') }}
            </h2>
            <x-welcome-button />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($requestsModel->isEmpty())
                        <p class="text-gray-500 text-center py-4">No hay solicitudes disponibles en este momento.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($requestsModel as $requestModel)
                                <div class="border rounded-lg p-4 bg-white">
                                    <div class="flex justify-between items-start mb-4">
                                        <h3 class="text-lg font-semibold">{{ $requestModel->title }}</h3>
                                        <span class="px-3 py-1 rounded text-sm bg-yellow-100 text-yellow-800">
                                            Pendiente
                                        </span>
                                    </div>

                                    <p class="text-gray-600 mb-4 line-clamp-2">{{ $requestModel->description }}</p>

                                    <div class="grid grid-cols-2 gap-2 text-sm mb-4">
                                        <div>
                                            <p class="text-gray-500">Categoría</p>
                                            <p class="font-medium">{{ $requestModel->category->name }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">Ubicación</p>
                                            <p class="font-medium">{{ $requestModel->location }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">Fecha límite</p>
                                            <p class="font-medium">{{ $requestModel->deadline->format('d/m/Y') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">Solicitante</p>
                                            <p class="font-medium">{{ $requestModel->user->name }}</p>
                                        </div>
                                    </div>

                                    <div class="flex justify-end">
                                        <a href="{{ route('assistant.requests.show', $requestModel) }}" class="bg-blue-500 text-black px-4 py-2 rounded hover:bg-blue-600">
                                            Ver detalles
                                        </a>
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
