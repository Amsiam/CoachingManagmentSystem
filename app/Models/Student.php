<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable
{
    use HasFactory, Notifiable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [
        "id"
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'fixed_salary' => 'boolean',
            'free' => 'boolean',
            'post_paid' => 'boolean',
        ];
    }

    public function personalDetails(){
        return $this->hasOne(PersonalDetail::class);
    }

    public function academicDetails(){
        return $this->hasMany(AcademicDetail::class);
    }

    public function hscSubs(){
        return $this->hasOne(HscSub::class);
    }

    public function addedBy(){
       return $this->belongsTo(User::class,"user_id");
    }



    public function batches(){
        return $this->belongsToMany(Batch::class);
    }

    public function courses(){
        return $this->belongsToMany(Course::class);
    }

    public function payments(){
        return $this->hasMany(Payment::class,"student_roll","id");
    }

    public function package(){
        return $this->belongsTo(Package::class);
    }

    public function result_marks()
    {
        return $this->hasMany(ResultMark::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class);
    }
}
