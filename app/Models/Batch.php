<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Batch extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded=["id"];

    public function group() {
        return $this->belongsTo(Group::class);
    }

    public function course() {
        return $this->belongsTo(Course::class);
    }
}
