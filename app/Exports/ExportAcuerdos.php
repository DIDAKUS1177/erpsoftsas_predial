<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class ExportAcuerdos implements FromView, WithTitle
{
    protected $usuario;
    protected $fechaInicial;
    protected $fechaFinal;

    public function __construct(string $usuario, string $fechaInicial, string $fechaFinal) {
        $this->usuario = $usuario;
        $this->fechaInicial = $fechaInicial;
        $this->fechaFinal = $fechaFinal;
    }

    public function title(): string {
        return 'reporte_acuerdos';
    }

    public function view(): View {
        $parametro_logo = DB::table('parametros')
                              ->select('parametros.valor')
                              ->where('parametros.nombre', 'logo')
                              ->first();

        $parametro_nit = DB::table('parametros')
                              ->select('parametros.valor')
                              ->where('parametros.nombre', 'nit')
                              ->first();

        $parametro_alcaldia = DB::table('parametros')
                    ->select('parametros.valor')
                    ->where('parametros.nombre', 'alcaldia')
                    ->first();

        $acuerdos = DB::table('predios_acuerdos_pago AS acuerdos')
                ->join('usuarios', 'usuarios.id', '=', 'acuerdos.id_usuario_crea')
                ->join('predios', 'predios.id', '=', 'acuerdos.id_predio')
                ->select('acuerdos.*', 'predios.codigo_predio', 'usuarios.nombres', 'usuarios.apellidos')
                ->where('acuerdos.estado_acuerdo', 1)
                ->whereRaw(DB::raw("acuerdos.created_at >= CONVERT(datetime, '" . $this->fechaInicial . " 00:00:00.000')"))
                ->whereRaw(DB::raw("acuerdos.created_at <= CONVERT(datetime, '" . $this->fechaFinal . " 23:59:59.999')"))
                ->orderBy('acuerdos.created_at', 'desc')
                ->get();

        return view('exports.reporteAcuerdosEXCEL', [
            'registros' => $acuerdos,
            'usuario' => $this->usuario,
            'logo' => $parametro_logo->valor,
            'nit' => $parametro_nit->valor,
            'alcaldia' => $parametro_alcaldia->valor,
            'fecha' => Carbon::now()->format('d/m/Y')
        ]);
    }
}
