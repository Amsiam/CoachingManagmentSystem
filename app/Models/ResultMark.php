<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResultMark extends Model
{
    use HasFactory;

    protected $guarded=["id"];

    public function student(){
        return $this->belongsTo(Student::class);
    }

    public function subject(){
        return $this->belongsTo(ExamRoutine::class,"subject_id");
    }
}
