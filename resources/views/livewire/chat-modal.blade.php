<div x-data="{ open: @entangle('show') }">
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center">
        
        <div x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="bg-white w-full max-w-2xl rounded-lg shadow-xl p-6 relative">
            
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">
                    Chat con {{ $receiver?->name }}
                    @if($requestModel)
                        - {{ $requestModel->title }}
                    @endif
                </h3>
                <button @click="open = false" wire:click="closeModal" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            @if($receiver)
                <livewire:chat :receiver="$receiver" :requestModel="$requestModel" :wire:key="'chat-' . $receiver->id" />
            @else
                <p class="text-center text-gray-600">Cargando conversación...</p>
            @endif
        </div>
    </div>
</div>
