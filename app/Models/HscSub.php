<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HscSub extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded=["id"];

    public function subject1()
    {
        return $this->belongsTo(Subject::class, 'sub1');
    }

    public function subject2()
    {
        return $this->belongsTo(Subject::class, 'sub2');
    }

    public function subject3()
    {
        return $this->belongsTo(Subject::class, 'sub3');
    }

    public function subject4()
    {
        return $this->belongsTo(Subject::class, 'sub4');
    }
}
