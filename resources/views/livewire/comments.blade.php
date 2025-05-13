<div>
    <!-- Botón para abrir el modal -->
    <button wire:click="createComment" class="px-4 py-2 bg-blue-500 text-white rounded">
        Enviar comentario
    </button>

    <!-- Modal -->
    @if ($showModal)
        <div class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-75 z-50">
            <div class="bg-white p-6 rounded shadow-md w-full max-w-md">
                <h2 class="text-lg font-semibold mb-4">
                    {{ $editingCommentId ? 'Editar Comentario' : 'Nuevo Comentario' }}
                </h2>

                <form wire:submit.prevent="saveComment">
                    <textarea
                        wire:model.defer="commentBody"
                        class="w-full border rounded p-2 focus:outline-none focus:ring focus:border-blue-300"
                        rows="4"
                        placeholder="Escribe tu comentario...">
                    </textarea>

                    <div class="mt-4 flex justify-end gap-2">
                        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded">
                            {{ $editingCommentId ? 'Actualizar' : 'Enviar' }}
                        </button>
                        <button type="button" wire:click="closeModal" class="px-4 py-2 bg-gray-300 text-black rounded">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Lista de comentarios -->
    <div class="space-y-4 mt-4">
        @forelse($comments as $comment)
            <div class="p-4 border rounded shadow-sm bg-white">
                <div class="flex justify-between items-center">
                    <strong>{{ $comment->user->name }}</strong>
                    <small class="text-gray-500">{{ $comment->created_at->diffForHumans() }}</small>
                </div>
                <p class="mt-2">{{ $comment->body }}</p>

                <div class="mt-2 flex gap-2 text-sm text-blue-600">
                    <button wire:click="editComment({{ $comment->id }})">Editar</button>
                    <button wire:click="deleteComment({{ $comment->id }})">Eliminar</button>
                </div>
            </div>
        @empty
            <p class="text-gray-500">No hay comentarios todavía.</p>
        @endforelse
    </div>
</div>
