<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    // --- API Method ---
    public function getAttendanceByDate(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $attendances = Attendance::whereDate('scan_at', $request->date)
            ->orderBy('scan_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $attendances,
        ]);
    }

    // --- Web Method ---
    public function index(Request $request)
    {
        $query = Attendance::query()->orderBy('scan_at', 'desc');

        if ($request->filled('date')) {
            $query->whereDate('scan_at', $request->date);
        }

        if ($request->filled('employee_name')) {
            $query->where('employee_name', 'like', '%' . $request->employee_name . '%');
        }

        if ($request->filled('cloud_id')) {
            $query->where('cloud_id', $request->cloud_id);
        }

        $attendances = $query->paginate(20)->appends($request->query());

        if ($request->expectsJson()) {
            return response()->json(['status' => true, 'data' => $attendances]);
        }

        return view('content.attendances.index', compact('attendances'));
    }
}
