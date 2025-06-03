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

                        <!-- Nombre -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input type="text" name="name" id="name"
                                value="{{ old('name', $assistant->name ?? '') }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Ej. María López" required>
                        </div>

                        <!-- Biografía -->
                        <div>
                            <label for="bio" class="block text-sm font-medium text-gray-700">Biografía</label>
                            <textarea name="bio" id="bio" rows="4"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Cuéntanos un poco sobre ti">{{ old('bio', $assistant->bio ?? '') }}</textarea>
                        </div>

                        <!-- Documentos de Verificación -->
                        <div class="space-y-6 bg-gray-50 p-6 rounded-lg border border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Documentos de Verificación</h3>
                            <p class="text-sm text-gray-500 mb-4">
                                Para garantizar la seguridad de nuestra comunidad, necesitamos verificar tu identidad. 
                                Por favor, sube los siguientes documentos:
                            </p>

                            <!-- DNI Frente -->
                            <div>
                                <label for="dni_front" class="block text-sm font-medium text-gray-700">
                                    Foto del DNI (Frente)
                                </label>
                                <div class="mt-1 flex items-center">
                                    <input type="file" name="dni_front" id="dni_front" accept="image/jpeg,image/png,application/pdf"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                        required>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Formatos aceptados: JPG, PNG, PDF. Máximo 5MB</p>
                            </div>

                            <!-- DNI Reverso -->
                            <div>
                                <label for="dni_back" class="block text-sm font-medium text-gray-700">
                                    Foto del DNI (Reverso)
                                </label>
                                <div class="mt-1 flex items-center">
                                    <input type="file" name="dni_back" id="dni_back" accept="image/jpeg,image/png,application/pdf"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                        required>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Formatos aceptados: JPG, PNG, PDF. Máximo 5MB</p>
                            </div>

                            <!-- Selfie con DNI -->
                            <div>
                                <label for="selfie_with_dni" class="block text-sm font-medium text-gray-700">
                                    Selfie sosteniendo el DNI
                                </label>
                                <div class="mt-1 flex items-center">
                                    <input type="file" name="selfie_with_dni" id="selfie_with_dni" accept="image/jpeg,image/png"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                        required>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">
                                    Por favor, toma una foto de tu rostro sosteniendo el DNI junto a tu cara. 
                                    Formatos aceptados: JPG, PNG. Máximo 5MB
                                </p>
                            </div>

                            <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-info-circle text-blue-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-blue-700">
                                            Tus documentos serán tratados con la máxima confidencialidad y solo serán utilizados 
                                            para verificar tu identidad. Una vez verificados, serán eliminados de nuestros servidores.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Disponibilidad -->
                        <div>
                            <label for="availability" class="block text-sm font-medium text-gray-700">Disponibilidad</label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @php
                                    $days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
                                @endphp
                                @foreach($days as $day)
                                    <div>
                                        <label for="availability_{{ strtolower($day) }}" class="block text-xs font-medium text-gray-600">{{ $day }}</label>
                                        <input type="text" name="availability[{{ strtolower($day) }}]" id="availability_{{ strtolower($day) }}"
                                            value="{{ old("availability." . strtolower($day), '') }}"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Ej. 9-13">
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Habilidades -->
                        <div>
                            <label for="skills" class="block text-sm font-medium text-gray-700">Habilidades</label>
                            <input type="text" name="skills" id="skills"
                                value="{{ old('skills', '') }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Ej. cocina, primeros auxilios, costura">
                        </div>

                        <!-- Años de experiencia -->
                        <div>
                            <label for="experience_years" class="block text-sm font-medium text-gray-700">Años de experiencia</label>
                            <input type="number" name="experience_years" id="experience_years"
                                value="{{ old('experience_years', 0) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                min="0">
                        </div>

                        <!-- Estado -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Estado</label>
                            <select name="status" id="status"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @foreach(['active', 'inactive', 'suspended'] as $status)
                                    <option value="{{ $status }}" {{ old('status', 'active') === $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Submit -->
                        <div class="text-right">
                            <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded-md shadow hover:bg-blue-700 transition">
                                Crear Asistente
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
