<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;

class Program extends Model
{
    protected $fillable = ['name', 'lri', 'code'];

    /**
     * @return BelongsTo
     */
    public function faculty() : BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function students() : HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function scopeByName(Builder $query, string $name) {
        return $query->where('name', $name);
    }

    public function scopeByCode(Builder $query, string $code) {
        return $query->where('code', $code);
    }

    /**
     * Rudimentary classificator
     * Source: https://likumi.lv/doc.php?id=291524
     */
    public function getStudyLevel()
    {
        $codes = [
            '41' => '1.LPSP',
            '42' => 'PBSP',
            '43' => 'BSP',
            '44' => '2.LPSP',
            '45' => 'MSP',
            '46' => '2.LPSP',
            '47' => 'PMSP',
            '48' => '2.LPSP',
            '49' => '2.LPSP',
            '51' => 'DSP',
        ];

        return $codes[substr($this->lri, 0, 2)];
    }

    public function createStudentList()
    {
        $studentList = new TemplateProcessor(resource_path('templates/Voter-registration.docx'));

        $studentList->setValue('faculty_name', "{$this->faculty->abbreviation}");
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
            $this->faculty->abbreviation." - {$this->name}".' - studentu saraksts.docx',
            'public'
        );

        return $path;
    }
}
