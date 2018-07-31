<?php

namespace App\Classes\Estadistica;

use DB;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Comercio;
use App\Models\Transaccion;

/*
 * Clase para manejo estadísticas de transacciones
  *
 * @package Estadistica
 */

class Transacciones
{

    // {{{ properties

    // ID de comercio
    protected $sComercio;
    protected $oTransaccion;

    // }}}

    // {{{ private functions

    /**
     * Obtiene estadísticas por hora
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $oBuilder
     * @param  array  $aFiltros  Filtros para ['forma_pago', 'operacion', 'transaccion_estatus_id']
     *
     * @return Builder
     */
    private function transaccion_filtra(Builder $oBuilder, array $aFiltros = null): Builder
    {
        if (!empty($aFiltros['forma_pago'])) {
            if (is_array($aFiltros['forma_pago'])) {
                $oBuilder->whereIn('forma_pago', $aFiltros['forma_pago']);
            } else {
                $oBuilder->where('forma_pago', $aFiltros['forma_pago']);
            }
        }
        if (!empty($aFiltros['operacion'])) {
            if (is_array($aFiltros['operacion'])) {
                $oBuilder->whereIn('operacion', $aFiltros['operacion']);
            } else {
                $oBuilder->where('operacion', $aFiltros['operacion']);
            }
        }
        if (!empty($aFiltros['transaccion_estatus_id'])) {
            if (is_array($aFiltros['transaccion_estatus_id'])) {
                $oBuilder->whereIn('transaccion_estatus_id', $aFiltros['transaccion_estatus_id']);
            } else {
                $oBuilder->where('transaccion_estatus_id', $aFiltros['transaccion_estatus_id']);
            }
        }
        return $oBuilder;
    }

    /**
     * Funcion para calcular el porcentaje o en su caso mostrar 0
     *
     * @param  float  $parcial
     * @param  float  $total
     *
     * @return float
     */
    private function porcentaje(float $parcial, float $total): float
    {
        if ($total > 0) {
            return $parcial*100/$total;
        }
        return 0;
    }

    // }}}


    // {{{ public functions

    /**
     * Crea nueva instancia.
     *
     * @param App\Models\Comercio $comercio Opcional
     * @param App\Models\Transaccion $transaccion Opcional
     *
     * @return void
     */
    public function __construct(Transaccion $transaccion = null)
    {
        $this->oTransaccion = $transaccion ?? new Transaccion();
    }

    /**
     * Define comercio a filtrar
     *
     * @param App\Models\Comercio $comercio
     *
     * @return void
     */
    public function setComercio(string $sComercio)
    {
        $this->sComercio = $sComercio;
        return $this;
    }

    /**
     * Obtiene estadísticas por hora
     *
     * @param  array  $aFiltros  Filtros de transacciones ver $this->transaccion_filtra
     * @param  \Illuminate\Http\Request  $oFecha
     *
     * @return Collection
     */
    public function total_hora(array $aFiltros = null, Carbon $oFecha = null): Collection
    {
        // Define fecha
        $oFecha = $oFecha ?? Carbon::now();

        // Realiza query de las transacciones
        $oTrx = $this->oTransaccion
            ->select(DB::raw("HOUR(created_at) AS hora, COUNT(*) AS total, SUM(monto) AS monto"))
            ->where(
                function ($q) {
                    if (!empty($this->sComercio)) {
                        return $q
                            ->where('comercio_', $this->sComercio);
                    }
                }
            )
            ->whereBetween('created_at', [$oFecha->copy()->startOfDay(), $oFecha->copy()->endOfDay()])
            ->where(
                function ($q) use ($aFiltros) {
                    return $this->transaccion_filtra($q, $aFiltros);
                }
            )
            ->groupBy(DB::raw("HOUR(created_at)"))
            ->get();

        if ($oTrx->isEmpty()) {
            return [
                'hora' => $oFecha->hour,
                'total' => 0,
                'monto' => 0,
            ];
        }
        return $oTrx->first()->toArray();
    }

    /**
     * Obtiene estadísticas del día agrupadas por hora
     *
     * @param  array  $aFiltros  Filtros de transacciones ver $this->transaccion_filtra
     * @param  \Illuminate\Http\Request  $oFecha
     *
     * @return  Collection  Regresa colección de las transacciones del día.
     */
    public function dia_xhora(array $aFiltros = null, Carbon $oFecha = null): Collection
    {
        // Define fecha
        $oFecha = $oFecha ?? Carbon::now();

        // Realiza query de las transacciones
        $cTrx = $this->oTransaccion
            ->select(DB::raw("HOUR(created_at) as hora, COUNT(*) AS total, SUM(monto) AS monto "))
            ->where(
                function ($q) {
                    if (!empty($this->sComercio)) {
                        return $q
                            ->where('comercio_uuid', $this->sComercio);
                    }
                }
            )
            ->whereBetween('created_at', [$oFecha->copy()->startOfDay(), $oFecha->copy()->endOfDay()])
            ->where(
                function ($q) use ($aFiltros) {
                    return $this->transaccion_filtra($q, $aFiltros);
                }
            )
            ->groupBy(DB::raw("HOUR(created_at)"))
            ->get();

        // Inicia colección vacía
        $aTmp = [];
        foreach(range(0, 23) as $i) {
            $aTmp[$i] = (object) [
                'hora' => $i,
                'label' => $i . ":00",
                'total' => 0,
                'monto' => 0,
            ];
        }
        // Inserta coleccion en arreglo vacío
        foreach($cTrx as $oTrx) {
            $aTmp[$oTrx->hora] = (object) array_merge((array) $aTmp[$oTrx->hora], $oTrx->toArray());
        }
        return collect($aTmp);
    }

    /**
     * Obtiene totales de transacciones del día
     *
     * @param  array  $aFiltros  Filtros de transacciones ver $this->transaccion_filtra
     * @param  \Illuminate\Http\Request  $oFecha
     *
     * @return  array  Regresa arreglo con los totales del día: ['fecha', 'total', 'monto']
     */
    public function total_dia(array $aFiltros = null, Carbon $oFecha = null): array
    {
        // Define fecha
        $oFecha = $oFecha ?? Carbon::now();

        // Realiza query de las transacciones
        $oTrx = $this->oTransaccion
            ->select(DB::raw("DATE(created_at) AS fecha, COUNT(*) AS total, SUM(monto) AS monto"))
            ->where(
                function ($q) {
                    if (!empty($this->sComercio)) {
                        return $q
                            ->where('comercio_uuid', $this->sComercio);
                    }
                }
            )
            ->whereBetween('created_at', [$oFecha->copy()->startOfDay(), $oFecha->copy()->endOfDay()])
            ->where(
                function ($q) use ($aFiltros) {
                    return $this->transaccion_filtra($q, $aFiltros);
                }
            )
            ->groupBy(DB::raw("DATE(created_at)"))
            ->get();

        if ($oTrx->isEmpty()) {
            return [
                'fecha' => $oFecha->toDateString(),
                'total' => 0,
                'monto' => 0,
            ];
        }
        return $oTrx->first()->toArray();
    }

