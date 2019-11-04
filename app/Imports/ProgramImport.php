<?php

namespace App\Imports;

use App\Faculty;
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
            if ($faculty->name != $row['faculty']) {
                $faculty = Faculty::byName($row['faculty'])->first();
            }
            $faculty->programs()->updateOrCreate([
                'code' => trim($row['code'])
                ],[
                'lri' => trim($row['lri']),
                'name' => trim($row['name'])
            ]);
        }
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
