<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard de Verificador') }}
            </h2>
            <x-welcome-button />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tarjetas de Estadísticas Principales -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 p-6 rounded-lg shadow-lg text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-75">Solicitudes por Verificar</p>
                            <p class="text-3xl font-bold">{{ $pendingVerifications }}</p>
                        </div>
                        <i class="fas fa-clipboard-check text-3xl opacity-75"></i>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm opacity-75">Verificadas hoy: {{ $verifiedToday }}</p>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-teal-500 to-teal-600 p-6 rounded-lg shadow-lg text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-75">Verificaciones Completadas</p>
                            <p class="text-3xl font-bold">{{ $completedVerifications }}</p>
                        </div>
                        <i class="fas fa-check-circle text-3xl opacity-75"></i>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm opacity-75">Este mes: {{ $verificationsThisMonth }}</p>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-6 rounded-lg shadow-lg text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-75">Asistentes Verificados</p>
                            <p class="text-3xl font-bold">{{ $verifiedAssistants }}</p>
                        </div>
                        <i class="fas fa-user-check text-3xl opacity-75"></i>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm opacity-75">Pendientes: {{ $pendingAssistants }}</p>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-pink-500 to-pink-600 p-6 rounded-lg shadow-lg text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-75">Tiempo Promedio</p>
                            <p class="text-3xl font-bold">{{ $averageVerificationTime }}</p>
                        </div>
                        <i class="fas fa-clock text-3xl opacity-75"></i>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm opacity-75">Última verificación: {{ $lastVerificationTime }}</p>
                    </div>
                </div>
            </div>

            <!-- Gráficos y Estadísticas Detalladas -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Gráfico de Verificaciones por Día -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Verificaciones por Día</h3>
                    <div class="h-64">
                        <canvas id="verificationsByDay"></canvas>
                    </div>
                </div>

                <!-- Gráfico de Tiempo de Verificación -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Tiempo de Verificación</h3>
                    <div class="h-64">
                        <canvas id="verificationTime"></canvas>
                    </div>
                </div>

                <!-- Últimas Verificaciones -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Últimas Verificaciones</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Asistente</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($latestVerifications as $verification)
                                <tr>
                                    <td class="px-4 py-2">
                                        <div class="flex items-center">
                                            <img src="{{ $verification->assistant->profile_photo_url }}" alt="{{ $verification->assistant->name }}" class="w-8 h-8 rounded-full mr-2">
                                            <span class="text-sm text-gray-900">{{ $verification->assistant->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $verification->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                               ($verification->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                               'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($verification->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-500">{{ $verification->created_at->diffForHumans() }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Estadísticas de Verificación -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Estadísticas de Verificación</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm text-gray-600">Tasa de Aprobación</p>
                                <p class="text-2xl font-bold text-green-600">{{ $approvalRate }}%</p>
                            </div>
                            <i class="fas fa-chart-line text-3xl text-green-500"></i>
                        </div>
                        <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm text-gray-600">Tasa de Rechazo</p>
                                <p class="text-2xl font-bold text-red-600">{{ $rejectionRate }}%</p>
                            </div>
                            <i class="fas fa-chart-bar text-3xl text-red-500"></i>
                        </div>
                        <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm text-gray-600">Verificaciones Pendientes</p>
                                <p class="text-2xl font-bold text-yellow-600">{{ $pendingVerifications }}</p>
                            </div>
                            <i class="fas fa-clock text-3xl text-yellow-500"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Gráfico de Verificaciones por Día
        const verificationsCtx = document.getElementById('verificationsByDay').getContext('2d');
        new Chart(verificationsCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($verificationsByDay->pluck('day')) !!},
                datasets: [{
                    label: 'Verificaciones',
                    data: {!! json_encode($verificationsByDay->pluck('count')) !!},
                    backgroundColor: '#4F46E5',
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

        // Gráfico de Tiempo de Verificación
        const timeCtx = document.getElementById('verificationTime').getContext('2d');
        new Chart(timeCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($verificationTime->pluck('date')) !!},
                datasets: [{
                    label: 'Tiempo Promedio (minutos)',
                    data: {!! json_encode($verificationTime->pluck('time')) !!},
                    borderColor: '#EC4899',
                    tension: 0.4,
                    fill: true,
                    backgroundColor: 'rgba(236, 72, 153, 0.1)'
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
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Minutos'
                        }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout> 