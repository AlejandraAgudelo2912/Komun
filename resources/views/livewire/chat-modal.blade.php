<div x-data="{ show: @entangle('show') }" x-cloak>
    @if($show)
        <div
            x-show="show"
            x-transition.opacity.duration.300ms
            class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center px-4 py-6 sm:p-6"
        >
            <div
                x-show="show"
                x-transition.duration.300ms
                x-transition.scale.origin.center
                class="bg-white w-full max-w-2xl max-h-[90vh] rounded-xl shadow-2xl overflow-hidden relative flex flex-col"
            >
                <div class="flex justify-between items-center px-6 py-4 border-b">
                    <h3 class="text-xl font-semibold text-gray-800">
                        Chat con {{ $receiver?->name }}
                        @if($requestModel)
                            <span class="text-sm text-gray-500">– {{ $requestModel->title }}</span>
                        @endif
                    </h3>
                    <button
                        @click="show = false"
                        wire:click="closeModal"
                        class="text-gray-400 hover:text-gray-600 transition-colors"
                        aria-label="Cerrar"
                    >
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto px-6 py-4 space-y-2">
                    @if($receiver)
                        <livewire:chat
                            :receiver="$receiver"
                            :requestModel="$requestModel"
                            :wire:key="'chat-' . $receiver->id"
                        />
                    @else
                        <div class="text-center text-gray-500">Cargando conversación...</div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

@push('styles')
    <style>
        [x-cloak] { display: none !important; }
    </style>
@endpush
