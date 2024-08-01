<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Result extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded=["id"];

    public function exam(){
        return $this->belongsTo(Exam::class);
    }

    public function marks(){
        return $this->hasMany(ResultMark::class);
    }
    public function resultSubjects(){
        return $this->hasMany(ResultSubject::class);
    }
}
