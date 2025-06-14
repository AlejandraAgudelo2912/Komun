<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Request') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('verificator.requests.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div>
                            <x-komun.label for="title" :value="__('Title')" />
                            <x-komun.text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required autofocus />
                            <x-input-error class="mt-2" for="title" :messages="$errors->get('title')" />
                        </div>

                        <div>
                            <x-komun.label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4" required>{{ old('description') }}</textarea>
                            <x-input-error class="mt-2" for="description" :messages="$errors->get('description')" />
                        </div>

                        <div>
                            <x-komun.label for="category_id" :value="__('Category')" />
                            <select id="category_id" name="category_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">{{ __('Select a category') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" for="category_id" :messages="$errors->get('category_id')" />
                        </div>

                        <div>
                            <x-komun.label for="location" :value="__('Location')" />
                            <x-komun.text-input id="location" name="location" type="text" class="mt-1 block w-full" :value="old('location')" required />
                            <x-input-error class="mt-2" for="location" :messages="$errors->get('location')" />
                        </div>

                        <div>
                            <x-komun.label for="priority" :value="__('Priority')" />
                            <select id="priority" name="priority" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>{{ __('Low') }}</option>
                                <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>{{ __('Medium') }}</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>{{ __('High') }}</option>
                            </select>
                            <x-input-error class="mt-2" for="priority" :messages="$errors->get('priority')" />
                        </div>

                        <div>
                            <x-komun.label for="deadline" :value="__('Deadline')" />
                            <x-komun.text-input id="deadline" name="deadline" type="date" class="mt-1 block w-full" :value="old('deadline')" required />
                            <x-input-error class="mt-2" for="deadline" :messages="$errors->get('deadline')" />
                        </div>

                        <div>
                            <x-komun.label for="verification_notes" :value="__('Verification Notes')" />
                            <textarea id="verification_notes" name="verification_notes" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('verification_notes') }}</textarea>
                            <x-input-error class="mt-2" for="verification_notes" :messages="$errors->get('verification_notes')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Create Request') }}</x-primary-button>
                            <a href="{{ route('verificator.requests.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
