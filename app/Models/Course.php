<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'courses';
    protected $fillable = ['teacher_id', 'name', 'status', 'created_at', 'updated_at'];

    public function subjectmatter()
    {
        return $this->hasMany(SubjectMatter::class);
    }
}
