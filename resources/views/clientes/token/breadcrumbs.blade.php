@component('clientes/breadcrumbs')
    <li><a href="{{ route('token.index') }}"><i class="fa fa-key"></i>Tokens</a></li>
    {{ $slot }}
@endcomponent