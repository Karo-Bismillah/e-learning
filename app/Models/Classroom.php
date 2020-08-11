<?php

namespace App\Models;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $table = 'classrooms';
    protected $fillable = [ 'name', 'teacher_id', 'quota', 'token', 'status', 'created_at', 'updated_at'];

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subjectmatter()
    {
        return $this->hasMany(SubjectMatter::class);
    }
}
