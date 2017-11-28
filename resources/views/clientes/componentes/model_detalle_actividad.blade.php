<div class="timeline-item">
    <h4 class="timeline-header">{{ __('activity.' . $actividad->description) }}</h4>
    <div class="timeline-body">
        <b>ID </b>: {{ $actividad->id }}

        <br><b><i class="fa fa-fw fa-clock-o"></i> Timestamp: </b> {{ $actividad->created_at }}

        <br><b><i class="fa fa-fw fa-child"></i> Causante</b>:
        @if($actividad->causer_type === null)
            Sistema
        @elseif($actividad->causer_type == 'App\Models\User')
            Usuario <a href="{{ route('usuario.index') }}/{{ $actividad->causer_id }}"><span class="badge">#{{ $actividad->causer_id }}</span></a>
        @else
            {{ str_replace('App\Models\\', '', $actividad->causer_type) }} <span class="badge">#{{ $actividad->causer_id }}</span>
        @endif

        @if($actividad->subject_type === null)
            <br><b><i class="fa fa-fw fa-cube"></i> Objeto</b>: {{ str_replace('App\Models\\', '', $actividad->subject_type) }} <span class="badge">#{{ $actividad->subject_id }}</span>
        @elseif($actividad->subject_type == 'App\Models\User')
            <br><b><i class="fa fa-fw fa-cube"></i> Objeto</b>: Usuario <a href="{{ route('usuario.index') }}/{{ $actividad->subject_id }}"><span class="badge">#{{ $actividad->subject_id }}</span></a>
        @endif

        <?php $aCambios = $actividad->changes()->toArray(); ?>
        @if($actividad->description == 'updated')
            @if(!empty($aCambios['attributes']))
                <br>&nbsp;
                <table class="table">
                    <thead>
                    <tr>
                        <th>Campo</th>
                        <th>Nuevo valor</th>
                        <th>Anterior</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($aCambios['attributes'] as $campo => $valor)
                        <tr>
                            <td>{{ $campo }}</td>
                            <td>{{ $valor }}</td>
                            <td>{{ $aCambios['old'][$campo] }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <br>Cambios de atributos no registrables.
            @endif
        @else
            @if(!empty($aCambios['attributes']))
                <br>&nbsp;
                <table class="table">
                    <thead>
                    <tr>
                        <th>Campo</th>
                        <th>Valor</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($aCambios['attributes'] as $campo => $valor)
                        <tr>
                            <td>{{ $campo }}</td>
                            <td>{{ $valor }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        @endif
    </div>
</div>
