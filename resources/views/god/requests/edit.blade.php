<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Request') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('god.requests.update', $requestModel) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-komun.label for="title" :value="__('Title')" />
                            <x-komun.text-input
                                id="title"
                                name="title"
                                type="text"
                                class="mt-1 block w-full"
                                :value="$requestModel->title"
                                required
                                autofocus
                            />
                            <x-input-error class="mt-2" for="title" :messages="$errors->get('title')" />
                        </div>

                        <div>
                            <x-komun.label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4" required>{{ old('description', $requestModel->description) }}</textarea>
                            <x-input-error class="mt-2" for="description" :messages="$errors->get('description')" />
                        </div>

                        <div>
                            <x-komun.label for="category_id" :value="__('Category')" />
                            <x-komun.select
                                id="category_id"
                                name="category_id"
                                :options="$categories->pluck('name', 'id')->toArray()"
                                :selected="old('category_id', $requestModel->category_id)"
                                required
                            />
                            <x-input-error class="mt-2" for="category_id" :messages="$errors->get('category_id')" />
                        </div>

                        <div>
                            <x-komun.label for="priority" :value="__('Priority')" />
                            <x-komun.select
                                id="priority"
                                name="priority"
                                :options="['low' => 'Baja','medium' => 'Media', 'high' => 'Alta']"
                                :selected="old('priority', $requestModel->priority)"
                                required
                            />
                            <x-input-error class="mt-2" for="priority" :messages="$errors->get('priority')" />
                        </div>

                        <div>
                            <x-komun.label for="status" :value="__('Status')" />
                            <x-komun.select
                                id="status"
                                name="status"
                                :options="[
                                    'pending' => 'Pendiente',
                                    'in_progress' => 'En Progreso',
                                    'completed' => 'Completada',
                                    'cancelled' => 'Cancelada'
                                ]"
                                :selected="old('status', $requestModel->status)"
                                required
                            />

                            <x-input-error class="mt-2" for="status" :messages="$errors->get('status')" />
                        </div>

                        <div>
                            <x-komun.label for="deadline" :value="__('Deadline')" />
                            <x-komun.text-input id="deadline" name="deadline" type="date" class="mt-1 block w-full" :value="old('deadline', $requestModel->deadline->format('Y-m-d'))" required />
                            <x-input-error class="mt-2"  for="deadline" :messages="$errors->get('deadline')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <button>{{ __('Update Request') }}</button>
                            <a href="{{ route('god.requests.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
