<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard de Necesitado') }}
            </h2>
            <x-welcome-button />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tarjetas de Estadísticas Principales -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-6 rounded-lg shadow-lg text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-75">Solicitudes Activas</p>
                            <p class="text-3xl font-bold">{{ $activeRequests }}</p>
                        </div>
                        <i class="fas fa-tasks text-3xl opacity-75"></i>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm opacity-75">Completadas: {{ $completedRequests }}</p>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-6 rounded-lg shadow-lg text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-75">Asistentes Ayudando</p>
                            <p class="text-3xl font-bold">{{ $activeAssistants }}</p>
                        </div>
                        <i class="fas fa-hands-helping text-3xl opacity-75"></i>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm opacity-75">Total asistentes: {{ $totalAssistants }}</p>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 p-6 rounded-lg shadow-lg text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-75">Horas de Ayuda</p>
                            <p class="text-3xl font-bold">{{ $totalHours }}</p>
                        </div>
                        <i class="fas fa-clock text-3xl opacity-75"></i>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm opacity-75">Este mes: {{ $hoursThisMonth }}</p>
                    </div>
                </div>
            </div>

            <!-- Gráficos y Estadísticas Detalladas -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Gráfico de Actividad Mensual -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Actividad Mensual</h3>
                    <div class="h-64">
                        <canvas id="monthlyActivity"></canvas>
                    </div>
                </div>

                <!-- Gráfico de Categorías -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Solicitudes por Categoría</h3>
                    <div class="h-64">
                        <canvas id="categoryDistribution"></canvas>
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

                <!-- Estadísticas de Asistentes -->
                <div class="mt-8">
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Top Asistentes</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach($topAssistants as $assistant)
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <img src="{{ $assistant->user->profile_photo_url }}" alt="{{ $assistant->user->name }}" class="w-12 h-12 rounded-full">
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $assistant->user->name }}</h4>
                                        <div class="flex items-center mt-1">
                                            <span class="text-yellow-500">{{ str_repeat('★', round($assistant->average_rating)) }}</span>
                                            <span class="text-sm text-gray-500 ml-1">({{ number_format($assistant->average_rating, 1) }})</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <p class="text-sm text-gray-600">{{ $assistant->total_requests }} solicitudes atendidas</p>
                                    <p class="text-sm text-gray-600">{{ $assistant->total_hours }} horas de ayuda</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Gráfico de Actividad Mensual
        const activityCtx = document.getElementById('monthlyActivity').getContext('2d');
        new Chart(activityCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($monthlyActivity->pluck('month')) !!},
                datasets: [{
                    label: 'Solicitudes Creadas',
                    data: {!! json_encode($monthlyActivity->pluck('requests')) !!},
                    borderColor: '#8B5CF6',
                    tension: 0.4,
                    fill: true,
                    backgroundColor: 'rgba(139, 92, 246, 0.1)'
                }, {
                    label: 'Horas de Ayuda',
                    data: {!! json_encode($monthlyActivity->pluck('hours')) !!},
                    borderColor: '#10B981',
                    tension: 0.4,
                    fill: true,
                    backgroundColor: 'rgba(16, 185, 129, 0.1)'
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

        // Gráfico de Distribución de Categorías
        const categoryCtx = document.getElementById('categoryDistribution').getContext('2d');
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($categoryStats->pluck('name')) !!},
                datasets: [{
                    data: {!! json_encode($categoryStats->pluck('count')) !!},
                    backgroundColor: [
                        '#8B5CF6', '#EC4899', '#F59E0B', '#10B981', '#3B82F6',
                        '#EF4444', '#F97316', '#84CC16', '#06B6D4', '#6366F1'
                    ],
                    borderWidth: 0
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
    </script>
    @endpush
</x-app-layout> 