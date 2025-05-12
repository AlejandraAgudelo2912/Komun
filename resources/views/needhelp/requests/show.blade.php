<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalle de la Solicitud') }}
            </h2>
            <x-welcome-button />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Detalles de la solicitud -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">{{ $request->title }}</h3>
                        <p class="text-gray-600 mb-4">{{ $request->description }}</p>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Categoría</p>
                                <p class="font-medium">{{ $request->category->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Ubicación</p>
                                <p class="font-medium">{{ $request->location }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Fecha límite</p>
                                <p class="font-medium">{{ $request->deadline->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Estado</p>
                                <p class="font-medium">{{ ucfirst($request->status) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Aplicaciones -->
                    @if($request->user_id === auth()->id())
                        <div class="mt-8">
                            <h3 class="text-lg font-semibold mb-4">Aplicaciones Recibidas</h3>
                            @if($request->applicants->isEmpty())
                                <p class="text-gray-500">No hay aplicaciones pendientes.</p>
                            @else
                                <div class="space-y-4">
                                    @foreach($request->applicants as $applicant)
                                        <div class="border rounded-lg p-4 {{ $applicant->pivot->status === 'pending' ? 'bg-yellow-50' : ($applicant->pivot->status === 'accepted' ? 'bg-green-50' : 'bg-red-50') }}">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <p class="font-medium">{{ $applicant->name }}</p>
                                                    <p class="text-sm text-gray-600">{{ $applicant->pivot->message }}</p>
                                                    <p class="text-sm text-gray-500">Aplicado el {{ $applicant->pivot->created_at->format('d/m/Y H:i') }}</p>
                                                </div>
                                                @if($applicant->pivot->status === 'pending')
                                                    <div class="flex space-x-2">
                                                        <form action="{{ route('needhelp.requests.applications.respond', ['request' => $request, 'applicant' => $applicant]) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="accepted">
                                                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                                                Aceptar
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('needhelp.requests.applications.respond', ['request' => $request, 'applicant' => $applicant]) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="rejected">
                                                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                                                                Rechazar
                                                            </button>
                                                        </form>
                                                    </div>
                                                @else
                                                    <span class="px-3 py-1 rounded text-sm {{ $applicant->pivot->status === 'accepted' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ $applicant->pivot->status === 'accepted' ? 'Aceptado' : 'Rechazado' }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
