@props(['disabled' => false, 'label' => null, 'name', 'id' => null, 'type' => 'text', 'value' => null, 'required' => false])

<div>
    @if($label)
        <x-komun.label :for="$id ?? $name" :value="$label" :required="$required" />
    @endif
    
    <input 
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $id ?? $name }}"
        value="{{ old($name, $value) }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {!! $attributes->merge(['class' => 'mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300']) !!}
    >
    
    @error($name)
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div> 