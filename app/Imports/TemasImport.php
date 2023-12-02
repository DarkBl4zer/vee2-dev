<?php
namespace App\Imports;

use App\Models\TemasImportModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TemasImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new TemasImportModel([
            'tema_principal' => $row[0],
            'acta' => $row[1],
            'tema_secundario' => $row[2]
         ]);
    }

}
