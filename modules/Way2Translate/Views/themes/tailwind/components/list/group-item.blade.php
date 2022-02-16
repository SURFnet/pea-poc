<a href="{{ $href }}" class="
    block w-full
    text-gray-900
    hover:bg-gray-50
    focus-within:ring-2
    focus-within:ring-inset
    focus-within:ring-blue-500
    px-3 py-2

    @if ($activeItem == $item)
        bg-gray-50
    @endif
    focus:outline-none
">
    {{ $item }}
</a>
