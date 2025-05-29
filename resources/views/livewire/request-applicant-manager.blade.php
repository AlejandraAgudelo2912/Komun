<table class="w-full table-auto border">
    <thead>
    <tr>
        <th>Nombre</th>
        <th>Mensaje</th>
        <th>Estado</th>
        <th>Acciones</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($applicants as $user)
        <tr class="border-t">
            <td class="p-2">{{ $user->name }}</td>
            <td class="p-2">{{ $user->pivot->message }}</td>
            <td class="p-2 capitalize">{{ $user->pivot->status ?? 'pendiente' }}</td>
            <td class="p-2 space-x-2">
                @if ($user->pivot->status !== 'accepted')
                    <button wire:click="acceptApplicant({{ $user->id }})"
                            class="bg-green-500 text-white px-2 py-1 rounded">
                        Aceptar
                    </button>
                @endif

                @if ($user->pivot->status !== 'rejected')
                    <button wire:click="rejectApplicant({{ $user->id }})"
                            class="bg-red-500 text-white px-2 py-1 rounded">
                        Rechazar
                    </button>
                @endif
                    <button
                        wire:click="$dispatch('openChatModal', [{{ $user->id }}, {{ $requestModel->id }}])"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">
                        Enviar mensaje
                    </button>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
