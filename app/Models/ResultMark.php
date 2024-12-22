<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResultMark extends Model
{
    use HasFactory;

    protected $guarded=["id"];

    //cust is optional to boolean
    protected $casts = [
        "is_optional" => "boolean",
    ];

    public function student(){
        return $this->belongsTo(Student::class);
    }


    public function subject(){
        return $this->belongsTo(ResultSubject::class,"subject_id");
    }
}
