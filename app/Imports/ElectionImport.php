<?php

namespace App\Imports;

use App\Election;
use App\Faculty;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ElectionImport implements ToCollection, WithHeadingRow, SkipsOnError
{
    use SkipsErrors;

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $collection->each(function ($row) {
            $election = Election::create([
                'name' => trim($row['name']),
                'name_short' => trim($row['name_short']),
            ]);

            if(!empty($row['faculty'])) {
                $this->tryFaculty($election, $row['faculty']);
            }
        });
    }

    private function tryFaculty(Election $election, $faculty)
    {
        $faculty = Faculty::byAbbreviation($faculty)->first();
        if (!$faculty) return;
        $election->faculty()->associate($faculty)->save();
    }
}
