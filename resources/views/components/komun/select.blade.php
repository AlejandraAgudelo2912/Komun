@props(['disabled' => false, 'label' => null, 'name', 'id' => null, 'options' => [], 'selected' => null, 'required' => false, 'placeholder' => 'Seleccione una opción'])

<div>
    @if($label)
        <x-komun.label :for="$id ?? $name" :value="$label" />
    @endif
    
    <select 
        name="{{ $name }}"
        id="{{ $id ?? $name }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {!! $attributes->merge(['class' => 'mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300']) !!}
    >
        <option value="">{{ $placeholder }}</option>
        @foreach($options as $value => $label)
            <option value="{{ $value }}" {{ (old($name, $selected) == $value) ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    
    @error($name)
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div> 