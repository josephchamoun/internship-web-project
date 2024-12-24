

<!-- x-add-button.blade.php -->
<a {{ $attributes->merge(['class' => 'bg-gray-100 text-black px-4 py-2 rounded hover:bg-gray-300']) }} href="{{ $url ?? '#' }}">
    {{ $slot }}
</a>

