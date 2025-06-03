<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Verificaciones') }}
        </h2>
    </x-slot>

    @foreach ($verifications as $verification)
        <div class="border p-4 my-4">
            <p><strong>Asistente:</strong> {{ $verification->assistant->name }}</p>
            <p><a href="{{ asset('storage/' . $verification->dni_front_path) }}" target="_blank">Ver DNI frontal</a></p>
            <p><a href="{{ asset('storage/' . $verification->dni_back_path) }}" target="_blank">Ver DNI trasero</a></p>
            <p><a href="{{ asset('storage/' . $verification->selfie_path) }}" target="_blank">Ver Selfie</a></p>

            <form method="POST" action="{{ route('verificator.verifications.approve', $verification->id) }}">
                @csrf
                <button type="submit" class="bg-green-500 text-white px-2 py-1">Aprobar</button>
            </form>

            <form method="POST" action="{{ route('verificator.verifications.reject', $verification->id) }}">
                @csrf
                <input type="text" name="reason" placeholder="Motivo del rechazo" required>
                <button type="submit" class="bg-red-500 text-white px-2 py-1">Rechazar</button>
            </form>
        </div>
    @endforeach

</x-app-layout>
