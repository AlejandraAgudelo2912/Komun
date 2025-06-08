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

                        <x-komun.input
                            name="title"
                            label="Título"
                            type="text"
                            :value="old('title', $request->title)"
                            required
                            autofocus
                        />

                        <div>
                            <x-komun.label for="description" :value="__('Descripción')" required />
                            <textarea 
                                id="description" 
                                name="description" 
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                                rows="4" 
                                required
                            >{{ old('description', $request->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <x-komun.select
                            name="category_id"
                            label="Categoría"
                            :options="$categories->pluck('name', 'id')"
                            :selected="old('category_id', $request->category_id)"
                            required
                        />

                        <x-komun.select
                            name="priority"
                            label="Prioridad"
                            :options="[
                                'low' => 'Baja',
                                'medium' => 'Media',
                                'high' => 'Alta'
                            ]"
                            :selected="old('priority', $request->priority)"
                            required
                        />

                        <x-komun.select
                            name="status"
                            label="Estado"
                            :options="[
                                'pending' => 'Pendiente',
                                'in_progress' => 'En Progreso',
                                'completed' => 'Completada',
                                'cancelled' => 'Cancelada'
                            ]"
                            :selected="old('status', $request->status)"
                            required
                        />

                        <x-komun.date-input
                            name="deadline"
                            label="Fecha Límite"
                            :value="old('deadline', $request->deadline->format('Y-m-d'))"
                            required
                        />

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