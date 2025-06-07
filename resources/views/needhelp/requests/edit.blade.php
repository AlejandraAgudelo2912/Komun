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
                    <form action="{{ route('needhelp.requests.update', $requestModel) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-label for="title" :value="__('Título')" />
                            <x-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $requestModel->title)" required autofocus />
                            <x-input-error :for="'title'" class="mt-2" />
                        </div>

                        <div>
                            <x-label for="description" :value="__('Descripción')" />
                            <textarea id="description" name="description" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4" required>{{ old('description', $requestModel->description) }}</textarea>
                            <x-input-error :for="'description'" class="mt-2" />
                        </div>

                        <div>
                            <x-komun.select
                                name="category_id"
                                label="Categoría"
                                :options="$categories->pluck('name', 'id')"
                                :selected="$requestModel->category_id"
                                required
                            />
                            <x-input-error :for="'category_id'" class="mt-2" />
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
                            <x-input-error :for="'priority'" class="mt-2" />
                        </div>

                        <div>
                            <x-komun.select
                                name="status"
                                label="Estado"
                                :options="[
                                    'pending' => 'Pendiente',
                                    'in_progress' => 'En progreso',
                                    'completed' => 'Completada',
                                    'cancelled' => 'Cancelada'
                                ]"
                                :selected="$requestModel->status"
                                required
                            />
                            <x-input-error :for="'status'" class="mt-2" />
                        </div>

                        <div>
                            <x-label for="deadline" :value="__('Fecha Límite')" />
                            <x-input id="deadline" name="deadline" type="date" class="mt-1 block w-full" :value="old('deadline', $requestModel->deadline->format('Y-m-d'))" required />
                            <x-input-error :for="'deadline'" class="mt-2" />
                        </div>

                        <div>
                            <x-label for="help_notes" :value="__('Notas Adicionales')" />
                            <textarea id="help_notes" name="help_notes" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('help_notes', $requestModel->help_notes) }}</textarea>
                            <x-input-error :for="'help_notes'" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-button type="submit" class="bg-primary-600 hover:bg-primary-700">
                                {{ __('Guardar Cambios') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 