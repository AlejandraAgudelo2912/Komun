<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Categorías') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @yield('content')

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($categories as $category)
                            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
                                <div class="flex items-center justify-between mb-4">
                                    @if($category->icon)
                                        <div class="text-2xl">
                                            <i class="{{ $category->icon }}"></i>
                                        </div>
                                    @endif
                                    @if($category->color)
                                        <div class="w-4 h-4 rounded-full" style="background-color: {{ $category->color }}"></div>
                                    @endif
                                </div>
                                
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                    {{ $category->name }}
                                </h3>
                                
                                @if($category->description)
                                    <p class="text-gray-600 text-sm mb-4">
                                        {{ $category->description }}
                                    </p>
                                @endif

                                @yield('category-actions')
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 