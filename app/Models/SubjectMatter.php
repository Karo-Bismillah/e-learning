<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectMatter extends Model
{
    protected $table = 'subject_matters';
    protected $fillable = ['course_id', 'teacher_id', 'classroom_id', 'name', 'information', 'youtube', 'link', 'start', 'end', 'status', 'created_at', 'updated_at'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}
