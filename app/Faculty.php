<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;

class Faculty extends Model
{
    protected $fillable = ['name', 'abbreviation'];

    /**
     * @return HasMany
     */
    public function programs() : HasMany
    {
        return $this->hasMany(Program::class);
    }

    public function students() : HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function parties() : HasMany
    {
        return $this->hasMany(Party::class);
    }

    public function protocols() : HasMany
    {
        return $this->hasMany(Protocol::class);
    }

    public function scopeByName(Builder $query, string $name) {
        return $query->where('name', $name);
    }

    public function scopeByAbbreviation(Builder $query, string $abbreviation) {
        return $query->where('abbreviation', $abbreviation);
    }

    public function createStudentList()
    {
        $studentList = new TemplateProcessor(resource_path('templates/Voter-registration.docx'));

        $studentList->setValue('faculty_name', $this->abbreviation);
        $studentList->setValue('year', Carbon::now()->format('Y.'));

        $students = $this->students()->orderBy('surname')->get();

        $studentList->cloneRow('vno', $students->count());

        foreach ($students as $key => $student) {
            $key += 1; // Array nomerates from 0, clones numerate from 1
            $studentList->setValues([
                "vno#$key" => $key.'.',
                "voter_name#$key" => $student->name,
                "voter_surname#$key" => $student->surname,
                "voter_sid#$key" => $student->sid,
            ]);
        }

        $path = Storage::putFileAs(
            'student-list',
            new File($studentList->save()),
            $this->abbreviation.' - studentu saraksts.docx',
            'public'
        );

        return $path;
    }
}
