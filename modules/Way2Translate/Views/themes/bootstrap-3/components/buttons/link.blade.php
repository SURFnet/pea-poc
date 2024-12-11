<a 
    href="{{ $href }}"
    class="
        btn btn-link 
        
        @if (isset($small) && $small == true)
            btn-xs
        @endif
    "
>
    {{ $slot }}
</a>

