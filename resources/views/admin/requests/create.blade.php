<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Nueva Solicitud') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">¡Error!</strong>
                            <ul class="mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.requests.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Título -->
                            <div>
                                <label for="title" class="block font-medium text-sm text-gray-700">Título</label>
                                <input type="text" name="title" id="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('title') }}" required autofocus>
                                @error('title')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Categoría -->
                            <div>
                                <x-komun.select
                                    name="category_id"
                                    label="Categoría"
                                    :options="$categories->pluck('name', 'id')"
                                    required
                                />
                            </div>

                            <!-- Prioridad -->
                            <div>
                                <x-komun.select
                                    name="priority"
                                    label="Prioridad"
                                    :options="[
                                        'low' => 'Baja',
                                        'medium' => 'Media',
                                        'high' => 'Alta'
                                    ]"
                                    required
                                />
                            </div>

                            <!-- Estado -->
                            <div>
                                <x-komun.select
                                    name="status"
                                    label="Estado"
                                    :options="[
                                        'pending' => 'Pendiente',
                                        'in_progress' => 'En Progreso',
                                        'completed' => 'Completada',
                                        'cancelled' => 'Cancelada'
                                    ]"
                                    required
                                />
                            </div>

                            <!-- Fecha Límite -->
                            <div>
                                <x-komun.date-input
                                    name="deadline"
                                    label="Fecha Límite"
                                    required
                                />
                            </div>

                            <!-- Ubicación -->
                            <div>
                                <label for="location" class="block font-medium text-sm text-gray-700">Ubicación</label>
                                <input type="text" name="location" id="location" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('location') }}" placeholder="Ciudad, País">
                                @error('location')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Máximo de Aplicaciones -->
                            <div>
                                <label for="max_applications" class="block font-medium text-sm text-gray-700">Máximo de Aplicaciones</label>
                                <input type="number" name="max_applications" id="max_applications" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('max_applications', 1) }}" min="1" max="10">
                                @error('max_applications')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Descripción -->
                        <div>
                            <label for="description" class="block font-medium text-sm text-gray-700">Descripción</label>
                            <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notas Adicionales -->
                        <div>
                            <label for="help_notes" class="block font-medium text-sm text-gray-700">Notas Adicionales</label>
                            <textarea name="help_notes" id="help_notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('help_notes') }}</textarea>
                            @error('help_notes')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Opciones Adicionales -->
                        <div class="flex items-center space-x-6">
                            <x-komun.checkbox
                                name="is_urgent"
                                label="Marcar como Urgente"
                            />
                            <x-komun.checkbox
                                name="is_verified"
                                label="Verificar Solicitud"
                            />
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 focus:bg-blue-600 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Crear Solicitud
                            </button>
                            <a href="{{ route('admin.requests.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 