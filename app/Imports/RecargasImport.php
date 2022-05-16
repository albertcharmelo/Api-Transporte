<?php

namespace App\Imports;

use App\Recarga;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
class RecargasImport implements ToModel,WithHeadingRow
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {   

        $existRecarga = Recarga::where('referencia',$row['referencia'])->first();
        if ($row['credito'] != 0 && $existRecarga == null) {
            return new Recarga([
                'referencia' => $row['referencia'],
                'creditos'=>$row['monto'],
            ]);
        }
        
    }
}
