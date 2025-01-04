<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory,SoftDeletes;


    protected $guarded=["id"];

    protected $casts = [
        'active' => 'boolean',
        'featured' => 'boolean',
    ];

    public function classs() {
        return $this->belongsTo(Classs::class);
    }

    public function batches() {
        return $this->hasMany(Batch::class);
    }

    public function package() {
        return $this->belongsTo(Package::class);
    }

    public function subCourses()
    {
        return $this->hasMany(Course::class, "parent_id");
    }

    public function parent()
    {
        return $this->belongsTo(Course::class, "parent_id");
    }
    public function getNameAttribute($value)
    {
        if ($this->parent_id) {
            return $this->parent->name . " - " . $value;
        }
        return  $value;
    }
}
