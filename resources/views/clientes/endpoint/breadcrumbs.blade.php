@component('clientes/breadcrumbs')
    <li><a href="{{ route('clientes.endpoint.index') }}"><i class="fa fa-hand-pointer-o"></i>Endpoint</a></li>
    {{ $slot }}
@endcomponent