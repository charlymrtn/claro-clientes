@component('clientes/breadcrumbs')
    <li><a href="{{ route('tokens.index') }}"><i class="fa fa-key"></i>Clientes y Tokens</a></li>
    {{ $slot }}
@endcomponent