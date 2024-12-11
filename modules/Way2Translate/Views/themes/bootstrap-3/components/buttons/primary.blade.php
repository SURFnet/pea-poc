<a 
    href="{{ $href }}"
    class="
        btn btn-primary
        
        @if (isset($small) && $small == true)
            btn-xs
        @endif
    " 
>
    {{ $slot }}
</a>