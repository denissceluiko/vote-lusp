<?php

namespace App\Imports;

use App\Candidate;
use App\Faculty;
use App\Party;
use App\Student;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
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
            $faculty = $this->getFaculty($candidate['faculty']);
            $party = $this->getParty($candidate['party']);

            if (!$this->party) {
                $party = $this->party = Party::create([
                    'name' => $candidate['party'],
                    'faculty_id' => $faculty->id,
                ]);
            }
            $student = Student::bySID($candidate['student_id'])->first();

            $party->candidates()->updateOrCreate([
                'student_id' => $student->id,
            ], [
                'phone' => $candidate['phone'],
                'email' => $candidate['email'],
            ]);
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
}
