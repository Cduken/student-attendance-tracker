<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Attendance;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $teacherId = Auth::id();

        // Total Students
        $totalStudents = Student::whereHas('class', function ($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId);
        })->count();

        // Today's Attendance
        $today = Carbon::today();
        $totalStudentsToday = Student::whereHas('class', function ($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId);
        })->count();

        $presentStudentsToday = Attendance::whereHas('student.class', function ($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId);
        })
            ->where('date', $today)
            ->where('status', 'present')
            ->count();

        $todaysAttendance = $totalStudentsToday > 0
            ? round(($presentStudentsToday / $totalStudentsToday) * 100, 1)
            : 0;

        // Monthly Average Attendance
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $attendanceRecords = Attendance::whereHas('student.class', function ($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId);
        })
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get();

        $totalDays = $attendanceRecords->groupBy('date')->count();
        $presentDays = $attendanceRecords->where('status', 'present')->count();
        $totalPossible = $totalDays * $totalStudents;

        $monthlyAverage = $totalPossible > 0
            ? round(($presentDays / $totalPossible) * 100, 1)
            : 0;

        // Recent Activity (last 5 attendance records)
        $recentActivities = Attendance::whereHas('student.class', function ($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId);
        })
            ->with(['student.class'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($attendance) {
                return [
                    'description' => "Marked attendance for {$attendance->student->class->name}",
                    'time_ago' => Carbon::parse($attendance->created_at)->diffForHumans(),
                ];
            });

        return Inertia::render('Dashboard', [
            'dashboardData' => [
                'totalStudents' => $totalStudents,
                'todaysAttendance' => $todaysAttendance,
                'monthlyAverage' => $monthlyAverage,
                'recentActivities' => $recentActivities,
            ],
        ]);
    }
}
