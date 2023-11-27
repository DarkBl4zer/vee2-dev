<?php

namespace App\Imports;



use App\Models\ActasModel;
use App\Models\TemasPModel;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Validators\ValidationException as THError;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class TemasImport implements OnEachRow, WithChunkReading
{
    private $id_delegada = 0;

    public function __construct(int $idDelegada)
    {
        $this->id_delegada = $idDelegada;
    }

    public function onRow(Row $row)
    {
        $rowIndex = $row->getIndex();
        $row = $row->toArray();

        if ($rowIndex > 1) {
            $nivel = 1;
            $id_padre = null;
            $acta = ActasModel::where('id_delegada', $this->id_delegada)->where('activo', true)->where('descripcion', strtoupper(trim($row[1])))->first();
            if (is_null($acta)) {
                $error = ['El acta con nombre ('.$row[1].') no se encontró.'];
                $failures[] = new Failure($rowIndex, 'acta', $error, $row);
                throw new THError(ValidationException::withMessages($error), $failures);
            } else{
                $id_acta = $acta->id;
            }
            if ($row[2] != '' && $row[2] != ' ' && !is_null($row[2])) {
                $padre = TemasPModel::where('id_delegada', $this->id_delegada)->where('nivel', 1)->where('activo', true)->where('eliminado', false)->where('nombre', strtoupper(trim($row[2])))->first();
                if (is_null($padre)) {
                    $error = ['El tema principal padre ('.$row[2].') no se encontró.'];
                    $failures[] = new Failure($rowIndex, 'acta', $error, $row);
                    throw new THError(ValidationException::withMessages($error), $failures);
                } else{
                    $id_padre = $padre->id;
                    $nivel = 2;
                }
            }
            TemasPModel::create([
                'id_delegada' => $this->id_delegada,
                'nombre' => strtoupper(trim($row[0])),
                'nivel' => $nivel,
                'activo' => true,
                'eliminado' => false,
                'id_acta' => $id_acta,
                'id_padre' => $id_padre
            ]);
        }
    }
    public function chunkSize(): int
    {
        return 1;
    }
}
