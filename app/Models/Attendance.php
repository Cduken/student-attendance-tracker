<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'classes_id', 'date', 'status', 'notes'];

    // Add this to cast the 'date' column to a Carbon instance
    protected $dates = ['date'];

    // Alternatively, use $casts (recommended for Laravel 9+)
    // protected $casts = [
    //     'date' => 'datetime',
    // ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'classes_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
