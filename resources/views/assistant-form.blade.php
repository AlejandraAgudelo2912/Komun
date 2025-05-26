@props(['assistant' => null])

<form action="{{ route('assistant.store') }}" method="POST" class="space-y-6">
    @csrf

    <!-- Bio -->
    <div>
        <label for="bio" class="block text-sm font-medium text-gray-700">Biografía</label>
        <textarea name="bio" id="bio" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('bio', $assistant->bio ?? '') }}</textarea>
    </div>

    <!-- Availability (JSON) -->
    <div>
        <label for="availability" class="block text-sm font-medium text-gray-700">Disponibilidad (JSON)</label>
        <textarea name="availability" id="availability" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('availability', $assistant->availability ?? '{}') }}</textarea>
        <p class="text-xs text-gray-500 mt-1">Ej: {"lunes":"9-13","miércoles":"10-14"}</p>
    </div>

    <!-- Skills (JSON) -->
    <div>
        <label for="skills" class="block text-sm font-medium text-gray-700">Habilidades (JSON)</label>
        <textarea name="skills" id="skills" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('skills', $assistant->skills ?? '[]') }}</textarea>
        <p class="text-xs text-gray-500 mt-1">Ej: ["cocina", "primeros auxilios"]</p>
    </div>

    <!-- Experience Years -->
    <div>
        <label for="experience_years" class="block text-sm font-medium text-gray-700">Años de experiencia</label>
        <input type="number" name="experience_years" id="experience_years" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
               value="{{ old('experience_years', $assistant->experience_years ?? 0) }}" min="0">
    </div>

    <!-- Status -->
    <div>
        <label for="status" class="block text-sm font-medium text-gray-700">Estado</label>
        <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            @foreach(['active', 'inactive', 'suspended'] as $status)
                <option value="{{ $status }}" {{ old('status', $assistant->status ?? 'active') === $status ? 'selected' : '' }}>
                    {{ ucfirst($status) }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Submit -->
    <div>
        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md shadow hover:bg-indigo-700">
            Crear Asistente
        </button>
    </div>
</form>
