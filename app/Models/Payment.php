<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded=["id"];

    public function student(){
        return $this->belongsTo(Student::class,"student_roll","id");
    }

    public function recievedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, "recieved_by", "email");
    }
}
