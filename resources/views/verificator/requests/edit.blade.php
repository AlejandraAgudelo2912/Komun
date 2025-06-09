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
                    <form action="{{ route('verificator.requests.update', $requestModel) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <x-komun.input
                            name="title"
                            :label="__('Title')"
                            type="text"
                            :value="old('title', $requestModel->title)"
                            required
                            autofocus
                        />

                        <div>
                            <x-komun.label for="description" :value="__('Description')" required />
                            <textarea
                                id="description"
                                name="description"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                rows="4"
                                required
                            >{{ old('description', $requestModel->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <x-komun.select
                            name="category_id"
                            :label="__('Category')"
                            :options="$categories->pluck('name', 'id')"
                            :selected="old('category_id', $requestModel->category_id)"
                            required
                        />

                        <x-komun.select
                            name="priority"
                            :label="__('Priority')"
                            :options="[
                                'low' => __('Low'),
                                'medium' => __('Medium'),
                                'high' => __('High')
                            ]"
                            :selected="old('priority', $requestModel->priority)"
                            required
                        />

                        <x-komun.select
                            name="status"
                            :label="__('Status')"
                            :options="[
                                'pending' => __('Pending'),
                                'in_progress' => __('In Progress'),
                                'completed' => __('Completed'),
                                'cancelled' => __('Cancelled')
                            ]"
                            :selected="old('status', $requestModel->status)"
                            required
                        />

                        <x-komun.date-input
                            name="deadline"
                            :label="__('Deadline')"
                            :value="old('deadline', $requestModel->deadline->format('Y-m-d'))"
                            required
                        />

                        <div class="flex items-center gap-4">
                            <button>{{ __('Update Request') }}</button>
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
