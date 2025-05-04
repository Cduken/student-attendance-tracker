<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        // Add this at the very start of the method
        Log::debug('Request Data:', [
            'search' => $request->input('search'),
            'class' => $request->input('class'),
            'user_id' => Auth::id()
        ]);

        $query = Student::with('class')
            ->whereHas('class', function ($query) {
                $query->where('teacher_id', Auth::id());
            });

        if ($request->has('search') && $request->input('search') !== '') {
            $search = trim($request->input('search'));
            Log::debug('Search Term:', ['term' => $search]);

            $query->where(function ($q) use ($search) {
                $q->where('students.name', 'like', "%{$search}%")
                    ->orWhere('students.student_id', 'like', "%{$search}%")
                    ->orWhere('students.email', 'like', "%{$search}%")
                    ->orWhereHas('class', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });

            Log::debug('SQL Query:', ['sql' => $query->toSql(), 'bindings' => $query->getBindings()]);
        }

        // Get classes for filter dropdown
        $classes = Classes::where('teacher_id', Auth::id())
            ->orderBy('name')
            ->get();

        // Order by class name then student name
        $students = $query->orderBy('students.name')
            ->select('students.*')
            ->paginate(6)
            ->withQueryString();



        return Inertia::render('Students/Index', [
            'students' => $students,
            'classes' => $classes,
            'filters' => $request->only(['search', 'class']),
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
