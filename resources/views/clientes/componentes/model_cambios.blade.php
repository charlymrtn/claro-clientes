@if (empty($eventos) || $eventos->isEmpty())
    No se encontró actividad.
@else
    <div class="tab-pane active" id="timeline">
        <ul class="timeline timeline-inverse">
        <?php $sFechaAnterior = ''; ?>
        @foreach($eventos->reverse() as $item)
            <?php $sFechaActual = $item->created_at->format('Y-m-d'); ?>
            @if($sFechaActual != $sFechaAnterior)
                    <li class="time-label"><span class="bg-red">{{ $sFechaActual }}</span></li>
            @endif
            <li>
                @if($item->description == 'updated')
                    <i class="fa fa-pencil bg-blue"></i>
                @elseif($item->description == 'created')
                    <i class="fa fa-plus bg-blue"></i>
                @elseif($item->description == 'deleted')
                    <i class="fa fa-trash bg-blue"></i>
                @elseif($item->description == 'restored')
                    <i class="fa fa-life-ring bg-blue"></i>
                @else
                    <i class="fa fa-eye bg-blue"></i>
                @endif
                <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> {{ $item->created_at }}</span>
                    <h3 class="timeline-header">{{ __('activity.' . $item->description) }}</h3>
                    <div class="timeline-body">
                        <b>Objeto</b>:
                        @if($item->subject_type == 'App\Models\User')
                            <a href="{{ route('clientes.inicio') }}/{{ $item->subject_id }}">Usuario #{{ $item->subject_id }}</a>
                        @else
                            {{ $item->subject_type }} #{{ $item->subject_id }}
                        @endif

                        <?php $aCambios = $item->changes()->toArray(); ?>

                        @if($item->description == 'updated')

                            @if(!empty($aCambios['attributes']))
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
                                <br><b>Cambio</b>: Cambio de atributo no registrable.
                            @endif
                        @elseif($item->description == 'created')
                        @elseif($item->description == 'deleted')
                        @elseif($item->description == 'restored')
                        @else
                        <br><b>Cambios</b>: {{ $item->changes()->toJson() }}
                        @endif
                    </div>
                </div>
            </li>
            <?php $sFechaAnterior = $sFechaActual; ?>
        @endforeach
            <li><i class="fa fa-clock-o bg-gray"></i></li>
        </ul>
    </div>
@endif


