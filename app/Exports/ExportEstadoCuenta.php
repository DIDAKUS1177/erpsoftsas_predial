<?php

namespace App\Exports;

use App\Models\ViewPredialFacturado;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportEstadoCuenta implements FromView, WithTitle
    // implements FromCollection, WithHeadings, WithCustomStartCell, ShouldAutoSize,
    // WithColumnFormatting, WithMapping, WithStyles, WithDrawings //, WithDefaultStyles, FromView
{
    // use Exportable;

    protected $idpredio;
    protected $usuario;

    public function __construct(string $idpredio, string $usuario) {
        $this->idpredio = $idpredio;
        $this->usuario = $usuario;
    }

    // /**
    // * @return \Illuminate\Support\Collection
    // */
    // public function headings():array {
    //     return[
    //         'Año',
    //         'Factura',
    //         'Codigo predio',
    //         'Propietario',
    //         'Predial presente año',
    //         'Interes predial',
    //         'Car',
    //         'Interes Car',
    //         'Total vigencia'
    //     ];
    // }

    // /**
    // * @return \Illuminate\Support\Collection
    // */
    // public function collection() {
    //     return ViewPredialFacturado::select(
    //         'ultimo_anio',
    //         'factura_pago',
    //         'codigo_predio',
    //         'nombre_propietario',
    //         'predial',
    //         'interespredial',
    //         'car',
    //         'interescar',
    //         'totalvigencia',
    //     )
    //     ->where('ultimo_anio', '<=', Carbon::now()->year)
    //     ->orderBy('codigo_predio', 'asc')
    //     ->orderBy('ultimo_anio', 'asc')
    //     ->get();
    // }

    // public function startCell(): string {
    //     return 'A7';
    // }

    // public function map($row): array {
    //     return [
    //         $row->ultimo_anio,
    //         $row->factura_pago,
    //         $row->codigo_predio,
    //         $row->nombre_propietario,
    //         $row->predial,
    //         $row->interespredial,
    //         $row->car,
    //         $row->interescar,
    //         $row->totalvigencia,
    //     ];
    // }

    // public function columnFormats(): array {
    //     return [
    //         'E' => NumberFormat::FORMAT_CURRENCY_USD,
    //         'F' => NumberFormat::FORMAT_CURRENCY_USD,
    //         'G' => NumberFormat::FORMAT_CURRENCY_USD,
    //         'H' => NumberFormat::FORMAT_CURRENCY_USD,
    //         'I' => NumberFormat::FORMAT_CURRENCY_USD,
    //     ];
    // }

    // // public function defaultStyles(Style $defaultStyle)
    // // {
    // //     // Configure the default styles
    // //     // return $defaultStyle->getFill()->setFillType(Fill::FILL_SOLID);

    // //     // Or return the styles array
    // //     return [
    // //         'fill' => [
    // //             'fillType'   => Fill::FILL_SOLID,
    // //             'startColor' => ['argb' => Color::COLOR_BLUE],
    // //         ],
    // //     ];
    // // }

    // public function drawings() {
    //     $drawing = new Drawing();
    //     $drawing->setName('Logo');
    //     $drawing->setDescription('Logo');
    //     $drawing->setPath(public_path('/theme/plugins/images/' . $this->pathImage));
    //     $drawing->setHeight(76);
    //     $drawing->setCoordinates('B2');

    //     return $drawing;
    // }

    // public function styles(Worksheet $sheet) {
    //     return [
    //         // Style the first row as bold text.
    //         7 => [
    //             'font' => ['bold' => true],
    //             'borders' => [
    //                 'allBorders' => [
    //                     'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
    //                     'color' => ['rgb' => '000000'],
    //                 ],
    //             ],
    //             'fill' => [
    //                 'fillType'   => Fill::FILL_SOLID,
    //                 'startColor' => ['argb' => 'ededed'],
    //             ],
    //         ],
    //         // Styling a specific cell by coordinate.
    //         // 'B2' => ['font' => ['italic' => true]],

    //         // Styling an entire column.
    //         // 'C'  => ['font' => ['size' => 16]],
    //     ];
    // }

    public function title(): string {
        return 'reporte';
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

        $predio =  DB::table('predios')
                    ->where('predios.estado', 1)
                    ->where('predios.id', $this->idpredio)
                    ->first();

        $predio_pago =  DB::table('predios_pagos')
                    ->where('pagado', '<>', 0)
                    ->where('anulada', 0)
                    ->where('id_predio', $this->idpredio)
                    ->orderBy('ultimo_anio', 'desc')
                    ->first();

        $propietario_ppal = DB::table('predios')
                            ->join('predios_propietarios', 'predios.id', '=', 'predios_propietarios.id_predio')
                            ->join('propietarios', 'propietarios.id', '=', 'predios_propietarios.id_propietario')
                            ->select(DB::raw('propietarios.*, predios_propietarios.jerarquia'))
                            ->where('predios_propietarios.jerarquia', '001')
                            ->where('predios.estado', 1)
                            ->where('predios.id', $this->idpredio)
                            ->first();

        return view('exports.reporteEstadoCuentaEXCEL', [
            'registros' => DB::select('select pp.id_predio as id,
                                                pp.ultimo_anio as vigencia,
                                                ISNULL(pp.avaluo, 0) as avaluo,
                                                ISNULL(pp.valor_concepto1, 0) as impuesto,
                                                ISNULL(pp.valor_concepto2, 0) as interes_impuesto,
                                                ISNULL(pp.valor_concepto3, 0) as car,
                                                ISNULL(pp.valor_concepto4, 0) as interes_car,
                                                ISNULL(pp.valor_concepto13, 0) + ISNULL(pp.valor_concepto15, 0) as descuento,
                                                ISNULL(pp.valor_concepto16, 0) as otros,
                                                ISNULL(pp.total_calculo, 0) as total,
                                                CASE WHEN ISNULL(pp.acuerdo, 0) != 0 THEN \'SI\' ELSE \'NO\' END as acuerdo
                                        from predios_pagos pp
                                        where
                                            pp.id_predio = '. $this->idpredio .' and
                                            pp.pagado = 0 and
                                            pp.anulada = 0
                                        order by pp.ultimo_anio'),
            'usuario' => $this->usuario,
            'predio' => $predio,
            'predio_pago' => $predio_pago,
            'propietario_ppal' => $propietario_ppal,
            'logo' => $parametro_logo->valor,
            'nit' => $parametro_nit->valor,
            'alcaldia' => $parametro_alcaldia->valor,
            'fecha' => Carbon::now()->format('d/m/Y')
        ]);
    }
}
