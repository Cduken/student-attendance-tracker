<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['classes_id', 'name', 'email', 'student_id'];

    public function class()
    {
        return $this->belongsTo(Classes::class, 'classes_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }
}
