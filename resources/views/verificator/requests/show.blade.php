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
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Título</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $requestModel->title }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Categoría</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $requestModel->category->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Ubicación</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $requestModel->location }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Prioridad</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @switch($requestModel->priority)
                                    @case('low') Baja @break
                                    @case('medium') Media @break
                                    @case('high') Alta @break
                                @endswitch
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Fecha Límite</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $requestModel->deadline->format('d/m/Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Estado</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @switch($requestModel->status)
                                    @case('pending') Pendiente @break
                                    @case('in_progress') En Progreso @break
                                    @case('completed') Completada @break
                                    @case('cancelled') Cancelada @break
                                @endswitch
                            </dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Descripción</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $requestModel->description }}</dd>
                        </div>
                        @if($requestModel->verification_notes)
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Notas de Verificación</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $requestModel->verification_notes }}</dd>
                            </div>
                        @endif
                    </dl>

                    <div class="mt-6 flex items-center gap-4">
                        <a href="{{ route('verificator.requests.edit', $requestModel) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                            {{ __('Editar') }}
                        </a>
                        <form action="{{ route('verificator.requests.destroy', $requestModel) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700" onclick="return confirm('¿Estás seguro de que deseas eliminar esta solicitud?')">
                                {{ __('Eliminar') }}
                            </button>
                        </form>
                        <a href="{{ route('verificator.requests.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                            {{ __('Volver') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
