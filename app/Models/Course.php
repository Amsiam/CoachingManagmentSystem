<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory,SoftDeletes;


    protected $guarded=["id"];

    public function classs() {
        return $this->belongsTo(Classs::class);
    }

    public function batches() {
        return $this->hasMany(Batch::class);
    }

    public function package() {
        return $this->belongsTo(Package::class);
    }
}
