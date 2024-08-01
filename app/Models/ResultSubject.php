<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResultSubject extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded=["id"];

    public function has2ndPart()
    {
        return $this->hasOne(ResultSubject::class, "first_part_id");
    }

    public function marks()
    {
        return $this->hasMany(ResultMark::class, "subject_id");
    }
}
