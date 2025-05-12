<h2 class="text-xl font-bold mb-4">Solicitantes</h2>
<livewire:request-applicants-manager :requestModel="$requestModel" />

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
        </tr>
    @endforeach
    </tbody>
</table>
