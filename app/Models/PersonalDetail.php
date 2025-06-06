<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersonalDetail extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded=["id"];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function shiftD()
    {
        return $this->belongsTo(Shift::class, 'shift');
    }
}
