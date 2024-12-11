<button 
    class="
        btn btn-primary
        
        @if (isset($small) && $small == true)
            btn-xs
        @endif
    " 
    
    @if (isset($type))
        type="{{ $type }}" 
    @endif
    
    @if (isset($form))
        form="{{ $form }}"
    @endif
>
    {{ $slot }}
</button>