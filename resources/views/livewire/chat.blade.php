<div class="flex flex-col h-[500px]">
    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="flex-1 overflow-y-auto p-4 space-y-4" id="chat-messages">
        @foreach($messages as $message)
            <div class="flex {{ $message->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-[70%] rounded-lg px-4 py-2 {{ $message->user_id === auth()->id() ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800' }}">
                    <div class="text-sm font-semibold mb-1">
                        {{ $message->user_id === auth()->id() ? 'Tú' : $message->user->name }}
                    </div>
                    <p>{{ $message->message }}</p>
                    <div class="text-xs mt-1 opacity-75">
                        {{ $message->created_at->format('H:i') }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <form wire:submit.prevent="sendMessage" class="p-4 border-t">
        <div class="flex space-x-2">
            <input type="text" 
                   wire:model="message" 
                   placeholder="Escribe tu mensaje..." 
                   class="flex-1 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                   autocomplete="off">
            <button type="submit" 
                    class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Enviar
            </button>
        </div>
        @error('message') 
            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
        @enderror
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        const chatMessages = document.getElementById('chat-messages');
        chatMessages.scrollTop = chatMessages.scrollHeight;

        Livewire.on('message-sent', () => {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        });
    });
</script>
@endpush
