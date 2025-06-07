<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard de Asistente') }}
            </h2>
            <x-welcome-button />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tarjetas de Estadísticas Principales -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-gradient-to-br from-orange-500 to-orange-600 p-6 rounded-lg shadow-lg text-white">
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

                <div class="bg-gradient-to-br from-cyan-500 to-cyan-600 p-6 rounded-lg shadow-lg text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-75">Usuarios Asistidos</p>
                            <p class="text-3xl font-bold">{{ $assistedUsers }}</p>
                        </div>
                        <i class="fas fa-users text-3xl opacity-75"></i>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm opacity-75">Este mes: {{ $assistedUsersThisMonth }}</p>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 p-6 rounded-lg shadow-lg text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-75">Valoración Promedio</p>
                            <p class="text-3xl font-bold">{{ number_format($averageRating, 1) }} ★</p>
                        </div>
                        <i class="fas fa-star text-3xl opacity-75"></i>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm opacity-75">Total reseñas: {{ $totalReviews }}</p>
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

                <!-- Gráfico de Valoraciones -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Distribución de Valoraciones</h3>
                    <div class="h-64">
                        <canvas id="ratingDistribution"></canvas>
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

                <!-- Últimas Reseñas -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Últimas Reseñas</h3>
                    <div class="space-y-4">
                        @foreach($latestReviews as $review)
                        <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg">
                            <img src="{{ $review->user->profile_photo_url }}" alt="{{ $review->user->name }}" class="w-10 h-10 rounded-full">
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="font-medium text-gray-900">{{ $review->user->name }}</p>
                                    <div class="flex items-center">
                                        <span class="text-yellow-500">{{ str_repeat('★', $review->rating) }}</span>
                                        <span class="text-gray-400">{{ str_repeat('★', 5 - $review->rating) }}</span>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">{{ $review->comment }}</p>
                                <p class="text-xs text-gray-500 mt-2">{{ $review->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Estadísticas de Categorías -->
            <div class="mt-8">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Estadísticas por Categoría</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($categoryStats as $category)
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-medium text-gray-900">{{ $category->name }}</h4>
                                <span class="text-sm text-gray-500">{{ $category->count }} solicitudes</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($category->count / $maxCategoryCount) * 100 }}%"></div>
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
        // Gráfico de Actividad Mensual
        const activityCtx = document.getElementById('monthlyActivity').getContext('2d');
        new Chart(activityCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($monthlyActivity->pluck('month')) !!},
                datasets: [{
                    label: 'Solicitudes Atendidas',
                    data: {!! json_encode($monthlyActivity->pluck('requests')) !!},
                    borderColor: '#F97316',
                    tension: 0.4,
                    fill: true,
                    backgroundColor: 'rgba(249, 115, 22, 0.1)'
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

        // Gráfico de Distribución de Valoraciones
        const ratingCtx = document.getElementById('ratingDistribution').getContext('2d');
        new Chart(ratingCtx, {
            type: 'bar',
            data: {
                labels: ['1 ★', '2 ★', '3 ★', '4 ★', '5 ★'],
                datasets: [{
                    data: {!! json_encode($ratingDistribution) !!},
                    backgroundColor: [
                        '#EF4444', '#F97316', '#F59E0B', '#10B981', '#3B82F6'
                    ],
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout> 