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

            $emails = $this->resolveEmails($row);

            $program->students()->updateOrCreate([
                'sid' => trim($row['student_id'])
            ], [
                'name' => trim($row['name']),
                'surname' => trim($row['surname']),
                'status' => trim($row['status']),
                'ballot_emails' => $this->resolveEmails($row),
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

    public function resolveEmails($row)
    {
        $primary = strlen($row['email_primary']) ? array_reverse(explode(' ', $row['email_primary'])) : [];
        $emails = array_unique(array_merge($primary, [$row['email_secondary'], $row['email_tertiary']])); // primary, secondary, tertiary

        $emails = array_filter($emails, function ($email) {
            return !empty($email);
        });
        $emails = array_values($emails);

        $emails[0] = $this->formatPrimaryEmail($emails[0]);
        return implode(';', $emails);
    }


    public function formatPrimaryEmail(string $email)
    {
        // options -> aa00000@students.lu.lv, aa00000@lu.lv, name.surname@lu.lv, username@lu.lv, smth else
        $allowedDomains = [
            'students.lu.lv',
            'lu.lv'
        ];

        list($user, $domain) = explode('@', $email);

        if (!in_array($domain, $allowedDomains)) return $email; // not LU domain
        if ($domain == 'students.lu.lv') return $email; // @students.lu.lv

        preg_match('/^[a-z]{2}[0-9]{5}$/', $user, $matches); // aa00000@lu.lv
        if (!empty($matches)) return $user.'@students.lu.lv';

        return $email; // other @lu.lv emails
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
