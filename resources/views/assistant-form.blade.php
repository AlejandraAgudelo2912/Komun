<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Assistant') }}
        </h2>
        <x-welcome-button />
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('assistant.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf

                        <!-- Biografía -->
                        <div>
                            <label for="bio" class="block text-sm font-medium text-gray-700">Biografía</label>
                            <textarea name="bio" id="bio" rows="4"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Cuéntanos un poco sobre ti">{{ old('bio', $assistant->bio ?? '') }}</textarea>
                        </div>

                        <!-- Disponibilidad -->
                        <div>
                            <label for="availability" class="block text-sm font-medium text-gray-700">Disponibilidad</label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @php
                                    $days = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes'];
                                @endphp
                                @foreach($days as $day)
                                    <div>
                                        <label for="availability_{{ $day }}" class="block text-xs font-medium text-gray-600">{{ ucfirst($day) }}</label>
                                        <input type="text" name="availability[{{ $day }}]" id="availability_{{ $day }}"
                                            value="{{ old("availability.$day", '') }}"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Ej. 9-13">
                                    </div>
                                @endforeach
                            </div>
                            <p class="mt-1 text-sm text-gray-500">
                                Ingresa los horarios en formato 24h (ej: 9-13, 14-18)
                            </p>
                        </div>

                        <!-- Habilidades -->
                        <div>
                            <label for="skills" class="block text-sm font-medium text-gray-700">Habilidades</label>
                            <input type="text" name="skills" id="skills"
                                value="{{ old('skills', '') }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Ej: cocina, primeros auxilios, costura">
                            <p class="mt-1 text-sm text-gray-500">
                                Separa las habilidades con comas
                            </p>
                        </div>

                        <!-- Años de Experiencia -->
                        <div>
                            <label for="experience_years" class="block text-sm font-medium text-gray-700">Años de Experiencia</label>
                            <input type="number" name="experience_years" id="experience_years"
                                value="{{ old('experience_years', 0) }}"
                                min="0"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                required>
                        </div>

                        <!-- Estado -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Estado</label>
                            <select name="status" id="status"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Activo</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                                <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspendido</option>
                            </select>
                        </div>

                        <!-- Documentos de Verificación -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900">Documentos de Verificación</h3>
                            
                            <!-- DNI Frente -->
                            <div>
                                <label for="dni_front" class="block text-sm font-medium text-gray-700">
                                    DNI (Frente)
                                </label>
                                <div class="mt-1 flex items-center">
                                    <input type="file" name="dni_front" id="dni_front" accept="image/jpeg,image/png"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                        required>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">
                                    Sube una foto clara del frente de tu DNI. Formatos aceptados: JPG, PNG. Máximo 5MB
                                </p>
                            </div>

                            <!-- DNI Reverso -->
                            <div>
                                <label for="dni_back" class="block text-sm font-medium text-gray-700">
                                    DNI (Reverso)
                                </label>
                                <div class="mt-1 flex items-center">
                                    <input type="file" name="dni_back" id="dni_back" accept="image/jpeg,image/png"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                        required>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">
                                    Sube una foto clara del reverso de tu DNI. Formatos aceptados: JPG, PNG. Máximo 5MB
                                </p>
                            </div>

                            <!-- Selfie con DNI -->
                            <div>
                                <label for="selfie" class="block text-sm font-medium text-gray-700">
                                    Selfie sosteniendo el DNI
                                </label>
                                <div class="mt-1 flex items-center">
                                    <input type="file" name="selfie" id="selfie" accept="image/jpeg,image/png"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                        required>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">
                                    Por favor, toma una foto de tu rostro sosteniendo el DNI junto a tu cara. 
                                    Formatos aceptados: JPG, PNG. Máximo 5MB
                                </p>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Enviar Solicitud
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
