<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ["id"];

    //cast
    protected $casts = [
        "auto_selected" => "boolean",
        "active" => "boolean",
    ];


    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
