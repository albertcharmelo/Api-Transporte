<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class LiquidacionesExport implements FromCollection, WithHeadings, ShouldAutoSize, WithColumnWidths, WithStyles
{

    use Exportable;

    public function __construct($title)
    {
        $this->title = $title;
    }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
       $user = User::where('type_user',2)
            ->has('datos_bancarios')
            ->with('wallet')
            ->with('datos_bancarios')
            ->with('liquidacion_pendiente')
            ->get();

       //map $user to array and get only the fields we need to export
        $userResults = $user->map(function($user){
            if ($user->wallet->creditos != 0) {
                if (isset($user->liquidacion_pendiente)) {
                    return [
                        $user->full_name,
                        $user->datos_bancarios->numero_de_cuenta,
                        $user->datos_bancarios->id_card,
                        $user->datos_bancarios->tipo_cuenta,
                        $user->datos_bancarios->banco,
                        $user->liquidacion_pendiente->monto_liquidar,
                    ];
                }else{
                    
                    return [
                        $user->full_name,
                        $user->datos_bancarios->numero_de_cuenta,
                        $user->datos_bancarios->id_card,
                        $user->datos_bancarios->tipo_cuenta,
                        $user->datos_bancarios->banco,
                        $user->wallet->creditos,
                    ];
                }
            }
        });
        
        //filter $userResults to get only not null values
        $userResults = $userResults->filter(function($user){
            return $user != null;
        });

        dd($userResults);
        
        foreach ($user as $key => $user) {
            if ($user->wallet->creditos != 0) {
                if (isset($user->liquidacion_pendiente)) {
                    if ($user->liquidacion_pendiente->monto_liquidar < $user->wallet->creditos) {
                        $user->wallet->creditos =  $user->wallet->creditos - $user->liquidacion_pendiente->monto_liquidar;
                        $user->wallet->save();
                        $user->liquidacion_pendiente->delete();
                    }
                }else{
                    $user->wallet->creditos = 0;
                    $user->wallet->save();
                }
            }
        }

        
        return collect($userResults);
        


    }


    public function headings(): array
    {
        return [
         [$this->title],
         [  'Nombre',
            'Numero de Cuenta',
            'Cedula de Identidad',
            'Tipo de Cuenta',
            'Banco',
            'Importe',
         ]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 30,
            'C' => 15,
            'D' => 15,
            'E' => 15,
            'F' => 10,
        ];
    }

    public function styles(Worksheet $sheet)
    {

        $sheet->mergeCells('A1:F1');
        //font size and align center text A2:L2
        $sheet->getStyle('A2:F2')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A2:F2')->getAlignment()->setVertical('center');


        $sheet->getStyle('A2:F2')->getFont()->setBold(true);
        $sheet->getStyle('A2:F2')->getFont()->setSize(15);
        $sheet->getStyle('A2:F2')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('A2:F2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A2:F2')->getFill()->getStartColor()->setARGB('FFF9A11F');
        
        //font size and align center text A1:L1
        $sheet->getStyle('A3:F3')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A3:F3')->getAlignment()->setVertical('center');
        
        return $sheet;
    }
}
