<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use HasFactory;

    protected $guarded=["id"];


    public function batch(){
        return $this->belongsTo(Batch::class);
    }

    public function course(){
        return $this->belongsTo(Course::class);
    }
    public function group(){
        return $this->belongsTo(Group::class);
    }

    public function package(){
        return $this->belongsTo(Package::class);
    }

    public function exam_routines(){
        return $this->hasMany(ExamRoutine::class);
    }
}
