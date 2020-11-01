<?php

namespace App\Imports;

use App\Faculty;
use App\Program;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProgramImport implements ToCollection, WithHeadingRow, SkipsOnError, WithBatchInserts, WithChunkReading
{
    use SkipsErrors;

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $faculty = new Faculty;
        foreach ($collection as $row) {
            if (empty($row['faculty'])) {
                $this->tryUpdate($row);
                continue;
            }

            if (!$faculty || $faculty->abbreviation != $row['faculty']) {
                $faculty = Faculty::byAbbreviation($row['faculty'])->first();
            }

            if (!$faculty) {
                $this->tryUpdate($row);
                continue;
            }

            $faculty->programs()->updateOrCreate([
                'code' => trim($row['code'])
                ],[
                'lri' => trim($row['lri']),
                'name' => trim($row['name']),
                'name_eng' => trim($row['name_eng']),
            ]);
        }
    }

    public function tryUpdate($row)
    {
        if (empty($row['code'])) return;
        $program = Program::byCode($row['code'])->first();

        if (!$program) return;
        $program->update([
            'lri' => trim($row['lri']),
            'name' => trim($row['name']),
            'name_eng' => trim($row['name_eng']),
        ]);
    }

    /**
     * @return int
     */
    public function batchSize(): int
    {
        return 100;
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 100;
    }
}
