<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';
    protected $fillable = [
        'user_id', 'class_id', 'name', 'gender', 'dob', 'address', 'created_at', 'updated_at',
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}
