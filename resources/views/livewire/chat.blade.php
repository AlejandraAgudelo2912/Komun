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
                    @if($editingMessageId === $message->id)
                        <form wire:submit.prevent="updateMessage" class="space-y-2">
                            <textarea 
                                wire:model="editingMessageText"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 text-gray-900"
                                rows="2"
                            ></textarea>
                            <div class="flex justify-end space-x-2">
                                <button type="button" 
                                        wire:click="cancelEdit"
                                        class="text-sm px-2 py-1 rounded bg-gray-200 text-gray-700 hover:bg-gray-300">
                                    Cancelar
                                </button>
                                <button type="submit"
                                        class="text-sm px-2 py-1 rounded bg-blue-600 text-white hover:bg-blue-700">
                                    Guardar
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="text-sm font-semibold mb-1">
                                    {{ $message->user_id === auth()->id() ? 'Tú' : $message->user->name }}
                                </div>
                                <p>{{ $message->message }}</p>
                                <div class="text-xs mt-1 opacity-75 flex items-center space-x-2">
                                    <span>{{ $message->created_at->format('H:i') }}</span>
                                    @if($message->edited_at)
                                        <span class="italic">(editado)</span>
                                    @endif
                                </div>
                            </div>
                            @if($message->user_id === auth()->id())
                                <div class="ml-2 flex space-x-1">
                                    <button wire:click="editMessage({{ $message->id }})"
                                            class="text-xs px-2 py-1 rounded bg-blue-600 text-white hover:bg-blue-700">
                                        Editar
                                    </button>
                                    <button wire:click="deleteMessage({{ $message->id }})"
                                            onclick="return confirm('¿Estás seguro de que quieres eliminar este mensaje?')"
                                            class="text-xs px-2 py-1 rounded bg-red-600 text-white hover:bg-red-700">
                                        Eliminar
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <form wire:submit.prevent="sendMessage" class="p-4 border-t">
        <div class="flex space-x-2">
            <input type="text" 
                   wire:model.live="message" 
                   wire:keydown.enter.prevent="sendMessage"
                   placeholder="Escribe tu mensaje..." 
                   class="flex-1 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                   autocomplete="off">
            <button type="submit" 
                    wire:loading.attr="disabled"
                    class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                <span wire:loading.remove>Enviar</span>
                <span wire:loading>Enviando...</span>
            </button>
        </div>
        @error('message') 
            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
        @enderror
        <div wire:loading wire:target="sendMessage" class="text-sm text-gray-500 mt-1">
            Enviando mensaje...
        </div>
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
