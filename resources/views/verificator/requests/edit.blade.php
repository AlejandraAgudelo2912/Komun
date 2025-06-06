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
                    <form action="{{ route('verificator.requests.update', $request) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="title" :value="__('Título')" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $request->title)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Descripción')" />
                            <textarea id="description" name="description" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4" required>{{ old('description', $request->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div>
                            <x-komun.select
                                name="category_id"
                                label="Categoría"
                                :options="$categories->pluck('name', 'id')"
                                :selected="$request->category_id"
                                required
                            />
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
                                :selected="$request->priority"
                                required
                            />
                        </div>

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
                                :selected="$request->status"
                                required
                            />
                        </div>

                        <div>
                            <x-komun.date-input
                                name="deadline"
                                label="Fecha Límite"
                                :value="$request->deadline->format('Y-m-d')"
                                required
                            />
                        </div>

                        <div>
                            <x-komun.input
                                name="verification_notes"
                                label="Notas de Verificación"
                                type="textarea"
                                :value="$request->verification_notes"
                                rows="3"
                            />
                        </div>

                        <div>
                            <x-komun.select
                                name="is_verified"
                                label="Estado de Verificación"
                                :options="[
                                    '1' => 'Verificada',
                                    '0' => 'Pendiente'
                                ]"
                                :selected="$request->is_verified"
                                required
                            />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Actualizar Solicitud') }}</x-primary-button>
                            <a href="{{ route('verificator.requests.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Cancelar') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 