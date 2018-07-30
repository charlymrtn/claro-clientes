@component('clientes/breadcrumbs')
    <li><a href="{{ route('clientes.token.index') }}"><i class="fa fa-key"></i>Tokens</a></li>
    {{ $slot }}
@endcomponent