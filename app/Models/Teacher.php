<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $table = 'teachers';
    protected $fillable = [
        'user_id', 'name', 'gender', 'dob', 'phone', 'address', 'created_at', 'update_at'
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function course()
    {
        return $this->hasMany(Course::class);
    }

    public function classroom()
    {
        return $this->hasMany(Classroom::class);
    }

    public function subjectmatter()
    {
        return $this->hasMany(SubjectMatter::class);
    }
}
