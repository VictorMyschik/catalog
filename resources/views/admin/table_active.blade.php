@if($active)
    <x-orchid-icon width="{{ $active ? '2em' : '1.2em' }}"
                   height="{{ $active ? '2em' : '1.2em' }}"
                   class="{{ $active ? 'text-success' : 'text-danger' }}"
                   path="{{ $active ? 'fa.check' : 'fa.ban' }}"/>
@else
    <span class="m-1">
    <x-orchid-icon width="{{ $active ? '2em' : '1.2em' }}"
                   height="{{ $active ? '2em' : '1.2em' }}"
                   class="{{ $active ? 'text-success' : 'text-danger' }}"
                   path="{{ $active ? 'fa.check' : 'fa.ban' }}"/>
</span>
@endif

