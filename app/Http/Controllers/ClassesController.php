<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ClassesController extends Controller
{
    public function index()
    {
        $classes = Classes::where('teacher_id', Auth::id())
            ->withCount('students')
            ->latest()
            ->get();

        return Inertia::render('Classes/Index', [
            'classes' => $classes,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $class = Classes::create([
            'teacher_id' => Auth::id(),
            'name' => $validated['name'],
            'subject' => $validated['subject'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('classes.index');
    }

    public function show(Classes $class)
    {
        // Verify the current user is the teacher of this class
        if (Auth::id() !== $class->teacher_id) {
            abort(403, 'Unauthorized action.');
        }

        $class->load(['students' => function ($query) {
            $query->with('class')->orderBy('name');
        }]);

        return Inertia::render('Classes/Show', [
            'classData' => $class,
            'students' => $class->students,
        ]);
    }

    public function edit(Classes $class)
    {
        // Verify the current user is the teacher of this class
        if (Auth::id() !== $class->teacher_id) {
            abort(403, 'Unauthorized action.');
        }

        return Inertia::render('Classes/Edit', [
            'class' => $class,
        ]);
    }

    public function update(Request $request, Classes $class)
    {
        // Verify the current user is the teacher of this class
        if (Auth::id() !== $class->teacher_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $class->update($validated);

        return back()->with('success', 'Class updated successfully');
    }

    public function destroy(Classes $class)
    {
        // Verify the current user is the teacher of this class
        if (Auth::id() !== $class->teacher_id) {
            abort(403, 'Unauthorized action.');
        }

        // Delete all related attendances first
        $class->attendances()->delete();

        $class->students()->delete();


        // Now delete the class
        $class->delete();

        return redirect()->route('classes.index');
    }
}
