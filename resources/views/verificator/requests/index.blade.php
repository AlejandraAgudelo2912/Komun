<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Requests') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">{{ __('Request List') }}</h3>
                        <a href="{{ route('verificator.requests.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Create New Request') }}
                        </a>
                    </div>

                    <form action="{{ route('verificator.requests.filter') }}" method="GET" class="mb-6 bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Búsqueda -->
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700">{{ __('Search') }}</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="{{ __('Search in title or description') }}">
                            </div>

                            <!-- Categoría -->
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700">{{ __('Category') }}</label>
                                <select name="category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">{{ __('All categories') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Estado -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">{{ __('Status') }}</label>
                                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">{{ __('All statuses') }}</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>{{ __('In Progress') }}</option>
                                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                                </select>
                            </div>

                            <!-- Ubicación -->
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700">{{ __('Location') }}</label>
                                <input type="text" name="location" id="location" value="{{ request('location') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="{{ __('Filter by location') }}">
                            </div>
                        </div>

                        <div class="mt-4 flex justify-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Filter') }}
                            </button>
                        </div>
                    </form>

                    @if($requests->isEmpty())
                        <p class="text-gray-500 text-center py-4">{{ __('No requests available at this time.') }}</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($requests as $request)
                                <div class="border rounded-lg p-4 {{ $request->status === 'pending' ? 'bg-yellow-50' : ($request->status === 'in_progress' ? 'bg-green-50' : 'bg-gray-50') }}">
                                    <div class="flex justify-between items-start mb-4">
                                        <h3 class="text-lg font-semibold">{{ $request->title }}</h3>
                                        <span class="px-3 py-1 rounded text-sm {{ $request->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($request->status === 'in_progress' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                            {{ __(ucfirst($request->status)) }}
                                        </span>
                                    </div>

                                    <p class="text-gray-600 mb-4 line-clamp-2">{{ $request->description }}</p>

                                    <div class="grid grid-cols-2 gap-2 text-sm mb-4">
                                        <div>
                                            <p class="text-gray-500">{{ __('Category') }}</p>
                                            <p class="font-medium">{{ $request->category->name }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">{{ __('Location') }}</p>
                                            <p class="font-medium">{{ $request->location }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">{{ __('Deadline') }}</p>
                                            <p class="font-medium">{{ $request->deadline->format('d/m/Y') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">{{ __('Applications') }}</p>
                                            <p class="font-medium">{{ $request->applicants->count() }}</p>
                                        </div>
                                    </div>

                                    <div class="flex justify-between items-center">
                                        <a href="{{ route('verificator.requests.show', $request) }}" class="text-blue-500 hover:text-blue-700">
                                            {{ __('View details') }}
                                        </a>
                                        @if($request->status === 'pending')
                                            <div class="flex space-x-2">
                                                <a href="{{ route('verificator.requests.edit', $request) }}" class="text-gray-500 hover:text-gray-700">
                                                    {{ __('Edit') }}
                                                </a>
                                                <form action="{{ route('verificator.requests.destroy', $request) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('{{ __('Are you sure you want to delete this request?') }}')">
                                                        {{ __('Delete') }}
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
