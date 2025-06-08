<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalles de la Solicitud') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">{{ $requestModel->title }}</h3>
                            <p class="text-gray-600 mb-4">{{ $requestModel->description }}</p>
                            
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <p class="text-sm text-gray-500">Estado</p>
                                    <p class="font-medium">{{ ucfirst($requestModel->status) }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Prioridad</p>
                                    <p class="font-medium">{{ ucfirst($requestModel->priority) }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Ubicación</p>
                                    <p class="font-medium">{{ $requestModel->location }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Fecha límite</p>
                                    <p class="font-medium">{{ $requestModel->deadline->format('d/m/Y') }}</p>
                                </div>
                            </div>

                            <div class="mb-4">
                                <p class="text-sm text-gray-500">Categoría</p>
                                <p class="font-medium">{{ $requestModel->category->name }}</p>
                            </div>

                            <div class="mb-4">
                                <p class="text-sm text-gray-500">Solicitante</p>
                                <p class="font-medium">{{ $requestModel->user->name }}</p>
                            </div>

                            @if($requestModel->help_notes)
                                <div class="mb-4">
                                    <p class="text-sm text-gray-500">Notas adicionales</p>
                                    <p class="font-medium">{{ $requestModel->help_notes }}</p>
                                </div>
                            @endif
                        </div>

                        <div>
                            <h4 class="text-lg font-semibold mb-4">Solicitantes</h4>
                            @if($requestModel->applicants->isEmpty())
                                <p class="text-gray-500">No hay solicitantes aún</p>
                            @else
                                <div class="space-y-4">
                                    @foreach($requestModel->applicants as $applicant)
                                        <div class="border rounded-lg p-4">
                                            <p class="font-medium">{{ $applicant->name }}</p>
                                            <p class="text-sm text-gray-600">{{ $applicant->pivot->message }}</p>
                                            <p class="text-sm text-gray-500">Estado: {{ ucfirst($applicant->pivot->status) }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6 flex justify-between">
                        <a href="{{ route('god.requests.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Volver
                        </a>
                        @if($requestModel->status === 'pending')
                            <div class="space-x-2">
                                <a href="{{ route('god.requests.edit', $requestModel) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Editar
                                </a>
                                <form action="{{ route('god.requests.destroy', $requestModel) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('¿Estás seguro de que quieres eliminar esta solicitud?')">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
