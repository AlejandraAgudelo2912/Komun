<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Application Details') }}
            </h2>

            <x-welcome-button />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-2xl font-bold">{{ $requestModel->title }}</h3>
                            <span class="px-3 py-1 rounded text-sm bg-yellow-100 text-yellow-800">
                                Pendiente
                            </span>
                        </div>

                        @php
                            $hasApplied = $requestModel->applicants->contains(auth()->id());
                        @endphp

                        <p class="text-gray-600 mb-6">{{ $requestModel->description }}</p>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            <div>
                                <p class="text-gray-500">Categoría</p>
                                <p class="font-medium">{{ $requestModel->category?->name ?? 'Sin categoría' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Ubicación</p>
                                <p class="font-medium">{{ $requestModel->location }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Fecha límite</p>
                                <p class="font-medium">{{ $requestModel->deadline ? $requestModel->deadline->format('d/m/Y') : 'Sin fecha límite' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Solicitante</p>
                                <p class="font-medium">{{ $requestModel->user?->name ?? 'Usuario no disponible' }}</p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('assistant.requests.apply', ['requestModel' => $requestModel->id]) }}" class="space-y-4">
                            @csrf

                            <textarea name="message" id="message" rows="4"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                      required {{ $hasApplied ? 'disabled' : '' }}></textarea>

                            <button type="submit"
                                    class="bg-blue-500 text-black px-4 py-2 rounded hover:bg-blue-600 {{ $hasApplied ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ $hasApplied ? 'disabled' : '' }}>
                                Aplicar a esta solicitud
                            </button>
                        </form>

                        @if ($hasApplied)
                            <div class="mb-4 text-green-600 font-semibold">
                                Ya has aplicado a esta solicitud.
                            </div>
                        @endif

                        @if ($requestModel->user_id !== auth()->id())
                            <button
                                wire:click="$dispatch('openChatModal', { receiverId: {{ $requestModel->user_id }}, requestModelId: {{ $requestModel->id }} })"
                                class="mt-4 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">
                                Iniciar chat con el solicitante
                            </button>
                        @endif

                        <livewire:comments :requestModel="$requestModel" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
