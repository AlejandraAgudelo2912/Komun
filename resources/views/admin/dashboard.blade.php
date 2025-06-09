<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard de Administrador') }}
            </h2>
            <x-welcome-button />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tarjetas de Estadísticas Principales -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-6 rounded-lg shadow-lg text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-75">Usuarios Totales</p>
                            <p class="text-3xl font-bold">{{ $totalUsers }}</p>
                        </div>
                        <i class="fas fa-users text-3xl opacity-75"></i>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm opacity-75">Nuevos este mes: {{ $newUsersThisMonth }}</p>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 p-6 rounded-lg shadow-lg text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-75">Solicitudes Pendientes</p>
                            <p class="text-3xl font-bold">{{ $pendingRequests }}</p>
                        </div>
                        <i class="fas fa-clock text-3xl opacity-75"></i>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm opacity-75">Completadas: {{ $completedRequests }}</p>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-6 rounded-lg shadow-lg text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-75">Asistentes Activos</p>
                            <p class="text-3xl font-bold">{{ $activeAssistants }}</p>
                        </div>
                        <i class="fas fa-hands-helping text-3xl opacity-75"></i>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm opacity-75">Pendientes de verificación: {{ $pendingVerifications }}</p>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 p-6 rounded-lg shadow-lg text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-75">Verificadores Activos</p>
                            <p class="text-3xl font-bold">{{ $activeVerifiers }}</p>
                        </div>
                        <i class="fas fa-user-shield text-3xl opacity-75"></i>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm opacity-75">Verificaciones este mes: {{ $verificationsThisMonth }}</p>
                    </div>
                </div>
            </div>

            <!-- Gráficos y Estadísticas Detalladas -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Gráfico de Solicitudes por Categoría -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Solicitudes por Categoría</h3>
                    <div class="h-64">
                        <canvas id="requestsByCategory"></canvas>
                    </div>
                </div>

                <!-- Gráfico de Actividad Mensual -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Actividad Mensual</h3>
                    <div class="h-64">
                        <canvas id="monthlyActivity"></canvas>
                    </div>
                </div>

                <!-- Últimas Solicitudes -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Últimas Solicitudes</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Título</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($latestRequests as $request)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $request->title }}</td>
                                    <td class="px-4 py-2">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $request->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                               ($request->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 
                                               'bg-green-100 text-green-800') }}">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-500">{{ $request->created_at->diffForHumans() }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Asistentes Destacados -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Asistentes Destacados</h3>
                    <div class="space-y-4">
                        @foreach($topAssistants as $assistant)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <img src="{{ $assistant->profile_photo_url }}" alt="{{ $assistant->name }}" class="w-10 h-10 rounded-full">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $assistant->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $assistant->total_requests }} solicitudes atendidas</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">Estado: {{ $assistant->status }}</p>
                                <p class="text-sm text-gray-500">Verificado: {{ $assistant->is_verified ? 'Sí' : 'No' }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Gráfico de Solicitudes por Categoría
        const categoryCtx = document.getElementById('requestsByCategory').getContext('2d');
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($requestsByCategory->pluck('name')) !!},
                datasets: [{
                    data: {!! json_encode($requestsByCategory->pluck('count')) !!},
                    backgroundColor: [
                        '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6',
                        '#EC4899', '#14B8A6', '#F97316', '#6366F1', '#84CC16'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });

        // Gráfico de Actividad Mensual
        const activityCtx = document.getElementById('monthlyActivity').getContext('2d');
        new Chart(activityCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($monthlyActivity->pluck('month')) !!},
                datasets: [{
                    label: 'Total Solicitudes',
                    data: {!! json_encode($monthlyActivity->pluck('total')) !!},
                    borderColor: '#3B82F6',
                    tension: 0.4
                }, {
                    label: 'Solicitudes Completadas',
                    data: {!! json_encode($monthlyActivity->pluck('completed')) !!},
                    borderColor: '#10B981',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout> 