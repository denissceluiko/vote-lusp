<?php

namespace App\Imports;

use App\Faculty;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FacultyImport implements ToModel, WithHeadingRow, SkipsOnError
{
    use SkipsErrors;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Faculty([
            'name' => trim($row['name']),
            'name_eng' => trim($row['name_eng']),
            'abbreviation' => trim($row['abbreviation']),
        ]);
    }
}
