<a
    href="{{ $href }}"
    class="
        text-blue-600
        hover:text-blue-700
        focus:outline-none
        focus:ring-2
        focus:ring-offset-2
        focus:ring-blue-500

        @if (isset($small) && $small == true)
        @endif
    "
>
    {{ $slot }}
</a>

