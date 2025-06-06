@props(['disabled' => false, 'label' => null, 'name', 'id' => null, 'value' => '1', 'checked' => false])

<div class="flex items-center">
    <input 
        type="checkbox"
        name="{{ $name }}"
        id="{{ $id ?? $name }}"
        value="{{ $value }}"
        {{ $checked ? 'checked' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {!! $attributes->merge(['class' => 'rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800']) !!}
    >
    
    @if($label)
        <x-komun.label :for="$id ?? $name" class="ml-2" :value="$label" />
    @endif
    
    @error($name)
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div> 