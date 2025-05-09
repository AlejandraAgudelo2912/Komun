<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Komun - Plataforma de Ayuda Comunitaria</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <div class="relative min-h-screen bg-gray-100">
        <!-- Header -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center">
                    <h1 class="text-3xl font-bold text-gray-900">Komun</h1>
                    <div class="space-x-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900">Iniciar Sesión</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="ml-4 text-gray-700 hover:text-gray-900">Registrarse</a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <div class="relative py-16 bg-white overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-4xl font-extrabold text-gray-900 sm:text-5xl sm:tracking-tight lg:text-6xl">
                        Bienvenido a Komun
                    </h2>
                    <p class="mt-5 max-w-xl mx-auto text-xl text-gray-500">
                        Tu plataforma de ayuda comunitaria donde todos podemos contribuir y recibir apoyo.
                    </p>
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
                        <h3 class="text-xl font-semibold mb-2">Ayuda Comunitaria</h3>
                        <p class="text-gray-600">
                            Conecta con personas que necesitan ayuda y aquellos que pueden ofrecerla.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="text-green-600 text-4xl mb-4">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Verificación de Usuarios</h3>
                        <p class="text-gray-600">
                            Sistema de verificación para garantizar la seguridad de todos los usuarios.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="text-purple-600 text-4xl mb-4">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Comunidad Activa</h3>
                        <p class="text-gray-600">
                            Únete a una comunidad activa y comprometida con el apoyo mutuo.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="bg-blue-600">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
                <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                    <span class="block">¿Listo para empezar?</span>
                    <span class="block text-blue-200">Únete a nuestra comunidad hoy.</span>
                </h2>
                <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0">
                    <div class="inline-flex rounded-md shadow">
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50">
                            Registrarse
                        </a>
                    </div>
                    <div class="ml-3 inline-flex rounded-md shadow">
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-500 hover:bg-blue-400">
                            Iniciar Sesión
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-800">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <div class="text-center text-gray-400">
                    <p>&copy; {{ date('Y') }} Komun. Todos los derechos reservados.</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
