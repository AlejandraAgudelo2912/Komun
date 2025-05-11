<!-- vista para poner mensaje y enviar la solicitud -->
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Aplicar Solicitud') }}
            </h2>
            <x-welcome-button />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('assistant.requests.apply', $request) }}" class="space-y-4">
                        @csrf
                        <div class="mb-6">
                            <label for="message" class="block mb-2 text-sm font-medium text-gray-900">Escribe tu mensaje</label>
                            <textarea id="message" name="message" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Escribe tu mensaje aquí..." required></textarea>
                        </div>
                        <div class="mb-6">
                            <label for="proposed_price" class="block mb-2 text-sm font-medium text-gray-900">Precio propuesto</label>
                            <input type="number" id="proposed_price" name="proposed_price" class="block w-full p-2.5 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Escribe tu precio propuesto aquí..." required>
                        </div>
                        <div class="mb-6">
                            <label for="estimated_duration" class="block mb-2 text-sm font-medium text-gray-900">Duración estimada (horas)</label>
                            <input type="number" id="estimated_duration" name="estimated_duration" class="block w-full p-2.5 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Escribe la duración estimada aquí..." required>
                        </div>
                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Enviar Solicitud</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
