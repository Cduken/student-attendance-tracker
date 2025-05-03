<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;

    protected $table = 'classes';
    protected $fillable = ['teacher_id', 'name', 'subject', 'description'];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'classes_id');
    }


    public function attendances()
    {
        return $this->hasManyThrough(Attendance::class, Student::class);
    }
}
