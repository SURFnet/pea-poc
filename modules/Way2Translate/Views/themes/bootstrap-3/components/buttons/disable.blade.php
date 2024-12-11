<a 
    href="{{ $href }}"
    class="
        btn btn-danger 
        
        @if (isset($small) && $small == true)
            btn-xs
        @endif
    "
>
    {{ $slot }}
</a>