    /**
     * Obtiene totales de transacciones del día agrupados por estatus de la transacción y respuesta del procesador
     *
     * @param  array  $aFiltros  Filtros de transacciones ver $this->transaccion_filtra
     * @param  \Illuminate\Http\Request  $oFecha
     *
     * @return  Collection  Regresa colección con los totales del día agrupados por estatus y respuesta del procesador
     */
    public function total_dia_respuestas_procesador_xestatus(array $aFiltros = null, Carbon $oFecha = null): Collection
    {
        // Define fecha
        $oFecha = $oFecha ?? Carbon::now();
        // Realiza query de las transacciones
        $cTrx = $this->oTransaccion
            ->select(DB::raw("DATE(created_at) AS fecha, transaccion_estatus_id, datos_procesador->>'$.response_code' AS response_code, datos_procesador->>'$.response_description' AS response_desc, COUNT(*) AS total, SUM(monto) AS monto"))
            ->where(
                function ($q) {
                    if (!empty($this->sComercio)) {
                        return $q
                            ->where('comercio_uuid', $this->sComercio);
                    }
                }
            )
            ->whereBetween('created_at', [$oFecha->copy()->startOfDay(), $oFecha->copy()->endOfDay()])
            ->where(
                function ($q) use ($aFiltros) {
                    return $this->transaccion_filtra($q, $aFiltros);
                }
            )
            ->groupBy(DB::raw("DATE(created_at), transaccion_estatus_id, response_code, response_desc"))
            ->get();
        // Calcula estadísticas extra
        $iTotal = $cTrx->sum('total');
        foreach ($cTrx as $trx) {
            $trx['gran-total'] = $iTotal;
            $trx['porcentaje_cantidad'] = $this->porcentaje($trx->total, $iTotal);
        }
        // Regresa resultados
        return $cTrx;
    }

    /**
     * Obtiene totales de transacciones del día agrupados por estatus de la transacción y respuesta del antifraude
     *
     * @param  array  $aFiltros  Filtros de transacciones ver $this->transaccion_filtra
     * @param  \Illuminate\Http\Request  $oFecha
     *
     * @return  Collection  Regresa arreglo con los totales del día agrupados por estatus y respuesta del antifraude
     */
    public function total_dia_respuestas_antifraude_xestatus(array $aFiltros = null, Carbon $oFecha = null): Collection
    {
        // Define fecha
        $oFecha = $oFecha ?? Carbon::now();
        // Realiza query de las transacciones
        $cTrx = $this->oTransaccion
            ->select(DB::raw("DATE(created_at) AS fecha, transaccion_estatus_id, datos_antifraude->>'$.response_code' AS response_code, datos_antifraude->>'$.response_description' AS response_desc, COUNT(*) AS total, SUM(monto) AS monto"))
            ->where(
                function ($q) {
                    if (!empty($this->sComercio)) {
                        return $q
                            ->where('comercio_uuid', $this->sComercio);
                    }
                }
            )
            ->whereBetween('created_at', [$oFecha->copy()->startOfDay(), $oFecha->copy()->endOfDay()])
            ->where(
                function ($q) use ($aFiltros) {
                    return $this->transaccion_filtra($q, $aFiltros);
                }
            )
            ->groupBy(DB::raw("DATE(created_at), transaccion_estatus_id, response_code, response_desc"))
            ->get();
        // Calcula estadísticas extra
        $iTotal = $cTrx->sum('total');
        foreach ($cTrx as $trx) {
            $trx['gran-total'] = $iTotal;
            $trx['porcentaje_cantidad'] = $this->porcentaje($trx->total, $iTotal);
        }
        // Regresa resultados
        return $cTrx;
    }


