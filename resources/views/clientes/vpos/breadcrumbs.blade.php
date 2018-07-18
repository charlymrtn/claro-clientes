@component('clientes/breadcrumbs')
    <li><a href="{{ route('vpos.index') }}"><i class="fa fa-key"></i>vPOS</a></li>
    {{ $slot }}
@endcomponent