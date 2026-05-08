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

class ExportExenciones implements FromView, WithTitle
    // implements FromCollection, WithHeadings, WithCustomStartCell, ShouldAutoSize,
    // WithColumnFormatting, WithMapping, WithStyles, WithDrawings //, WithDefaultStyles, FromView
{
    // use Exportable;

    protected $fechaInicial;
    protected $fechaFinal;

    public function __construct(string $fechaInicial, string $fechaFinal) {
        $this->fechaInicial = $fechaInicial;
        $this->fechaFinal = $fechaFinal;
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

        return view('exports.reporteExencionesEXCEL', [
            'registros' => DB::table('predios_exenciones')
                            ->leftJoin('predios', 'predios.id', '=', 'predios_exenciones.id_predio')
                            ->select(DB::raw('predios_exenciones.*, predios.codigo_predio'))
                            ->where('predios.estado', 1)
                            ->whereRaw(DB::raw("predios_exenciones.created_at >= CONVERT(datetime, '" . $this->fechaInicial . " 00:00:00.000')"))
                            ->whereRaw(DB::raw("predios_exenciones.created_at <= CONVERT(datetime, '" . $this->fechaFinal . " 23:59:59.999')"))
                            ->orderBy('predios_exenciones.created_at')
                            ->orderBy('predios.codigo_predio')
                            ->get(),
            'logo' => $parametro_logo->valor,
            'nit' => $parametro_nit->valor,
            'alcaldia' => $parametro_alcaldia->valor,
            'fecha_inicial' => $this->fechaInicial,
            'fecha_final' => $this->fechaFinal
        ]);
    }
}
