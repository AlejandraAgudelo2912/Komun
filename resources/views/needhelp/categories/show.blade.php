<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Categoría') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h1 class="text-2xl font-bold mb-2">{{ $category->name }}</h1>
                            <p class="text-gray-600">{{ $category->description }}</p>
                        </div>

                        @auth
                            @if(auth()->user()->followedCategories()->where('category_id', $category->id)->exists())
                                <div class="flex space-x-2">

                                    <form action="{{ route('categories.unfollow', $category) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-4 py-2 bg-red-100 text-red-700 rounded hover:bg-red-200">
                                            Dejar de seguir
                                        </button>
                                    </form>
                                </div>
                            @else
                                <form action="{{ route('categories.follow', $category) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-green-100 text-green-700 rounded hover:bg-green-200">
                                        Seguir categoría
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>

                    <div class="mt-8">
                        <h2 class="text-xl font-semibold mb-4">Solicitudes en esta categoría</h2>
                        @if($category->requests->isEmpty())
                            <p class="text-gray-500">No hay solicitudes en esta categoría todavía.</p>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($category->requests as $request)
                                    <div class="border rounded-lg p-4 hover:shadow-lg transition-shadow duration-300">
                                        <h3 class="font-semibold mb-2">{{ $request->title }}</h3>
                                        <p class="text-gray-600 text-sm mb-2">{{ Str::limit($request->description, 100) }}</p>
                                        <div class="flex justify-between items-center text-sm text-gray-500">
                                            <span>{{ $request->created_at->diffForHumans() }}</span>
                                            <a href="{{ route('needhelp.requests.show', $request) }}" class="text-blue-600 hover:text-blue-800">
                                                Ver detalles
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
