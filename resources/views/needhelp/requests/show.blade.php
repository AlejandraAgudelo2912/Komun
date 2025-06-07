<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Request Details') }}
            </h2>
            <x-welcome-button />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Detalles de la solicitud -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">{{ $requestModel->title }}</h3>
                        <p class="text-gray-600 mb-4">{{ $requestModel->description }}</p>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Categoría</p>
                                <p class="font-medium">{{ $requestModel->category?->name ?? 'Sin categoría' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Ubicación</p>
                                <p class="font-medium">{{ $requestModel->location }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">{{__('Deadline')}}</p>
                                <p class="font-medium">
                                    {{ $requestModel->deadline?->format('d/m/Y') ?? 'Sin fecha límite' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Estado</p>
                                <p class="font-medium">{{ ucfirst($requestModel->status) }}</p>
                            </div>
                        </div>

                        <!-- Sección de Reseñas -->
                        @if($requestModel->user_id === auth()->id())
                            @if($requestModel->status === 'completed')
                                <div class="mt-8">
                                    <h3 class="text-lg font-semibold mb-4">Reseñas de Asistentes</h3>
                                    @if($requestModel->reviews->isEmpty())
                                        <p class="text-gray-500">No hay reseñas disponibles.</p>
                                    @else
                                        <div class="space-y-4">
                                            @foreach($requestModel->reviews as $review)
                                                <div class="p-4 bg-gray-50 rounded-lg">
                                                    <div class="flex justify-between items-start mb-2">
                                                        <div>
                                                            <h4 class="font-medium">{{ $review->assistant->name }}</h4>
                                                            <div class="flex items-center mt-1">
                                                                <div class="flex text-yellow-400">
                                                                    @for($i = 1; $i <= 5; $i++)
                                                                        <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                                        </svg>
                                                                    @endfor
                                                                </div>
                                                                <span class="ml-2 text-gray-600">{{ $review->rating }}/5</span>
                                                            </div>
                                                        </div>
                                                        <div class="flex items-center space-x-2">
                                                            <span class="text-sm text-gray-500">{{ $review->created_at->format('d/m/Y') }}</span>
                                                            <a href="{{ route('needhelp.reviews.edit', $review) }}" 
                                                               class="text-blue-500 hover:text-blue-700">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                                </svg>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    @if($review->comment)
                                                        <p class="text-gray-600 mt-2">{{ $review->comment }}</p>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    @if($requestModel->status === 'completed' && $requestModel->applicants()->wherePivot('status', 'accepted')->whereDoesntHave('reviews', function($query) use ($requestModel) {
                                        $query->where('request_models_id', $requestModel->id)
                                              ->where('user_id', auth()->id());
                                    })->exists())
                                        <div class="mt-4">
                                            <a href="{{ route('needhelp.reviews.create', $requestModel) }}" 
                                               class="inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600 focus:bg-green-600 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                                </svg>
                                                Calificar Asistentes
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @endif
                    </div>

                    <!-- Aplicaciones -->
                    @if($requestModel->user_id === auth()->id())
                        <div class="mt-8">
                            <h3 class="text-lg font-semibold mb-4">{{__('Applications Received')}}</h3>
                            @if($requestModel->applicants->isEmpty())
                                <p class="text-gray-500">{{__('There are no pending applications.')}}</p>
                            @else
                                <div class="space-y-4">
                                    @foreach($requestModel->applicants as $applicant)
                                        <livewire:request-applicant-manager
                                            :requestModel="$requestModel"
                                            :applicant="$applicant"
                                            :key="'applicant-'.$applicant->id" />
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @else
                        <!-- Botón para chatear con el solicitante -->
                        <div class="mt-8">
                            <button
                                wire:click="$dispatch('openChatModal', [{{ $requestModel->user_id }}, {{ $requestModel->id }}])"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">
                                Chatear con el solicitante
                            </button>
                        </div>
                    @endif

                    <!-- Botón para aplicar (solo para asistentes) -->
                    @if(auth()->user()->hasRole('assistant') && $request->status === 'pending')
                        <div class="mt-8">
                            <h3 class="text-lg font-semibold mb-4">Aplicar a esta solicitud</h3>
                            <form action="{{ route('assistant.requests.apply', $request) }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="message" class="block text-sm font-medium text-gray-700">Mensaje</label>
                                    <textarea name="message" id="message" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required></textarea>
                                </div>
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                    Aplicar
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Componente de Chat Modal -->
    <livewire:chat-modal />
</x-app-layout>
