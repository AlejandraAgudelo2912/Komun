@props(['for' => null, 'messages' => null])

@if($messages)
    @foreach($messages as $message)
        <p {{ $attributes->merge(['class' => 'text-sm text-red-600 dark:text-red-400']) }}>{{ $message }}</p>
    @endforeach
@elseif($for)
    @error($for)
        <p {{ $attributes->merge(['class' => 'text-sm text-red-600 dark:text-red-400']) }}>{{ $message }}</p>
    @enderror
@endif
