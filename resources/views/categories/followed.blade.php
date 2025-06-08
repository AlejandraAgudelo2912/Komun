<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mis Categorías Seguidas') }}
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

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    @if($categories->isEmpty())
                        <p class="text-gray-500 text-center py-4">No sigues ninguna categoría todavía.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($categories as $category)
                                <div class="border rounded-lg p-4 {{ $category->pivot->notifications_enabled ? 'bg-blue-50' : 'bg-gray-50' }}">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="text-lg font-semibold" style="color: {{ $category->color }}">
                                            {{ $category->name }}
                                        </h3>
                                        <span class="text-sm {{ $category->pivot->notifications_enabled ? 'text-blue-600' : 'text-gray-500' }}">
                                            {{ $category->pivot->notifications_enabled ? 'Notificaciones activadas' : 'Notificaciones desactivadas' }}
                                        </span>
                                    </div>
                                    
                                    <p class="text-gray-600 text-sm mb-4">{{ $category->description }}</p>
                                    
                                    <div class="flex space-x-2">
                                        <form action="{{ route('categories.unfollow', $category) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm px-3 py-1 rounded bg-red-100 text-red-700 hover:bg-red-200">
                                                Dejar de seguir
                                            </button>
                                        </form>
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