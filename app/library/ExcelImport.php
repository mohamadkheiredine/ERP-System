<?php
namespace App\library;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ExcelImport implements ToModel, WithHeadingRow
{
    use Importable;

    public function model(array $row)
    {
        // You can transform the data here if necessary before it gets returned
        return $row;
    }
}