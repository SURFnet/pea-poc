<a 
    href="{{ $href }}"
    class="list-group-item
        @if ($activeItem == $item)
            active
        @endif
    "
>
    {{ $item }}
</a>