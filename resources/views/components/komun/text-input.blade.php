@props([
    'type' => 'text',
    'name',
    'id' => $name,
    'value' => '',
    'required' => false,
    'autofocus' => false,
])

<input
    {{ $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) }}
    type="{{ $type }}"
    name="{{ $name }}"
    id="{{ $id }}"
    value="{{ old($name, $value) }}"
    @if ($required) required @endif
    @if ($autofocus) autofocus @endif
>