    /**
     * Obtiene totales de transacciones del día agrupados por estatus de la transacción
     *
     * @param  array  $aFiltros  Filtros de transacciones ver $this->transaccion_filtra
     * @param  \Illuminate\Http\Request  $oFecha
     *
     * @return  array  Regresa arreglo con los totales del día agrupados por estatus y tipo de pago
     */
    public function total_dia_xestatus_xtipo(array $aFiltros = null, Carbon $oFecha = null): array
    {
        // Define fecha
        $oFecha = $oFecha ?? Carbon::now();

        // Realiza query de las transacciones
        $cTrx = $this->oTransaccion
            ->select(DB::raw("DATE(created_at) AS fecha, transaccion_estatus_id, forma_pago, COUNT(*) AS total, SUM(monto) AS monto"))
            ->where(
                function ($q) {
                    if (!empty($this->sComercio)) {
                        return $q
                            ->where('comercio_uuid', $this->sComercio);
                    }
                }
            )
            ->whereBetween('created_at', [$oFecha->copy()->startOfDay(), $oFecha->copy()->endOfDay()])
            ->where(
                function ($q) use ($aFiltros) {
                    return $this->transaccion_filtra($q, $aFiltros);
                }
            )
            ->groupBy(DB::raw("DATE(created_at), transaccion_estatus_id, forma_pago"))
            ->get();

        // Inicializa estructura de resultados
        // @todo: Cambiarlo a dinámico y pasar por parámetros
        $aTrx = [
            'total' => [
                'total' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 100, 'porcentaje_monto' => 100, 'label' => 'Total'],
                'aceptadas' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 0, 'porcentaje_monto' => 0, 'label' => 'Aceptado'],
                'rechazadas' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 0, 'porcentaje_monto' => 0, 'label' => 'Rechazo', 'respuestas' => []],
                'fraude' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 0, 'porcentaje_monto' => 0, 'label' => 'Fraudes'],
                'otros' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 0, 'porcentaje_monto' => 0, 'label' => 'Otros'],
            ],
            'tarjeta-credito' => [
                'total' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 100, 'porcentaje_monto' => 100, 'label' => 'Total'],
                'aceptadas' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 0, 'porcentaje_monto' => 0, 'label' => 'Aceptado'],
                'rechazadas' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 0, 'porcentaje_monto' => 0, 'label' => 'Rechazo'],
                'fraude' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 0, 'porcentaje_monto' => 0, 'label' => 'Fraudes'],
                'otros' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 0, 'porcentaje_monto' => 0, 'label' => 'Otros'],
            ],
            'tarjeta-debito' => [
                'total' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 100, 'porcentaje_monto' => 100, 'label' => 'Total'],
                'aceptadas' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 0, 'porcentaje_monto' => 0, 'label' => 'Aceptado'],
                'rechazadas' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 0, 'porcentaje_monto' => 0, 'label' => 'Rechazo'],
                'fraude' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 0, 'porcentaje_monto' => 0, 'label' => 'Fraudes'],
                'otros' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 0, 'porcentaje_monto' => 0, 'label' => 'Otros'],
            ]
        ];

        foreach ($cTrx as $trx) {
            // Totales
            $aTrx['total']['total']['cantidad'] += $trx->total;
            $aTrx['total']['total']['monto'] += $trx->monto;
            if (in_array($trx->transaccion_estatus_id, [1, 5])) {
                $aTrx['total']['aceptadas']['cantidad'] += $trx->total;
                $aTrx['total']['aceptadas']['monto'] += $trx->monto;
                if ($trx->forma_pago == "tarjeta-credito") {
                    $aTrx['tarjeta-credito']['aceptadas']['cantidad'] += $trx->total;
                    $aTrx['tarjeta-credito']['aceptadas']['monto'] += $trx->monto;
                } else if ($trx->forma_pago == "tarjeta-debito") {
                    $aTrx['tarjeta-debito']['aceptadas']['cantidad'] += $trx->total;
                    $aTrx['tarjeta-debito']['aceptadas']['monto'] += $trx->monto;
                }
            } else if (in_array($trx->transaccion_estatus_id, [7, 13])) {
                $aTrx['total']['rechazadas']['cantidad'] += $trx->total;
                $aTrx['total']['rechazadas']['monto'] += $trx->monto;
                if ($trx->forma_pago == "tarjeta-credito") {
                    $aTrx['tarjeta-credito']['rechazadas']['cantidad'] += $trx->total;
                    $aTrx['tarjeta-credito']['rechazadas']['monto'] += $trx->monto;
                } else if ($trx->forma_pago == "tarjeta-debito") {
                    $aTrx['tarjeta-debito']['rechazadas']['cantidad'] += $trx->total;
                    $aTrx['tarjeta-debito']['rechazadas']['monto'] += $trx->monto;
                }
            } else if ($trx->transaccion_estatus_id == 8) {
                $aTrx['total']['fraude']['cantidad'] += $trx->total;
                $aTrx['total']['fraude']['monto'] += $trx->monto;
                if ($trx->forma_pago == "tarjeta-credito") {
                    $aTrx['tarjeta-credito']['fraude']['cantidad'] += $trx->total;
                    $aTrx['tarjeta-credito']['fraude']['monto'] += $trx->monto;
                } else if ($trx->forma_pago == "tarjeta-debito") {
                    $aTrx['tarjeta-debito']['fraude']['cantidad'] += $trx->total;
                    $aTrx['tarjeta-debito']['fraude']['monto'] += $trx->monto;
                }
            } else {
                $aTrx['total']['otros']['cantidad'] += $trx->total;
                $aTrx['total']['otros']['monto'] += $trx->monto;
                if ($trx->forma_pago == "tarjeta-credito") {
                    $aTrx['tarjeta-credito']['otros']['cantidad'] += $trx->total;
                    $aTrx['tarjeta-credito']['otros']['monto'] += $trx->monto;
                } else if ($trx->forma_pago == "tarjeta-debito") {
                    $aTrx['tarjeta-debito']['otros']['cantidad'] += $trx->total;
                    $aTrx['tarjeta-debito']['otros']['monto'] += $trx->monto;
                }
            }
        }
        // Porcentajes
        $aTrx['total']['aceptadas']['porcentaje_cantidad'] = $this->porcentaje($aTrx['total']['aceptadas']['cantidad'], $aTrx['total']['total']['cantidad']);
        $aTrx['total']['rechazadas']['porcentaje_cantidad'] = $this->porcentaje($aTrx['total']['rechazadas']['cantidad'], $aTrx['total']['total']['cantidad']);
        $aTrx['total']['fraude']['porcentaje_cantidad'] = $this->porcentaje($aTrx['total']['fraude']['cantidad'], $aTrx['total']['total']['cantidad']);
        $aTrx['total']['otros']['porcentaje_cantidad'] = $this->porcentaje($aTrx['total']['otros']['cantidad'], $aTrx['total']['total']['cantidad']);
        $aTrx['total']['aceptadas']['porcentaje_monto'] = $this->porcentaje($aTrx['total']['aceptadas']['monto'], $aTrx['total']['total']['monto']);
        $aTrx['total']['rechazadas']['porcentaje_monto'] = $this->porcentaje($aTrx['total']['rechazadas']['monto'], $aTrx['total']['total']['monto']);
        $aTrx['total']['fraude']['porcentaje_monto'] = $this->porcentaje($aTrx['total']['fraude']['monto'], $aTrx['total']['total']['monto']);
        $aTrx['total']['otros']['porcentaje_monto'] = $this->porcentaje($aTrx['total']['otros']['monto'], $aTrx['total']['total']['monto']);
        // Obtiene la suma del total de transacciones y $this->porcentaje de tarjeta de credito y debito
        $aTrx['tarjeta-credito']['total']['cantidad'] = $aTrx['tarjeta-credito']['aceptadas']['cantidad'] + $aTrx['tarjeta-credito']['rechazadas']['cantidad'] + $aTrx['tarjeta-credito']['fraude']['cantidad'] + $aTrx['tarjeta-credito']['otros']['cantidad'];
        $aTrx['tarjeta-credito']['total']['monto'] = $aTrx['tarjeta-credito']['aceptadas']['monto'] + $aTrx['tarjeta-credito']['rechazadas']['monto'] + $aTrx['tarjeta-credito']['fraude']['monto'] + $aTrx['tarjeta-credito']['otros']['monto'];
        $aTrx['tarjeta-debito']['total']['cantidad'] = $aTrx['tarjeta-debito']['aceptadas']['cantidad'] + $aTrx['tarjeta-debito']['rechazadas']['cantidad'] + $aTrx['tarjeta-debito']['fraude']['cantidad'] + $aTrx['tarjeta-debito']['otros']['cantidad'];
        $aTrx['tarjeta-debito']['total']['monto'] = $aTrx['tarjeta-debito']['aceptadas']['monto'] + $aTrx['tarjeta-debito']['rechazadas']['monto'] + $aTrx['tarjeta-debito']['fraude']['monto'] + $aTrx['tarjeta-debito']['otros']['monto'];
        // Obtiene el porcentaje de transacciones por estatus por tarjeta de credito
        $aTrx['tarjeta-credito']['aceptadas']['porcentaje_cantidad'] = $this->porcentaje($aTrx['tarjeta-credito']['aceptadas']['cantidad'], $aTrx['tarjeta-credito']['total']['cantidad']);
        $aTrx['tarjeta-credito']['rechazadas']['porcentaje_cantidad'] = $this->porcentaje($aTrx['tarjeta-credito']['rechazadas']['cantidad'], $aTrx['tarjeta-credito']['total']['cantidad']);
        $aTrx['tarjeta-credito']['fraude']['porcentaje_cantidad'] = $this->porcentaje($aTrx['tarjeta-credito']['fraude']['cantidad'], $aTrx['tarjeta-credito']['total']['cantidad']);
        $aTrx['tarjeta-credito']['otros']['porcentaje_cantidad'] = $this->porcentaje($aTrx['tarjeta-credito']['otros']['cantidad'], $aTrx['tarjeta-credito']['total']['cantidad']);
        $aTrx['tarjeta-credito']['aceptadas']['porcentaje_monto'] = $this->porcentaje($aTrx['tarjeta-credito']['aceptadas']['monto'], $aTrx['tarjeta-credito']['total']['monto']);
        $aTrx['tarjeta-credito']['rechazadas']['porcentaje_monto'] = $this->porcentaje($aTrx['tarjeta-credito']['rechazadas']['monto'], $aTrx['tarjeta-credito']['total']['monto']);
        $aTrx['tarjeta-credito']['fraude']['porcentaje_monto'] = $this->porcentaje($aTrx['tarjeta-credito']['fraude']['monto'], $aTrx['tarjeta-credito']['total']['monto']);
        $aTrx['tarjeta-credito']['otros']['porcentaje_monto'] = $this->porcentaje($aTrx['tarjeta-credito']['otros']['monto'], $aTrx['tarjeta-credito']['total']['monto']);
        $aTrx['tarjeta-debito']['aceptadas']['porcentaje_cantidad'] = $this->porcentaje($aTrx['tarjeta-debito']['aceptadas']['cantidad'], $aTrx['tarjeta-debito']['total']['cantidad']);
        $aTrx['tarjeta-debito']['rechazadas']['porcentaje_cantidad'] = $this->porcentaje($aTrx['tarjeta-debito']['rechazadas']['cantidad'], $aTrx['tarjeta-debito']['total']['cantidad']);
        $aTrx['tarjeta-debito']['fraude']['porcentaje_cantidad'] = $this->porcentaje($aTrx['tarjeta-debito']['fraude']['cantidad'], $aTrx['tarjeta-debito']['total']['cantidad']);
        $aTrx['tarjeta-debito']['otros']['porcentaje_cantidad'] = $this->porcentaje($aTrx['tarjeta-debito']['otros']['cantidad'], $aTrx['tarjeta-debito']['total']['cantidad']);
        $aTrx['tarjeta-debito']['aceptadas']['porcentaje_monto'] = $this->porcentaje($aTrx['tarjeta-debito']['aceptadas']['monto'], $aTrx['tarjeta-debito']['total']['monto']);
        $aTrx['tarjeta-debito']['rechazadas']['porcentaje_monto'] = $this->porcentaje($aTrx['tarjeta-debito']['rechazadas']['monto'], $aTrx['tarjeta-debito']['total']['monto']);
        $aTrx['tarjeta-debito']['fraude']['porcentaje_monto'] = $this->porcentaje($aTrx['tarjeta-debito']['fraude']['monto'], $aTrx['tarjeta-debito']['total']['monto']);
        $aTrx['tarjeta-debito']['otros']['porcentaje_monto'] = $this->porcentaje($aTrx['tarjeta-debito']['otros']['monto'], $aTrx['tarjeta-debito']['total']['monto']);

        return $aTrx;
    }

    /**
     * Obtiene estadísticas de la semana
     *
     * @param  array  $aFiltros  Filtros de transacciones ver $this->transaccion_filtra
     * @param  \Illuminate\Http\Request  $oFecha
     *
     * @return  Collection  Regresa colección de las transacciones de la semana.
     */
    public function semana_xdia(array $aFiltros = null, Carbon $oFecha = null): Collection
    {
        // Define fecha
        $oFecha = $oFecha ?? Carbon::now();
        $oFechaInical = $oFecha->copy()->subDays(7)->startOfDay();

        // Realiza query de las transacciones
        $cTrx = $this->oTransaccion
            ->select(DB::raw("DATE(created_at) as dia, COUNT(*) AS total, SUM(monto) AS monto "))
            ->where(
                function ($q) {
                    if (!empty($this->sComercio)) {
                        return $q
                            ->where('comercio_uuid', $this->sComercio);
                    }
                }
            )
            ->whereBetween('created_at', [$oFechaInical, $oFecha->copy()->endOfDay()])
            ->where(
                function ($q) use ($aFiltros) {
                    return $this->transaccion_filtra($q, $aFiltros);
                }
            )
            ->groupBy(DB::raw("DATE(created_at)"))
            ->get();
        // Inicia colección vacía
        // De $oFecha->copy()->startOfWeek()->day a $oFecha->copy()->endOfWeek()->day
        $aTmp = [];
        foreach(range(0, 7) as $i) {
            $aTmp[$oFechaInical->toDateString()] = (object) [
                'dia' => $oFechaInical->toDateString(),
                'label' => $oFechaInical->formatLocalized('%b %d'),
                'total' => 0,
                'monto' => 0,
            ];
            $oFechaInical->addDay();
        }
        // Inserta coleccion en arreglo vacío
        foreach($cTrx as $oTrx) {
            $aTmp[$oTrx->dia] = (object) array_merge((array) $aTmp[$oTrx->dia], $oTrx->toArray());
        }
        return collect($aTmp);
    }

    /**
     * Obtiene totales de transacciones de la semana
     *
     * @param  array  $aFiltros  Filtros de transacciones ver $this->transaccion_filtra
     * @param  \Illuminate\Http\Request  $oFecha
     *
     * @return  array  Regresa arreglo con los totales de la semana: ['semana', 'total', 'monto']
     */
    public function total_semana(array $aFiltros = null, Carbon $oFecha = null): array
    {
        // Define fecha
        $oFecha = $oFecha ?? Carbon::now();

        // Realiza query de las transacciones
        $oTrx = $this->oTransaccion
            ->select(DB::raw("WEEK(created_at) AS semana, COUNT(*) AS total, SUM(monto) AS monto"))
            ->where(
                function ($q) {
                    if (!empty($this->sComercio)) {
                        return $q
                            ->where('comercio_uuid', $this->sComercio);
                    }
                }
            )
            ->whereBetween('created_at', [$oFecha->copy()->startOfWeek(), $oFecha->copy()->endOfWeek()])
            ->where(
                function ($q) use ($aFiltros) {
                    return $this->transaccion_filtra($q, $aFiltros);
                }
            )
            ->groupBy(DB::raw("WEEK(created_at)"))
            ->get();
        if ($oTrx->isEmpty()) {
            return [
                'semana' => $oFecha->copy()->weekOfYear(),
                'total' => 0,
                'monto' => 0,
            ];
        }
        return $oTrx->first()->toArray();
    }

    /**
     * Obtiene totales de transacciones de la semana agrupados por estatus de la transacción y respuesta del procesador
     *
     * @param  array  $aFiltros  Filtros de transacciones ver $this->transaccion_filtra
     * @param  \Illuminate\Http\Request  $oFecha
     *
     * @return  Collection  Regresa colección con los totales del día agrupados por estatus y respuesta del procesador
     */
    public function total_semana_respuestas_procesador_xestatus(array $aFiltros = null, Carbon $oFecha = null): Collection
    {
        // Define fecha
        $oFecha = $oFecha ?? Carbon::now();
        $oFechaInical = $oFecha->copy()->subDays(7);
        // Realiza query de las transacciones
        $cTrx = $this->oTransaccion
            ->select(DB::raw("transaccion_estatus_id, datos_procesador->>'$.response_code' AS response_code, datos_procesador->>'$.response_description' AS response_desc, COUNT(*) AS total, SUM(monto) AS monto"))
            ->where(
                function ($q) {
                    if (!empty($this->sComercio)) {
                        return $q
                            ->where('comercio_uuid', $this->sComercio);
                    }
                }
            )
            ->whereBetween('created_at', [$oFechaInical, $oFecha->copy()->endOfDay()])
            ->where(
                function ($q) use ($aFiltros) {
                    return $this->transaccion_filtra($q, $aFiltros);
                }
            )
            ->groupBy(DB::raw("transaccion_estatus_id, response_code, response_desc"))
            ->get();
        // Calcula estadísticas extra
        $iTotal = $cTrx->sum('total');
        foreach ($cTrx as $trx) {
            $trx['gran-total'] = $iTotal;
            $trx['porcentaje_cantidad'] = $this->porcentaje($trx->total, $iTotal);
        }
        // Regresa resultados
        return $cTrx;
    }

    /**
     * Obtiene totales de transacciones de la semana agrupados por estatus de la transacción y respuesta del antifraude
     *
     * @param  array  $aFiltros  Filtros de transacciones ver $this->transaccion_filtra
     * @param  \Illuminate\Http\Request  $oFecha
     *
     * @return  Collection  Regresa arreglo con los totales del día agrupados por estatus y respuesta del antifraude
     */
    public function total_semana_respuestas_antifraude_xestatus(array $aFiltros = null, Carbon $oFecha = null): Collection
    {
        // Define fecha
        $oFecha = $oFecha ?? Carbon::now();
        $oFechaInical = $oFecha->copy()->subDays(7);
        // Realiza query de las transacciones
        $cTrx = $this->oTransaccion
            ->select(DB::raw("transaccion_estatus_id, datos_antifraude->>'$.response_code' AS response_code, datos_antifraude->>'$.response_description' AS response_desc, COUNT(*) AS total, SUM(monto) AS monto"))
            ->where(
                function ($q) {
                    if (!empty($this->sComercio)) {
                        return $q
                            ->where('comercio_uuid', $this->sComercio);
                    }
                }
            )
            ->whereBetween('created_at', [$oFechaInical, $oFecha->copy()->endOfDay()])
            ->where(
                function ($q) use ($aFiltros) {
                    return $this->transaccion_filtra($q, $aFiltros);
                }
            )
            ->groupBy(DB::raw("transaccion_estatus_id, response_code, response_desc"))
            ->get();
        // Calcula estadísticas extra
        $iTotal = $cTrx->sum('total');
        foreach ($cTrx as $trx) {
            $trx['gran-total'] = $iTotal;
            $trx['porcentaje_cantidad'] = $this->porcentaje($trx->total, $iTotal);
        }
        // Regresa resultados
        return $cTrx;
    }

    /**
     * Obtiene totales de transacciones del día agrupados por estatus de la transacción
     *
     * @param  array  $aFiltros  Filtros de transacciones ver $this->transaccion_filtra
     * @param  \Illuminate\Http\Request  $oFecha
     *
     * @return  array  Regresa arreglo con los totales del día agrupados por estatus y tipo de pago
     */
    public function total_semana_xdia_xestatus_xtipo(array $aFiltros = null, Carbon $oFecha = null): array
    {
        // Define fecha
        $oFecha = $oFecha ?? Carbon::now();
        $oFechaInical = $oFecha->copy()->subDays(7);

        // Realiza query de las transacciones
        $cTrx = $this->oTransaccion
            ->select(DB::raw("DATE(created_at) AS fecha, transaccion_estatus_id, forma_pago, COUNT(*) AS total, SUM(monto) AS monto"))
            ->where(
                function ($q) {
                    if (!empty($this->sComercio)) {
                        return $q
                            ->where('comercio_uuid', $this->sComercio);
                    }
                }
            )
            ->whereBetween('created_at', [$oFechaInical, $oFecha->copy()->endOfDay()])
            ->where(
                function ($q) use ($aFiltros) {
                    return $this->transaccion_filtra($q, $aFiltros);
                }
            )
            ->groupBy(DB::raw("DATE(created_at), transaccion_estatus_id, forma_pago"))
            ->get();
        #dd($cTrx->toArray());
        // Inicializa estructura de resultados
        // @todo: Cambiarlo a dinámico y pasar por parámetros
        $aTrx = [
            'label' => [],
            'dias' => [],
            'resultados' => [],
            'total' => [
                    'total' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 100, 'porcentaje_monto' => 100, 'label' => 'Total'],
                    'aceptadas' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 0, 'porcentaje_monto' => 0, 'label' => 'Aceptado'],
                    'rechazadas' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 0, 'porcentaje_monto' => 0, 'label' => 'Rechazo', 'respuestas' => []],
                    'fraude' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 0, 'porcentaje_monto' => 0, 'label' => 'Fraudes'],
                    'otros' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 0, 'porcentaje_monto' => 0, 'label' => 'Otros'],
                ],
                'tarjeta-credito' => [
                    'total' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 100, 'porcentaje_monto' => 100, 'label' => 'Total'],
                    'aceptadas' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 0, 'porcentaje_monto' => 0, 'label' => 'Aceptado'],
                    'rechazadas' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 0, 'porcentaje_monto' => 0, 'label' => 'Rechazo'],
                    'fraude' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 0, 'porcentaje_monto' => 0, 'label' => 'Fraudes'],
                    'otros' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 0, 'porcentaje_monto' => 0, 'label' => 'Otros'],
                ],
                'tarjeta-debito' => [
                    'total' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 100, 'porcentaje_monto' => 100, 'label' => 'Total'],
                    'aceptadas' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 0, 'porcentaje_monto' => 0, 'label' => 'Aceptado'],
                    'rechazadas' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 0, 'porcentaje_monto' => 0, 'label' => 'Rechazo'],
                    'fraude' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 0, 'porcentaje_monto' => 0, 'label' => 'Fraudes'],
                    'otros' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 0, 'porcentaje_monto' => 0, 'label' => 'Otros'],
                ]
        ];
        foreach(range(0, 7) as $i) {
            // Labels
            $aTrx['label'][] = $oFechaInical->formatLocalized('%b %d');
            $aTrx['dias'][] = $oFechaInical->toDateString();
            // Resultados
            $aTrx['resultados'][$oFechaInical->toDateString()] = [
                'total' => [
                    'total' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 100, 'porcentaje_monto' => 100, 'label' => 'Total'],
                    'aceptadas' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 0, 'porcentaje_monto' => 0, 'label' => 'Aceptado'],
                    'rechazadas' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 0, 'porcentaje_monto' => 0, 'label' => 'Rechazo', 'respuestas' => []],
                    'fraude' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 0, 'porcentaje_monto' => 0, 'label' => 'Fraudes'],
                    'otros' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 0, 'porcentaje_monto' => 0, 'label' => 'Otros'],
                ],
                'tarjeta-credito' => [
                    'total' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 100, 'porcentaje_monto' => 100, 'label' => 'Total'],
                    'aceptadas' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 0, 'porcentaje_monto' => 0, 'label' => 'Aceptado'],
                    'rechazadas' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 0, 'porcentaje_monto' => 0, 'label' => 'Rechazo'],
                    'fraude' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 0, 'porcentaje_monto' => 0, 'label' => 'Fraudes'],
                    'otros' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 0, 'porcentaje_monto' => 0, 'label' => 'Otros'],
                ],
                'tarjeta-debito' => [
                    'total' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 100, 'porcentaje_monto' => 100, 'label' => 'Total'],
                    'aceptadas' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 0, 'porcentaje_monto' => 0, 'label' => 'Aceptado'],
                    'rechazadas' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 0, 'porcentaje_monto' => 0, 'label' => 'Rechazo'],
                    'fraude' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 0, 'porcentaje_monto' => 0, 'label' => 'Fraudes'],
                    'otros' => ['cantidad' => 0, 'monto' => 0, 'porcentaje_cantidad' => 0, 'porcentaje_monto' => 0, 'label' => 'Otros'],
                ]
            ];
            $oFechaInical->addDay();
        }
        foreach ($cTrx as $trx) {
            // Totales
            $aTrx['total']['total']['cantidad'] += $trx->total;
            $aTrx['total']['total']['monto'] += $trx->monto;
            $aTrx['resultados'][$trx->fecha]['total']['total']['cantidad'] += $trx->total;
            $aTrx['resultados'][$trx->fecha]['total']['total']['monto'] += $trx->monto;
            if (in_array($trx->transaccion_estatus_id, [1, 5])) {
                $aTrx['total']['aceptadas']['cantidad'] += $trx->total;
                $aTrx['total']['aceptadas']['monto'] += $trx->monto;
                $aTrx['resultados'][$trx->fecha]['total']['aceptadas']['cantidad'] += $trx->total;
                $aTrx['resultados'][$trx->fecha]['total']['aceptadas']['monto'] += $trx->monto;
                if ($trx->forma_pago == "tarjeta-credito") {
                    $aTrx['tarjeta-credito']['aceptadas']['cantidad'] += $trx->total;
                    $aTrx['tarjeta-credito']['aceptadas']['monto'] += $trx->monto;
                    $aTrx['resultados'][$trx->fecha]['tarjeta-credito']['aceptadas']['cantidad'] += $trx->total;
                    $aTrx['resultados'][$trx->fecha]['tarjeta-credito']['aceptadas']['monto'] += $trx->monto;
                } else if ($trx->forma_pago == "tarjeta-debito") {
                    $aTrx['tarjeta-debito']['aceptadas']['cantidad'] += $trx->total;
                    $aTrx['tarjeta-debito']['aceptadas']['monto'] += $trx->monto;
                    $aTrx['resultados'][$trx->fecha]['tarjeta-debito']['aceptadas']['cantidad'] += $trx->total;
                    $aTrx['resultados'][$trx->fecha]['tarjeta-debito']['aceptadas']['monto'] += $trx->monto;
                }
            } else if (in_array($trx->transaccion_estatus_id, [7, 13])) {
                $aTrx['total']['rechazadas']['cantidad'] += $trx->total;
                $aTrx['total']['rechazadas']['monto'] += $trx->monto;
                $aTrx['resultados'][$trx->fecha]['total']['rechazadas']['cantidad'] += $trx->total;
                $aTrx['resultados'][$trx->fecha]['total']['rechazadas']['monto'] += $trx->monto;
                if ($trx->forma_pago == "tarjeta-credito") {
                    $aTrx['tarjeta-credito']['rechazadas']['cantidad'] += $trx->total;
                    $aTrx['tarjeta-credito']['rechazadas']['monto'] += $trx->monto;
                    $aTrx['resultados'][$trx->fecha]['tarjeta-credito']['rechazadas']['cantidad'] += $trx->total;
                    $aTrx['resultados'][$trx->fecha]['tarjeta-credito']['rechazadas']['monto'] += $trx->monto;
                } else if ($trx->forma_pago == "tarjeta-debito") {
                    $aTrx['tarjeta-debito']['rechazadas']['cantidad'] += $trx->total;
                    $aTrx['tarjeta-debito']['rechazadas']['monto'] += $trx->monto;
                    $aTrx['resultados'][$trx->fecha]['tarjeta-debito']['rechazadas']['cantidad'] += $trx->total;
                    $aTrx['resultados'][$trx->fecha]['tarjeta-debito']['rechazadas']['monto'] += $trx->monto;
                }
            } else if ($trx->transaccion_estatus_id == 8) {
                $aTrx['total']['fraude']['cantidad'] += $trx->total;
                $aTrx['total']['fraude']['monto'] += $trx->monto;
                $aTrx['resultados'][$trx->fecha]['total']['fraude']['cantidad'] += $trx->total;
                $aTrx['resultados'][$trx->fecha]['total']['fraude']['monto'] += $trx->monto;
                if ($trx->forma_pago == "tarjeta-credito") {
                    $aTrx['tarjeta-credito']['fraude']['cantidad'] += $trx->total;
                    $aTrx['tarjeta-credito']['fraude']['monto'] += $trx->monto;
                    $aTrx['resultados'][$trx->fecha]['tarjeta-credito']['fraude']['cantidad'] += $trx->total;
                    $aTrx['resultados'][$trx->fecha]['tarjeta-credito']['fraude']['monto'] += $trx->monto;
                } else if ($trx->forma_pago == "tarjeta-debito") {
                    $aTrx['tarjeta-debito']['fraude']['cantidad'] += $trx->total;
                    $aTrx['tarjeta-debito']['fraude']['monto'] += $trx->monto;
                    $aTrx['resultados'][$trx->fecha]['tarjeta-debito']['fraude']['cantidad'] += $trx->total;
                    $aTrx['resultados'][$trx->fecha]['tarjeta-debito']['fraude']['monto'] += $trx->monto;
                }
            } else {
                $aTrx['total']['otros']['cantidad'] += $trx->total;
                $aTrx['total']['otros']['monto'] += $trx->monto;
                $aTrx['resultados'][$trx->fecha]['total']['otros']['cantidad'] += $trx->total;
                $aTrx['resultados'][$trx->fecha]['total']['otros']['monto'] += $trx->monto;
                if ($trx->forma_pago == "tarjeta-credito") {
                    $aTrx['tarjeta-credito']['otros']['cantidad'] += $trx->total;
                    $aTrx['tarjeta-credito']['otros']['monto'] += $trx->monto;
                    $aTrx['resultados'][$trx->fecha]['tarjeta-credito']['otros']['cantidad'] += $trx->total;
                    $aTrx['resultados'][$trx->fecha]['tarjeta-credito']['otros']['monto'] += $trx->monto;
                } else if ($trx->forma_pago == "tarjeta-debito") {
                    $aTrx['tarjeta-debito']['otros']['cantidad'] += $trx->total;
                    $aTrx['tarjeta-debito']['otros']['monto'] += $trx->monto;
                    $aTrx['resultados'][$trx->fecha]['tarjeta-debito']['otros']['cantidad'] += $trx->total;
                    $aTrx['resultados'][$trx->fecha]['tarjeta-debito']['otros']['monto'] += $trx->monto;
                }
            }
        }
        // Porcentajes
        $aTrx['total']['aceptadas']['porcentaje_cantidad'] = $this->porcentaje($aTrx['total']['aceptadas']['cantidad'], $aTrx['total']['total']['cantidad']);
        $aTrx['total']['rechazadas']['porcentaje_cantidad'] = $this->porcentaje($aTrx['total']['rechazadas']['cantidad'], $aTrx['total']['total']['cantidad']);
        $aTrx['total']['fraude']['porcentaje_cantidad'] = $this->porcentaje($aTrx['total']['fraude']['cantidad'], $aTrx['total']['total']['cantidad']);
        $aTrx['total']['otros']['porcentaje_cantidad'] = $this->porcentaje($aTrx['total']['otros']['cantidad'], $aTrx['total']['total']['cantidad']);
        $aTrx['total']['aceptadas']['porcentaje_monto'] = $this->porcentaje($aTrx['total']['aceptadas']['monto'], $aTrx['total']['total']['monto']);
        $aTrx['total']['rechazadas']['porcentaje_monto'] = $this->porcentaje($aTrx['total']['rechazadas']['monto'], $aTrx['total']['total']['monto']);
        $aTrx['total']['fraude']['porcentaje_monto'] = $this->porcentaje($aTrx['total']['fraude']['monto'], $aTrx['total']['total']['monto']);
        $aTrx['total']['otros']['porcentaje_monto'] = $this->porcentaje($aTrx['total']['otros']['monto'], $aTrx['total']['total']['monto']);
        // Obtiene la suma del total de transacciones y $this->porcentaje de tarjeta de credito y debito
        $aTrx['tarjeta-credito']['total']['cantidad'] = $aTrx['tarjeta-credito']['aceptadas']['cantidad'] + $aTrx['tarjeta-credito']['rechazadas']['cantidad'] + $aTrx['tarjeta-credito']['fraude']['cantidad'] + $aTrx['tarjeta-credito']['otros']['cantidad'];
        $aTrx['tarjeta-credito']['total']['monto'] = $aTrx['tarjeta-credito']['aceptadas']['monto'] + $aTrx['tarjeta-credito']['rechazadas']['monto'] + $aTrx['tarjeta-credito']['fraude']['monto'] + $aTrx['tarjeta-credito']['otros']['monto'];
        $aTrx['tarjeta-debito']['total']['cantidad'] = $aTrx['tarjeta-debito']['aceptadas']['cantidad'] + $aTrx['tarjeta-debito']['rechazadas']['cantidad'] + $aTrx['tarjeta-debito']['fraude']['cantidad'] + $aTrx['tarjeta-debito']['otros']['cantidad'];
        $aTrx['tarjeta-debito']['total']['monto'] = $aTrx['tarjeta-debito']['aceptadas']['monto'] + $aTrx['tarjeta-debito']['rechazadas']['monto'] + $aTrx['tarjeta-debito']['fraude']['monto'] + $aTrx['tarjeta-debito']['otros']['monto'];
        // Obtiene el porcentaje de transacciones por estatus por tarjeta de credito
        $aTrx['tarjeta-credito']['aceptadas']['porcentaje_cantidad'] = $this->porcentaje($aTrx['tarjeta-credito']['aceptadas']['cantidad'], $aTrx['tarjeta-credito']['total']['cantidad']);
        $aTrx['tarjeta-credito']['rechazadas']['porcentaje_cantidad'] = $this->porcentaje($aTrx['tarjeta-credito']['rechazadas']['cantidad'], $aTrx['tarjeta-credito']['total']['cantidad']);
        $aTrx['tarjeta-credito']['fraude']['porcentaje_cantidad'] = $this->porcentaje($aTrx['tarjeta-credito']['fraude']['cantidad'], $aTrx['tarjeta-credito']['total']['cantidad']);
        $aTrx['tarjeta-credito']['otros']['porcentaje_cantidad'] = $this->porcentaje($aTrx['tarjeta-credito']['otros']['cantidad'], $aTrx['tarjeta-credito']['total']['cantidad']);
        $aTrx['tarjeta-credito']['aceptadas']['porcentaje_monto'] = $this->porcentaje($aTrx['tarjeta-credito']['aceptadas']['monto'], $aTrx['tarjeta-credito']['total']['monto']);
        $aTrx['tarjeta-credito']['rechazadas']['porcentaje_monto'] = $this->porcentaje($aTrx['tarjeta-credito']['rechazadas']['monto'], $aTrx['tarjeta-credito']['total']['monto']);
        $aTrx['tarjeta-credito']['fraude']['porcentaje_monto'] = $this->porcentaje($aTrx['tarjeta-credito']['fraude']['monto'], $aTrx['tarjeta-credito']['total']['monto']);
        $aTrx['tarjeta-credito']['otros']['porcentaje_monto'] = $this->porcentaje($aTrx['tarjeta-credito']['otros']['monto'], $aTrx['tarjeta-credito']['total']['monto']);
        $aTrx['tarjeta-debito']['aceptadas']['porcentaje_cantidad'] = $this->porcentaje($aTrx['tarjeta-debito']['aceptadas']['cantidad'], $aTrx['tarjeta-debito']['total']['cantidad']);
        $aTrx['tarjeta-debito']['rechazadas']['porcentaje_cantidad'] = $this->porcentaje($aTrx['tarjeta-debito']['rechazadas']['cantidad'], $aTrx['tarjeta-debito']['total']['cantidad']);
        $aTrx['tarjeta-debito']['fraude']['porcentaje_cantidad'] = $this->porcentaje($aTrx['tarjeta-debito']['fraude']['cantidad'], $aTrx['tarjeta-debito']['total']['cantidad']);
        $aTrx['tarjeta-debito']['otros']['porcentaje_cantidad'] = $this->porcentaje($aTrx['tarjeta-debito']['otros']['cantidad'], $aTrx['tarjeta-debito']['total']['cantidad']);
        $aTrx['tarjeta-debito']['aceptadas']['porcentaje_monto'] = $this->porcentaje($aTrx['tarjeta-debito']['aceptadas']['monto'], $aTrx['tarjeta-debito']['total']['monto']);
        $aTrx['tarjeta-debito']['rechazadas']['porcentaje_monto'] = $this->porcentaje($aTrx['tarjeta-debito']['rechazadas']['monto'], $aTrx['tarjeta-debito']['total']['monto']);
        $aTrx['tarjeta-debito']['fraude']['porcentaje_monto'] = $this->porcentaje($aTrx['tarjeta-debito']['fraude']['monto'], $aTrx['tarjeta-debito']['total']['monto']);
        $aTrx['tarjeta-debito']['otros']['porcentaje_monto'] = $this->porcentaje($aTrx['tarjeta-debito']['otros']['monto'], $aTrx['tarjeta-debito']['total']['monto']);
        foreach($aTrx['resultados'] as $sTmpDate => $aTmpValue) {
            $aTrx['resultados'][$sTmpDate]['total']['aceptadas']['porcentaje_cantidad'] = $this->porcentaje($aTrx['resultados'][$sTmpDate]['total']['aceptadas']['cantidad'], $aTrx['resultados'][$sTmpDate]['total']['total']['cantidad']);
            $aTrx['resultados'][$sTmpDate]['total']['rechazadas']['porcentaje_cantidad'] = $this->porcentaje($aTrx['resultados'][$sTmpDate]['total']['rechazadas']['cantidad'], $aTrx['resultados'][$sTmpDate]['total']['total']['cantidad']);
            $aTrx['resultados'][$sTmpDate]['total']['fraude']['porcentaje_cantidad'] = $this->porcentaje($aTrx['resultados'][$sTmpDate]['total']['fraude']['cantidad'], $aTrx['resultados'][$sTmpDate]['total']['total']['cantidad']);
            $aTrx['resultados'][$sTmpDate]['total']['otros']['porcentaje_cantidad'] = $this->porcentaje($aTrx['resultados'][$sTmpDate]['total']['otros']['cantidad'], $aTrx['resultados'][$sTmpDate]['total']['total']['cantidad']);
            $aTrx['resultados'][$sTmpDate]['total']['aceptadas']['porcentaje_monto'] = $this->porcentaje($aTrx['resultados'][$sTmpDate]['total']['aceptadas']['monto'], $aTrx['resultados'][$sTmpDate]['total']['total']['monto']);
            $aTrx['resultados'][$sTmpDate]['total']['rechazadas']['porcentaje_monto'] = $this->porcentaje($aTrx['resultados'][$sTmpDate]['total']['rechazadas']['monto'], $aTrx['resultados'][$sTmpDate]['total']['total']['monto']);
            $aTrx['resultados'][$sTmpDate]['total']['fraude']['porcentaje_monto'] = $this->porcentaje($aTrx['resultados'][$sTmpDate]['total']['fraude']['monto'], $aTrx['resultados'][$sTmpDate]['total']['total']['monto']);
            $aTrx['resultados'][$sTmpDate]['total']['otros']['porcentaje_monto'] = $this->porcentaje($aTrx['resultados'][$sTmpDate]['total']['otros']['monto'], $aTrx['resultados'][$sTmpDate]['total']['total']['monto']);
            // Obtiene la suma del total de transacciones y $this->porcentaje de tarjeta de credito y debito
            $aTrx['resultados'][$sTmpDate]['tarjeta-credito']['total']['cantidad'] = $aTrx['resultados'][$sTmpDate]['tarjeta-credito']['aceptadas']['cantidad'] + $aTrx['resultados'][$sTmpDate]['tarjeta-credito']['rechazadas']['cantidad'] + $aTrx['resultados'][$sTmpDate]['tarjeta-credito']['fraude']['cantidad'] + $aTrx['resultados'][$sTmpDate]['tarjeta-credito']['otros']['cantidad'];
            $aTrx['resultados'][$sTmpDate]['tarjeta-credito']['total']['monto'] = $aTrx['resultados'][$sTmpDate]['tarjeta-credito']['aceptadas']['monto'] + $aTrx['resultados'][$sTmpDate]['tarjeta-credito']['rechazadas']['monto'] + $aTrx['resultados'][$sTmpDate]['tarjeta-credito']['fraude']['monto'] + $aTrx['resultados'][$sTmpDate]['tarjeta-credito']['otros']['monto'];
            $aTrx['resultados'][$sTmpDate]['tarjeta-debito']['total']['cantidad'] = $aTrx['resultados'][$sTmpDate]['tarjeta-debito']['aceptadas']['cantidad'] + $aTrx['resultados'][$sTmpDate]['tarjeta-debito']['rechazadas']['cantidad'] + $aTrx['resultados'][$sTmpDate]['tarjeta-debito']['fraude']['cantidad'] + $aTrx['resultados'][$sTmpDate]['tarjeta-debito']['otros']['cantidad'];
            $aTrx['resultados'][$sTmpDate]['tarjeta-debito']['total']['monto'] = $aTrx['resultados'][$sTmpDate]['tarjeta-debito']['aceptadas']['monto'] + $aTrx['resultados'][$sTmpDate]['tarjeta-debito']['rechazadas']['monto'] + $aTrx['resultados'][$sTmpDate]['tarjeta-debito']['fraude']['monto'] + $aTrx['resultados'][$sTmpDate]['tarjeta-debito']['otros']['monto'];
            // Obtiene el porcentaje de transacciones por estatus por tarjeta de credito
            $aTrx['resultados'][$sTmpDate]['tarjeta-credito']['aceptadas']['porcentaje_cantidad'] = $this->porcentaje($aTrx['resultados'][$sTmpDate]['tarjeta-credito']['aceptadas']['cantidad'], $aTrx['resultados'][$sTmpDate]['tarjeta-credito']['total']['cantidad']);
            $aTrx['resultados'][$sTmpDate]['tarjeta-credito']['rechazadas']['porcentaje_cantidad'] = $this->porcentaje($aTrx['resultados'][$sTmpDate]['tarjeta-credito']['rechazadas']['cantidad'], $aTrx['resultados'][$sTmpDate]['tarjeta-credito']['total']['cantidad']);
            $aTrx['resultados'][$sTmpDate]['tarjeta-credito']['fraude']['porcentaje_cantidad'] = $this->porcentaje($aTrx['resultados'][$sTmpDate]['tarjeta-credito']['fraude']['cantidad'], $aTrx['resultados'][$sTmpDate]['tarjeta-credito']['total']['cantidad']);
            $aTrx['resultados'][$sTmpDate]['tarjeta-credito']['otros']['porcentaje_cantidad'] = $this->porcentaje($aTrx['resultados'][$sTmpDate]['tarjeta-credito']['otros']['cantidad'], $aTrx['resultados'][$sTmpDate]['tarjeta-credito']['total']['cantidad']);
            $aTrx['resultados'][$sTmpDate]['tarjeta-credito']['aceptadas']['porcentaje_monto'] = $this->porcentaje($aTrx['resultados'][$sTmpDate]['tarjeta-credito']['aceptadas']['monto'], $aTrx['resultados'][$sTmpDate]['tarjeta-credito']['total']['monto']);
            $aTrx['resultados'][$sTmpDate]['tarjeta-credito']['rechazadas']['porcentaje_monto'] = $this->porcentaje($aTrx['resultados'][$sTmpDate]['tarjeta-credito']['rechazadas']['monto'], $aTrx['resultados'][$sTmpDate]['tarjeta-credito']['total']['monto']);
            $aTrx['resultados'][$sTmpDate]['tarjeta-credito']['fraude']['porcentaje_monto'] = $this->porcentaje($aTrx['resultados'][$sTmpDate]['tarjeta-credito']['fraude']['monto'], $aTrx['resultados'][$sTmpDate]['tarjeta-credito']['total']['monto']);
            $aTrx['resultados'][$sTmpDate]['tarjeta-credito']['otros']['porcentaje_monto'] = $this->porcentaje($aTrx['resultados'][$sTmpDate]['tarjeta-credito']['otros']['monto'], $aTrx['resultados'][$sTmpDate]['tarjeta-credito']['total']['monto']);
            $aTrx['resultados'][$sTmpDate]['tarjeta-debito']['aceptadas']['porcentaje_cantidad'] = $this->porcentaje($aTrx['resultados'][$sTmpDate]['tarjeta-debito']['aceptadas']['cantidad'], $aTrx['resultados'][$sTmpDate]['tarjeta-debito']['total']['cantidad']);
            $aTrx['resultados'][$sTmpDate]['tarjeta-debito']['rechazadas']['porcentaje_cantidad'] = $this->porcentaje($aTrx['resultados'][$sTmpDate]['tarjeta-debito']['rechazadas']['cantidad'], $aTrx['resultados'][$sTmpDate]['tarjeta-debito']['total']['cantidad']);
            $aTrx['resultados'][$sTmpDate]['tarjeta-debito']['fraude']['porcentaje_cantidad'] = $this->porcentaje($aTrx['resultados'][$sTmpDate]['tarjeta-debito']['fraude']['cantidad'], $aTrx['resultados'][$sTmpDate]['tarjeta-debito']['total']['cantidad']);
            $aTrx['resultados'][$sTmpDate]['tarjeta-debito']['otros']['porcentaje_cantidad'] = $this->porcentaje($aTrx['resultados'][$sTmpDate]['tarjeta-debito']['otros']['cantidad'], $aTrx['resultados'][$sTmpDate]['tarjeta-debito']['total']['cantidad']);
            $aTrx['resultados'][$sTmpDate]['tarjeta-debito']['aceptadas']['porcentaje_monto'] = $this->porcentaje($aTrx['resultados'][$sTmpDate]['tarjeta-debito']['aceptadas']['monto'], $aTrx['resultados'][$sTmpDate]['tarjeta-debito']['total']['monto']);
            $aTrx['resultados'][$sTmpDate]['tarjeta-debito']['rechazadas']['porcentaje_monto'] = $this->porcentaje($aTrx['resultados'][$sTmpDate]['tarjeta-debito']['rechazadas']['monto'], $aTrx['resultados'][$sTmpDate]['tarjeta-debito']['total']['monto']);
            $aTrx['resultados'][$sTmpDate]['tarjeta-debito']['fraude']['porcentaje_monto'] = $this->porcentaje($aTrx['resultados'][$sTmpDate]['tarjeta-debito']['fraude']['monto'], $aTrx['resultados'][$sTmpDate]['tarjeta-debito']['total']['monto']);
            $aTrx['resultados'][$sTmpDate]['tarjeta-debito']['otros']['porcentaje_monto'] = $this->porcentaje($aTrx['resultados'][$sTmpDate]['tarjeta-debito']['otros']['monto'], $aTrx['resultados'][$sTmpDate]['tarjeta-debito']['total']['monto']);
        }
        return $aTrx;
    }

    /**
     * Obtiene estadísticas del mes
     *
     * @param  array  $aFiltros  Filtros de transacciones ver $this->transaccion_filtra
     * @param  \Illuminate\Http\Request  $oFecha
     *
     * @return  Collection  Regresa colección de las transacciones del mes.
     */
    public function mes_xdia(array $aFiltros = null, Carbon $oFecha = null): Collection
    {
        // Define fecha
        $oFecha = $oFecha ?? Carbon::now();

        // Realiza query de las transacciones
        $cTrx = $this->oTransaccion
            ->select(DB::raw("DAY(created_at) as dia, COUNT(*) AS total, SUM(monto) AS monto "))
            ->where(
                function ($q) {
                    if (!empty($this->sComercio)) {
                        return $q
                            ->where('comercio_uuid', $this->sComercio);
                    }
                }
            )
            ->whereBetween('created_at', [$oFecha->copy()->startOfMonth(), $oFecha->copy()->endOfMonth()])
            ->where(
                function ($q) use ($aFiltros) {
                    return $this->transaccion_filtra($q, $aFiltros);
                }
            )
            ->groupBy(DB::raw("DAY(created_at)"))
            ->get();

        // Inicia colección vacía
        $aTmp = [];
        foreach(range(1, $oFecha->copy()->endOfMonth()->day) as $i) {
            $aTmp[$i] = (object) [
                'dia' => $i,
                'total' => 0,
                'monto' => 0,
            ];
        }
        // Inserta coleccion en arreglo vacío
        foreach($cTrx as $oTrx) {
            $aTmp[$oTrx->dia] = (object) array_merge((array) $aTmp[$oTrx->dia], $oTrx->toArray());
        }
        return collect($aTmp);
    }

    /**
     * Obtiene totales de transacciones del mes
     *
     * @param  array  $aFiltros  Filtros de transacciones ver $this->transaccion_filtra
     * @param  \Illuminate\Http\Request  $oFecha
     *
     * @return  array  Regresa arreglo con los totales del mes: ['mes', 'total', 'monto']
     */
    public function total_mes(array $aFiltros = null, Carbon $oFecha = null): array
    {
        // Define fecha
        $oFecha = $oFecha ?? Carbon::now();

        // Realiza query de las transacciones
        $oTrx = $this->oTransaccion
            ->select(DB::raw("MONTH(created_at) AS mes, COUNT(*) AS total, SUM(monto) AS monto"))
            ->where(
                function ($q) {
                    if (!empty($this->sComercio)) {
                        return $q
                            ->where('comercio_uuid', $this->sComercio);
                    }
                }
            )
            ->whereBetween('created_at', [$oFecha->copy()->startOfMonth(), $oFecha->copy()->endOfMonth()])
            ->where(
                function ($q) use ($aFiltros) {
                    return $this->transaccion_filtra($q, $aFiltros);
                }
            )
            ->groupBy(DB::raw("MONTH(created_at)"))
            ->get();

        if ($oTrx->isEmpty()) {
            return [
                'mes' => $oFecha->copy()->month,
                'total' => 0,
                'monto' => 0,
            ];
        }
        return $oTrx->first()->toArray();
    }

    // }}}

}