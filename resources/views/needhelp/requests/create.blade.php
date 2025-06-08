<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Crear Nueva Solicitud') }}
            </h2>
            <x-welcome-button />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('needhelp.requests.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <x-komun.input
                            name="title"
                            label="Título"
                            type="text"
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
                            >{{ old('description') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <x-komun.select
                            name="category_id"
                            label="Categoría"
                            :options="$categories->pluck('name', 'id')"
                            required
                        />

                        <x-komun.input
                            name="location"
                            label="Ubicación"
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
                            required
                        />

                        <x-komun.date-input
                            name="deadline"
                            label="Fecha Límite"
                            required
                        />

                        <x-komun.input
                            name="max_applications"
                            label="Máximo de aplicaciones"
                            type="number"
                            :value="old('max_applications', 1)"
                            min="1"
                            max="10"
                        />

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Crear Solicitud') }}</x-primary-button>
                            <a href="{{ route('needhelp.requests.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Cancelar') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 