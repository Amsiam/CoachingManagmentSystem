<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classs extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded=["id"];

    public function groups() {
        return $this->belongsToMany(Group::class);
    }

    public function courses() {
        return $this->hasMany(Course::class);
    }
}
