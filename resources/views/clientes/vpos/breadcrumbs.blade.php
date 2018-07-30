@component('clientes/breadcrumbs')
    <li><a href="{{ route('clientes.vpos.index') }}"><i class="fa fa-key"></i>vPOS</a></li>
    {{ $slot }}
@endcomponent