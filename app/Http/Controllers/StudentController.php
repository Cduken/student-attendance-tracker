<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::whereHas('class', function ($query) {
            $query->where('teacher_id', Auth::id());
        })
            ->with('class')
            ->join('classes', 'students.classes_id', '=', 'classes.id') // Join the classes table
            ->orderByRaw('LEFT(classes.name, 1)') // Order by the first letter of the class name
            ->select('students.*') // Ensure only student columns are selected
            ->get();

        return Inertia::render('Students/Index', [
            'students' => $students,
        ]);
    }

    public function create(Classes $class)
    {
        // Verify the current user is the teacher of this class
        if (Auth::id() !== $class->teacher_id) {
            abort(403, 'Unauthorized action.');
        }

        return Inertia::render('Students/Create', [
            'class' => $class,
        ]);
    }

    public function store(Request $request, Classes $class)
    {
        // Verify the current user is the teacher of this class
        if (Auth::id() !== $class->teacher_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'student_id' => 'required|string|max:50|unique:students,student_id',
        ]);

        $class->students()->create($validated);

        return redirect()->route('classes.show', $class);
    }

    public function show(Student $student)
    {
        // Verify the current user is the teacher of this class
        if (Auth::id() !== $student->class->teacher_id) {
            abort(403, 'Unauthorized action.');
        }

        $student->load(['class', 'attendances' => function ($query) {
            $query->orderBy('date', 'desc');
        }]);

        return Inertia::render('Students/Show', [
            'student' => $student,
        ]);
    }

    public function edit(Student $student)
    {
        // Verify the current user is the teacher of this class
        if (Auth::id() !== $student->class->teacher_id) {
            abort(403, 'Unauthorized action.');
        }

        return Inertia::render('Students/Edit', [
            'student' => $student,
            'class' => $student->class,
        ]);
    }

    public function update(Request $request, Student $student)
    {
        // Verify the current user is the teacher of this class
        if (Auth::id() !== $student->class->teacher_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'student_id' => 'required|string|max:50|unique:students,student_id,' . $student->id,
        ]);

        $student->update($validated);

        return redirect()->route('classes.show', $student->class);
    }

    public function destroy(Student $student)
    {
        // Verify the current user is the teacher of this class
        if (Auth::id() !== $student->class->teacher_id) {
            abort(403, 'Unauthorized action.');
        }

        $class = $student->class;

        // Delete related attendance records
        $student->attendances()->delete();

        // Delete the student
        $student->delete();

        return redirect()->route('classes.show', $class);
    }
}
