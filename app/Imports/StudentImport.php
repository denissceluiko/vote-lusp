<?php

namespace App\Imports;

use App\Faculty;
use App\Program;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentImport
    implements ToCollection, WithHeadingRow, SkipsOnError, SkipsOnFailure, WithBatchInserts, WithChunkReading, ShouldQueue
{
    use Importable;
    use SkipsErrors;
    use SkipsFailures;

    protected $faculty = null;
    protected $program = null;

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {

        foreach ($collection as $row) {
            $faculty = $this->getFaculty($row['faculty']);
            $program = $this->getProgram($row['program_code']);

            if ($faculty == null) {
                Log::warning("Faculty {$row['faculty']} not found.");
                continue;
            }

            if ($program == null) {
                Log::warning("Program {$row['program_code']} of {$row['faculty']} for ^{$row['student_id']}# not found.");
            }

            $faculty->students()->updateOrCreate([
                'sid' => trim($row['student_id'])
            ], [
                'name' => trim($row['name']),
                'surname' => trim($row['surname']),
                'status' => trim($row['status']),
                'program_id' => $program->id ?? null,
            ]);
        }
    }

    /**
     * @param string $name
     * @return Faculty|null
     */
    public function getFaculty(string $name)
    {
        if ($this->faculty == null || $this->faculty->name != $name)
        {
            $this->faculty = Faculty::byName($name)->first();
        }
        return $this->faculty;
    }

    /**
     * @param string $code
     * @return Program|null
     */
    public function getProgram(string $code)
    {
        if ($this->program == null || $this->program->code != $code)
        {
            $this->program = Program::byCode($code)->first();
        }
        return $this->program;
    }

    public function batchSize(): int
    {
        return 200;
    }

    public function chunkSize(): int
    {
        return 200;
    }
}
