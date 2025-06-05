<x-app-layout>     
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Perfiles de Usuarios</h1>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form action="{{ route('admin.profiles.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Buscar por nombre o email" 
                           class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex gap-2">
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-search mr-2"></i>Buscar
                    </button>
                    @if(request()->has('search'))
                        <a href="{{ route('admin.profiles.index') }}" 
                           class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                            <i class="fas fa-times mr-2"></i>Limpiar
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">  
            @forelse ($users as $user)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-xl font-bold text-blue-600">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <h2 class="text-lg font-bold text-gray-800">{{ $user->name }}</h2>
                                    <p class="text-sm text-gray-600">{{ $user->email }}</p>
                                </div>
                            </div>
                            <div class="flex flex-col items-end">
                                <span class="text-xs text-gray-500">
                                    Registrado: {{ $user->created_at->format('d/m/Y') }}
                                </span>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <!-- Roles -->
                            <div>
                                <h3 class="text-sm font-semibold text-gray-700 mb-1">Roles</h3>
                                <div class="flex flex-wrap gap-1">
                                    @forelse($user->roles as $role)
                                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                                            {{ $role->name === 'admin' ? 'bg-purple-100 text-purple-800' : 
                                               ($role->name === 'assistant' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800') }}">
                                            {{ ucfirst($role->name) }}
                                        </span>
                                    @empty
                                        <span class="text-sm text-gray-500">Sin roles asignados</span>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Estado Asistente -->
                            @if($user->assistant)
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-700 mb-1">Estado Asistente</h3>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                                        {{ $user->assistant->status === 'active' ? 'bg-green-100 text-green-800' : 
                                           ($user->assistant->status === 'inactive' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($user->assistant->status) }}
                                    </span>
                                </div>

                                <!-- Verificación -->
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-700 mb-1">Verificación</h3>
                                    @if($user->assistant->verification)
                                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                                            {{ $user->assistant->verification->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                               ($user->assistant->verification->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($user->assistant->verification->status) }}
                                        </span>
                                        @if($user->assistant->verification->status === 'rejected' && $user->assistant->verification->rejection_reason)
                                            <p class="mt-1 text-xs text-red-600">
                                                Razón: {{ $user->assistant->verification->rejection_reason }}
                                            </p>
                                        @endif
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                            No verificado
                                        </span>
                                    @endif
                                </div>

                                <!-- Estadísticas del Asistente -->
                                <div class="grid grid-cols-2 gap-2 pt-2 border-t">
                                    <div class="text-center">
                                        <p class="text-xs text-gray-500">Valoración</p>
                                        <p class="text-lg font-semibold text-gray-800">
                                            {{ number_format($user->assistant->rating, 1) }}
                                            <span class="text-yellow-500">★</span>
                                        </p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs text-gray-500">Reseñas</p>
                                        <p class="text-lg font-semibold text-gray-800">
                                            {{ $user->assistant->total_reviews }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-lg shadow-md p-6 text-center">
                        <div class="text-gray-500 mb-2">
                            <i class="fas fa-users text-4xl"></i>
                        </div>
                        <p class="text-gray-600">No se encontraron usuarios</p>
                        @if(request()->has('search'))
                            <a href="{{ route('admin.profiles.index') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                                Volver a la lista completa
                            </a>
                        @endif
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    @push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @endpush
</x-app-layout>
