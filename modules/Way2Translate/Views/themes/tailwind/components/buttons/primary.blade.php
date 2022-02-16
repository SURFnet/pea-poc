<a
    href="{{ $href }}"
    class="
        inline-flex items-center border
        border-transparent text-sm
        font-medium rounded shadow-sm text-white
        bg-blue-600
        hover:bg-blue-700
        focus:outline-none
        focus:ring-2
        focus:ring-offset-2
        focus:ring-blue-500

        @if (isset($small) && $small == true)
            px-2.5 py-1.5
        @else
            leading-4 px-3 py-2
        @endif
">
    {{ $slot }}
</a>
