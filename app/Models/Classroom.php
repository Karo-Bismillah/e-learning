<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $table = 'classrooms';
    protected $fillable = [ 'name', 'teacher_id', 'quota', 'token', 'status', 'created_at', 'updated_at'];

    public function student()
    {
        return $this->hasOne(Student::class);
    }
}
