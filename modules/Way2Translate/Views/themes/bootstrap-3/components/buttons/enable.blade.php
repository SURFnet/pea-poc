<a 
    href="{{ $href }}"
    class="
        btn btn-success 
        
        @if (isset($small) && $small == true)
            btn-xs
        @endif
    "
>
    {{ $slot }}
</a>