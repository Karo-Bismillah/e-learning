<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    public function student()
    {
        return $this->hasMany(Student::class);
    }
}
