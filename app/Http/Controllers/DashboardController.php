<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Devices;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'employees' => Employee::count(),
            'devices' => Devices::count(),
            'attendance_today' => Attendance::whereDate('scan_at', today())->count(),
            'attendance_total' => Attendance::count(),
        ];

        $recentAttendances = Attendance::latest('scan_at')
            ->take(10)
            ->get();

        return view('content.dashboard', compact('stats', 'recentAttendances'));
    }
}
