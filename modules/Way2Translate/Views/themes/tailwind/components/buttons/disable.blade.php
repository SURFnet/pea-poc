<a
    href="{{ $href }}"
    class="
        inline-flex items-center border
        border-transparent text-sm
        font-medium rounded shadow-sm text-white
        bg-red-600
        hover:bg-red-700
        focus:outline-none
        focus:ring-2
        focus:ring-offset-2
        focus:ring-blue-400

        @if (isset($small) && $small == true)
            px-2.5 py-1.5
        @else
            leading-4 px-3 py-2
        @endif
">
    {{ $slot }}
</a>
