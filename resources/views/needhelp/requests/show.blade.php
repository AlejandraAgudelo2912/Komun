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
