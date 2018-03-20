@component('clientes/breadcrumbs')
    <li><a href="{{ route('transaccion.index') }}"><i class="fa fa-random"></i>Transacciones</a></li>
    {{ $slot }}
@endcomponent