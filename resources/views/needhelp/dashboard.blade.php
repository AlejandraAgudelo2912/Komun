<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between px-2 sm:px-4">
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">
                {{ __('User Dashboard') }}
            </h2>
            <x-welcome-button />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Tarjetas de Estadísticas Principales -->
            <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-4 gap-6">
                <div class="bg-gradient-to-br from-blue-100 to-blue-200 text-gray-900 rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all transform hover:scale-[1.03]">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium">{{ __('Active Requests') }}</p>
                            <p class="text-4xl font-bold mt-1">{{ $activeRequests }}</p>
                            <p class="text-sm mt-2 text-gray-700">{{ $completedRequests }} {{ __('completed') }}</p>
                        </div>
                        <div class="p-3 rounded-full bg-blue-300/30">
                            <svg class="w-8 h-8 text-blue-700" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <!-- icon -->
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-emerald-100 to-emerald-200 text-gray-900 rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all transform hover:scale-[1.03]">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium">{{ __('Active Assistants') }}</p>
                            <p class="text-4xl font-bold mt-1">{{ $activeAssistants }}</p>
                            <p class="text-sm mt-2 text-gray-700">{{ $totalAssistants }} {{ __('total') }}</p>
                        </div>
                        <div class="p-3 rounded-full bg-emerald-300/30">
                            <svg class="w-8 h-8 text-emerald-700" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <!-- icon -->
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-amber-100 to-amber-200 text-gray-900 rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all transform hover:scale-[1.03]">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium">{{ __('Help Hours') }}</p>
                            <p class="text-4xl font-bold mt-1">{{ number_format($totalHours, 1) }}</p>
                            <p class="text-sm mt-2 text-gray-700">{{ number_format($hoursThisMonth, 1) }} {{ __('this month') }}</p>
                        </div>
                        <div class="p-3 rounded-full bg-amber-300/30">
                            <svg class="w-8 h-8 text-amber-700" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <!-- icon -->
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Contenedores de Estadísticas -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">{{ __('Monthly Activity') }}</h3>
                    <div class="h-64">
                        <canvas id="monthlyActivity"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">{{ __('Latest Requests') }}</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600 uppercase tracking-wide">{{ __('Title') }}</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600 uppercase tracking-wide">{{ __('Category') }}</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600 uppercase tracking-wide">{{ __('Status') }}</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600 uppercase tracking-wide">{{ __('Date') }}</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                            @foreach($latestRequests as $request)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 font-medium text-gray-800">{{ $request->title }}</td>
                                    <td class="px-4 py-3 text-gray-500">{{ $request->category->name }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                                            {{ $request->status === 'completed' ? 'bg-green-100 text-green-800' :
                                               ($request->status === 'in_progress' ? 'bg-blue-100 text-blue-800' :
                                               'bg-yellow-100 text-yellow-800') }}">
                                            {{ __(ucfirst($request->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-500">{{ $request->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const activityCtx = document.getElementById('monthlyActivity').getContext('2d');
        new Chart(activityCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($monthlyActivity->pluck('month')) !!},
                datasets: [{
                    label: '{{ __('Requests') }}',
                    data: {!! json_encode($monthlyActivity->pluck('requests')) !!},
                    borderColor: '#3B82F6',
                    tension: 0.4,
                    fill: false
                }, {
                    label: '{{ __('Hours') }}',
                    data: {!! json_encode($monthlyActivity->pluck('hours')) !!},
                    borderColor: '#F59E0B',
                    tension: 0.4,
                    fill: false
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
                    borderRadius: 4,
                    barThickness: 20
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
