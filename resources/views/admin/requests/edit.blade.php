<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Solicitud') }}
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

                    <form action="{{ route('admin.requests.update', [$requestModel]) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="title" class="block font-medium text-sm text-gray-700">Título</label>
                            <input type="text" name="title" id="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('title', $requestModel->title) }}" required autofocus>
                            @error('title')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-komun.input
                                name="description"
                                label="Descripción"
                                type="textarea"
                                :value="$requestModel->description"
                                rows="4"
                                required
                            />
                        </div>

                        <div>
                            <x-komun.select
                                name="category_id"
                                label="Categoría"
                                :options="$categories->pluck('name', 'id')"
                                :selected="$requestModel->category_id"
                                required
                            />
                        </div>

                        <div>
                            <label for="status" class="block font-medium text-sm text-gray-700">Estado</label>
                            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="pending" {{ old('status', $requestModel->status) == 'pending' ? 'selected' : '' }}>Pendiente</option>
                                <option value="in_progress" {{ old('status', $requestModel->status) == 'in_progress' ? 'selected' : '' }}>En Progreso</option>
                                <option value="completed" {{ old('status', $requestModel->status) == 'completed' ? 'selected' : '' }}>Completada</option>
                                <option value="cancelled" {{ old('status', $requestModel->status) == 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                            </select>
                            @error('status')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-komun.select
                                name="priority"
                                label="Prioridad"
                                :options="[
                                    'low' => 'Baja',
                                    'medium' => 'Media',
                                    'high' => 'Alta'
                                ]"
                                :selected="$requestModel->priority"
                                required
                            />
                        </div>

                        <div>
                            <x-komun.input
                                name="location"
                                label="Ubicación"
                                :value="$requestModel->location"
                            />
                        </div>

                        <div>
                            <x-komun.date-input
                                name="deadline"
                                label="Fecha Límite"
                                :value="$requestModel->deadline ? $requestModel->deadline->format('Y-m-d') : ''"
                                required
                            />
                        </div>

                        <div>
                            <x-komun.input
                                name="max_applications"
                                label="Máximo de Aplicaciones"
                                type="number"
                                :value="$requestModel->max_applications"
                                min="1"
                                max="10"
                            />
                        </div>

                        <div>
                            <x-komun.input
                                name="help_notes"
                                label="Notas Adicionales"
                                type="textarea"
                                :value="$requestModel->help_notes"
                                rows="3"
                            />
                        </div>

                        <div class="flex items-center space-x-6">
                            <x-komun.checkbox
                                name="is_urgent"
                                label="Marcar como Urgente"
                                :checked="$requestModel->is_urgent"
                            />
                            <x-komun.checkbox
                                name="is_verified"
                                label="Verificar Solicitud"
                                :checked="$requestModel->is_verified"
                            />
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 focus:bg-blue-600 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Actualizar Solicitud
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
