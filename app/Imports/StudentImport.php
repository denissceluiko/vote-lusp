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
            $program = $this->getProgram($row);


            if ($program == null) {
                $program = $this->createProgram($row);
            }

            $program->students()->updateOrCreate([
                'sid' => trim($row['student_id'])
            ], [
                'name' => trim($row['name']),
                'surname' => trim($row['surname']),
                'status' => trim($row['status']),
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
     * @param array $row
     * @return Program|null
     */
    public function getProgram($row)
    {
        if ($this->program == null || $this->program->code != $row['program_code'])
        {
            $this->program = Program::byCode($row['program_code'])->first();
        }
        if ($this->program == null)
        {
            $this->program = Program::byName($row['program_name'])->first();
        }
        return $this->program;
    }

    public function createProgram($row)
    {
        $faculty = $this->getFaculty($row['faculty_name']);

        if ($faculty == null) {
            Log::warning("Faculty {$row['faculty_name']} not found.");
            return;
        }

        return $faculty->programs()->create([
            'code' => trim($row['program_code']),
            'name' => trim($row['program_name']),
        ]);
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
