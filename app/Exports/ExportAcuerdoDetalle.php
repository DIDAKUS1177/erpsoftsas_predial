<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class ExportAcuerdoDetalle implements FromView, WithTitle
{
    protected $usuario;
    protected $acuerdoPago;
    protected $registros;
    protected $logo;
    protected $nit;
    protected $alcaldia;
    protected $fecha;

    public function __construct($usuario, $acuerdoPago, $registros, $logo, $nit, $alcaldia, $fecha) {
        $this->usuario     = $usuario;
        $this->acuerdoPago = $acuerdoPago;
        $this->registros   = $registros;
        $this->logo        = $logo;
        $this->nit         = $nit;
        $this->alcaldia    = $alcaldia;
        $this->fecha       = $fecha;
    }

    public function title(): string {
        return 'cuotas_acuerdo';
    }

    public function view(): View {
        return view('exports.reporteAcuerdoDetalleEXCEL', [
            'registros'    => $this->registros,
            'acuerdo_pago' => $this->acuerdoPago,
            'usuario'      => $this->usuario,
            'logo'         => $this->logo,
            'nit'          => $this->nit,
            'alcaldia'     => $this->alcaldia,
            'fecha'        => $this->fecha,
        ]);
    }
}
