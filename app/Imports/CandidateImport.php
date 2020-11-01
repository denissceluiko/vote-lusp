<?php

namespace App\Imports;

use App\Faculty;
use App\Party;
use App\Student;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CandidateImport implements ToCollection, WithHeadingRow, SkipsOnError, SkipsOnFailure
{
    use SkipsFailures, SkipsErrors;

    protected $faculty = null;
    protected $party = null;

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $collection->each(function ($candidate) {
            $candidate = array_map('trim', iterator_to_array($candidate));
            $faculty = $this->getFaculty($candidate['faculty']);
            $party = $this->getParty($candidate['party']);

            if (!$this->party) {
                $party = $this->party = Party::create([
                    'name' => $candidate['party'],
                    'faculty_id' => $faculty->id,
                    'number' => $candidate['party_number'] ?? null,
                ]);
            }

            $student = $this->findStudent($candidate);
            $student->update([
                'phone' => $candidate['phone'],
                'email' => $candidate['email'],
            ]);

            $party->candidates()->updateOrCreate([
                'student_id' => $student->id,
            ], []);
        });
    }

    /**
     * @param string $abbreviation
     * @return Faculty|null
     */
    public function getFaculty(string $abbreviation)
    {
        if ($this->faculty == null || $this->faculty->abbreviation != $abbreviation)
        {
            $this->faculty = Faculty::byAbbreviation($abbreviation)->first();
        }
        return $this->faculty;
    }

    public function getParty(string $name)
    {
        if ($this->party == null || $this->party->name != $name)
        {
            $this->party = Party::byName($name)->first();
        }
        return $this->party;
    }

    public function findStudent($candidate)
    {
        $student = Student::bySID($candidate['student_id'])->first();

        if ($student && $this->checkStudent($student, $candidate)) {
            Log::info("Found by SID {$candidate['name']} {$candidate['surname']} ({$candidate['student_id']})");
            return $student;
        }

        $student = Student::where([
            'name' => $candidate['name'],
            'surname' => $candidate['surname'],
        ])->first();

        if ($student && $this->checkStudent($student, $candidate)) {
            Log::info("Found by name {$candidate['name']} {$candidate['surname']}, wrong SID {$candidate['student_id']} instead of {$student->sid}");
            return $student;
        }

        Log::info("{$candidate['name']} {$candidate['surname']} not found, failing.");
        return null;
    }

    public function checkStudent($student, $candidate)
    {
        if ($student->name != $candidate['name'] && $student->surname != $candidate['surname']) {
            Log::info("{$student->name} {$student->surname} ({$student->sid}) is not {$candidate['name']} {$candidate['surname']} ({$candidate['student_id']})");
            return false;
        }
        return true;
    }

    private function clean($candidate)
    {
        foreach ($candidate as &$cell) {
            $cell = trim($cell);
        }
        return $candidate;
    }
}
