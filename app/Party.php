<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;

class Party extends Model
{
    protected $fillable = ['name', 'faculty_id', 'number'];

    public function faculty() : BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function members() : HasManyThrough
    {
        return $this->hasManyThrough(Student::class, Candidate::class, 'party_id', 'id', 'id', 'student_id');
    }

    public function candidates() : HasMany
    {
        return $this->hasMany(Candidate::class);
    }

    public function scopeByName(Builder $query, string $name)
    {
        return $query->where('name', $name);
    }

    public function createBallot()
    {
        if ($this->members->count() > 20) {
            return null;
        }

        $ballot = new TemplateProcessor(resource_path('templates/Ballot.docx'));

        $ballot->setValue('faculty_name', $this->faculty->abbreviation);
        $ballot->setValue('year', Carbon::now()->format('Y.'));
        $ballot->setValue('party_name', $this->name);
        $ballot->setValue('pn', $this->number);

        // In case there's only one candidate
        if ($this->members->count() > 1) {
            list($left, $right) = $this->members->split(2);
        } else {
            $left = $this->members;
            $right = collect();
        }

        $ballot->cloneRow('no1',$left->count());

        $i = 1;
        $r2i = ceil($i+$this->members->count() / 2);

        while (!$left->isEmpty()) {
            $candidate_left = $left->shift();
            $candidate_right = $right->shift();

            $ballot->setValues([
                "no1#$i" => $i.'.',
                "name1#$i" => "{$candidate_left->name} {$candidate_left->surname}",
                "no2#$i" => $candidate_right ? $r2i++.'.' : '',
                "name2#$i" => $candidate_right ? "{$candidate_right->name} {$candidate_right->surname}" : '',
            ]);

            $ballot->setImageValue("m1#$i", ['path' => resource_path('templates/rhombus.png'), 'width' => 50, 'height' => 50]);
            if ($candidate_right) {
                $ballot->setImageValue("m2#$i", ['path' => resource_path('templates/rhombus.png'), 'width' => 50, 'height' => 50]);
            } else {
                $ballot->setValue("m2#$i", '');
            }

            $i++;
        }


        $path = Storage::putFileAs(
            'ballots',
            new File($ballot->save()),
            $this->faculty->abbreviation.' - '.$this->name.'.docx',
            'public'
        );

        return $path;
    }
}
