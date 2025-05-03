<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Classes;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AttendanceController extends Controller
{
    public function globalIndex()
    {
        $attendances = Attendance::whereHas('class', function ($query) {
            $query->where('teacher_id', Auth::id());
        })
            ->with(['student', 'class'])
            ->latest('date')
            ->get()
            ->groupBy('date');

        return Inertia::render('Attendance/GlobalIndex', [
            'attendances' => $attendances,
        ]);
    }

    public function index(Classes $class)
    {
        $attendances = Attendance::where('classes_id', $class->id)
            ->with('student')
            ->latest('date')
            ->get()
            ->groupBy('date');

        return Inertia::render('Attendance/Index', [
            'class' => $class,
            'attendances' => $attendances,
        ]);
    }

    public function create(Classes $class)
    {
        $students = $class->students()->orderBy('name')->get();

        return Inertia::render('Attendance/Create', [
            'class' => $class,
            'students' => $students,
            'today' => now()->format('Y-m-d'),
        ]);
    }

    public function store(Request $request, Classes $class)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:students,id',
            'attendances.*.status' => 'required|in:present,absent,late,excused',
            'attendances.*.notes' => 'nullable|string',
        ]);

        foreach ($validated['attendances'] as $attendanceData) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $attendanceData['student_id'],
                    'classes_id' => $class->id,
                    'date' => $validated['date'], // Use the provided date
                ],
                [
                    'status' => $attendanceData['status'],
                    'notes' => $attendanceData['notes'] ?? null,
                ]
            );
        }

        return redirect()->route('classes.show', $class);
    }

    public function show(Attendance $attendance)
    {
        return Inertia::render('Attendance/Show', [
            'attendance' => $attendance->load(['student', 'class']),
        ]);
    }

    public function edit(Attendance $attendance)
    {
        return Inertia::render('Attendance/Edit', [
            'attendance' => $attendance->load(['student', 'class']),
        ]);
    }

    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'status' => 'required|in:present,absent,late,excused',
            'notes' => 'nullable|string',
        ]);

        $attendance->update($validated);

        return redirect()->route('classes.show', $attendance->class);
    }

    public function destroy(Attendance $attendance)
    {
        $class = $attendance->class;
        $attendance->delete();

        return redirect()->route('classes.show', $class);
    }
}
