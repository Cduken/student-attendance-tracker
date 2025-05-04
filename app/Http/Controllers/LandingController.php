<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Classes;
use App\Models\Student;
use App\Models\User;
use Inertia\Inertia;

class LandingController extends Controller
{
    public function index()
    {
        $stats = [
            [
                'value' => User::count() . '+',
                'label' => 'Educators',
            ],
            [
                'value' => Student::count() . '+',
                'label' => 'Students',
            ],
            [
                'value' => '98%',
                'label' => 'Satisfaction',
            ],
            [
                'value' => Attendance::count() . '+',
                'label' => 'Attendance Records',
            ],
        ];

        return Inertia::render('Landing', [
            'stats' => $stats,
        ]);
    }
}
