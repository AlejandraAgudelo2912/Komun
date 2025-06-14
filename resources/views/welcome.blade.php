<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="antialiased">
    <div class="relative min-h-screen bg-gray-100">
        <!-- Header -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center">
                    <h1 class="text-3xl font-bold text-gray-900">{{ config('app.name', 'Laravel') }}</h1>
                    <div class="space-x-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900">{{ __('Dashboard') }}</a>
                            @else
                                <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900">{{ __('Login') }}</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="ml-4 text-gray-700 hover:text-gray-900">{{ __('Register') }}</a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </header>

        <!-- Quick Navigation Section -->
        @auth
        <div class="bg-white py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('Quick Navigation') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @if(auth()->user()->hasRole('needHelp'))
                        <!-- Crear Solicitud -->
                        <a href="{{ route('needhelp.requests.create') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <i class="fas fa-plus-circle text-blue-600 text-2xl mr-3"></i>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ __('Create Request') }}</h3>
                                <p class="text-sm text-gray-600">{{ __('Publish a new help request') }}</p>
                            </div>
                        </a>

                        <!-- Ver Mis Solicitudes -->
                        <a href="{{ route('needhelp.requests.index') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                            <i class="fas fa-list text-green-600 text-2xl mr-3"></i>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ __('My Requests') }}</h3>
                                <p class="text-sm text-gray-600">{{ __('Manage your requests') }}</p>
                            </div>
                        </a>

                        <!-- Ver Categorías -->
                        <a href="{{ route('needhelp.categories.index') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                            <i class="fas fa-tags text-purple-600 text-2xl mr-3"></i>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ __('Categories') }}</h3>
                                <p class="text-sm text-gray-600">{{ __('Explore by categories') }}</p>
                            </div>
                        </a>
                    @endif

                    @if(auth()->user()->hasRole('assistant'))
                        <!-- Ver Solicitudes Disponibles -->
                        <a href="{{ route('assistant.requests.index') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                            <i class="fas fa-list text-green-600 text-2xl mr-3"></i>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ __('Available Requests') }}</h3>
                                <p class="text-sm text-gray-600">{{ __('Explore requests to help') }}</p>
                            </div>
                        </a>

                        <!-- Ver Categorías -->
                        <a href="{{ route('assistant.categories.index') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                            <i class="fas fa-tags text-purple-600 text-2xl mr-3"></i>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ __('Categories') }}</h3>
                                <p class="text-sm text-gray-600">{{ __('Explore by categories') }}</p>
                            </div>
                        </a>
                    @endif

                    @if(auth()->user()->hasRole('verificator'))
                        <!-- Ver Solicitudes -->
                        <a href="{{ route('verificator.requests.index') }}" class="flex items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                            <i class="fas fa-check-circle text-yellow-600 text-2xl mr-3"></i>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ __('Verify Requests') }}</h3>
                                <p class="text-sm text-gray-600">{{ __('Review and verify requests') }}</p>
                            </div>
                        </a>

                        <!-- Ver Categorías -->
                        <a href="{{ route('verificator.categories.index') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                            <i class="fas fa-tags text-purple-600 text-2xl mr-3"></i>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ __('Categories') }}</h3>
                                <p class="text-sm text-gray-600">{{ __('Manage categories') }}</p>
                            </div>
                        </a>

                        <!-- Ver Verificaciones -->
                        <a href="{{ route('verificator.verifications.index') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <i class="fas fa-user-check text-blue-600 text-2xl mr-3"></i>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ __('Verifications') }}</h3>
                                <p class="text-sm text-gray-600">{{ __('Manage assistant verifications') }}</p>
                            </div>
                        </a>
                    @endif

                    @if(auth()->user()->hasRole('admin'))
                        <!-- Ver Solicitudes -->
                        <a href="{{ route('admin.requests.index') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <i class="fas fa-list text-blue-600 text-2xl mr-3"></i>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ __('Requests') }}</h3>
                                <p class="text-sm text-gray-600">{{ __('Manage all requests') }}</p>
                            </div>
                        </a>

                        <!-- Ver Categorías -->
                        <a href="{{ route('admin.categories.index') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                            <i class="fas fa-tags text-purple-600 text-2xl mr-3"></i>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ __('Categories') }}</h3>
                                <p class="text-sm text-gray-600">{{ __('Manage categories') }}</p>
                            </div>
                        </a>

                        <!-- Ver Perfiles -->
                        <a href="{{ route('admin.profiles.index') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                            <i class="fas fa-users text-green-600 text-2xl mr-3"></i>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ __('Profiles') }}</h3>
                                <p class="text-sm text-gray-600">{{ __('Manage user profiles') }}</p>
                            </div>
                        </a>
                    @endif

                    @if(auth()->user()->hasRole('god'))
                        <!-- Ver Solicitudes -->
                        <a href="{{ route('god.requests.index') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <i class="fas fa-list text-blue-600 text-2xl mr-3"></i>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ __('Requests') }}</h3>
                                <p class="text-sm text-gray-600">{{ __('Manage all requests') }}</p>
                            </div>
                        </a>

                        <!-- Ver Categorías -->
                        <a href="{{ route('god.categories.index') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                            <i class="fas fa-tags text-purple-600 text-2xl mr-3"></i>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ __('Categories') }}</h3>
                                <p class="text-sm text-gray-600">{{ __('Manage categories') }}</p>
                            </div>
                        </a>

                        <!-- Ver Perfiles -->
                        <a href="{{ route('god.profiles.index') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                            <i class="fas fa-users text-green-600 text-2xl mr-3"></i>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ __('Profiles') }}</h3>
                                <p class="text-sm text-gray-600">{{ __('Manage user profiles') }}</p>
                            </div>
                        </a>
                    @endif

                    @if(auth()->user()->hasRole(['admin', 'god']))
                        <div class="mt-8 bg-white p-6 rounded-lg shadow-md">
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">{{ __('User Management') }}</h3>
                            <div class=" md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <!-- Gestión de Asistentes -->
                                <a href="{{ route('admin.profiles.index', ['role' => 'assistant']) }}" 
                                   class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                    <i class="fas fa-user-check text-blue-600 text-2xl mr-3"></i>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ __('Manage Assistants') }}</h4>
                                        <p class="text-sm text-gray-600">{{ __('View and manage assistant profiles') }}</p>
                                    </div>
                                </a>

                                <!-- Gestión de Verificadores -->
                                <a href="{{ route('admin.profiles.index', ['role' => 'verificator']) }}" 
                                   class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                                    <i class="fas fa-user-shield text-purple-600 text-2xl mr-3"></i>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ __('Manage Verificators') }}</h4>
                                        <p class="text-sm text-gray-600">{{ __('View and manage verificator profiles') }}</p>
                                    </div>
                                </a>

                                <!-- Gestión de Usuarios Necesitados -->
                                <a href="{{ route('admin.profiles.index', ['role' => 'needHelp']) }}" 
                                   class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                                    <i class="fas fa-hands-helping text-green-600 text-2xl mr-3"></i>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ __('Manage Users') }}</h4>
                                        <p class="text-sm text-gray-600">{{ __('View and manage user profiles') }}</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Mi Perfil (visible para todos los roles) -->
                    <a href="{{ route('profile.show') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="fas fa-user text-gray-600 text-2xl mr-3"></i>
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ __('My Profile') }}</h3>
                            <p class="text-sm text-gray-600">{{ __('Manage your profile') }}</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        @endauth

        <!-- Hero Section -->
        <div class="relative py-16 bg-white overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-4xl font-extrabold text-gray-900 sm:text-5xl sm:tracking-tight lg:text-6xl">
                        {{ __('Welcome to Komun') }}
                    </h2>
                    <p class="mt-5 max-w-xl mx-auto text-xl text-gray-500">
                        {{ __('Your community help platform where everyone can contribute and receive support.') }}
                    </p>
                    <div class="mt-8">
                        @auth
                            @if(auth()->user()->hasRole('assistant'))
                                <a href="{{ route('assistant.dashboard') }}"
                                class="inline-block bg-green-600 hover:bg-green-700 text-white text-lg font-semibold px-6 py-3 rounded-lg shadow-md transition">
                                    {{ __('You are already a helper') }}
                                </a>
                            @else
                                <a href="{{ route('assistant.form') }}"
                                class="inline-block bg-blue-600 hover:bg-blue-700 text-white text-lg font-semibold px-6 py-3 rounded-lg shadow-md transition">
                                    {{ __('Do you want to help people?') }}
                                </a>
                            @endif
                        @else
                            <a href="{{ route('assistant.form') }}"
                            class="inline-block bg-blue-600 hover:bg-blue-700 text-white text-lg font-semibold px-6 py-3 rounded-lg shadow-md transition">
                                {{ __('Do you want to help people?') }}
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="py-12 bg-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="text-blue-600 text-4xl mb-4">
                            <i class="fas fa-hands-helping"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">{{ __('Community Help') }}</h3>
                        <p class="text-gray-600">
                            {{ __('Connect with people who need help and those who can offer it.') }}
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="text-green-600 text-4xl mb-4">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">{{ __('User Verification') }}</h3>
                        <p class="text-gray-600">
                            {{ __('Verification system to ensure the safety of all users.') }}
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="text-purple-600 text-4xl mb-4">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">{{ __('Active Community') }}</h3>
                        <p class="text-gray-600">
                            {{ __('Join an active and committed community focused on mutual support.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="bg-blue-600">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
                <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                    <span class="block">{{ __('Ready to start?') }}</span>
                    <span class="block text-blue-200">{{ __('Join our community today.') }}</span>
                </h2>
                <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0">
                    <div class="inline-flex rounded-md shadow">
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50">
                            {{ __('Register') }}
                        </a>
                    </div>
                    <div class="ml-3 inline-flex rounded-md shadow">
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-500 hover:bg-blue-400">
                            {{ __('Login') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-800">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <div class="text-center text-gray-400">
                    <p>&copy; {{ date('Y') }} Komun. {{ __('All rights reserved.') }}</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
