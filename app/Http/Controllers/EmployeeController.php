<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Devices;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    // --- Web Methods ---

    public function index()
    {
        $employees = Employee::with('device')->orderBy('id', 'desc')->paginate(15);
        return view('content.employees.index', compact('employees'));
    }

    public function create()
    {
        $devices = Devices::orderBy('name')->get();
        return view('content.employees.form', compact('devices'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'client_id' => 'required|string|max:50',
            'pin' => 'required|integer|unique:employees,pin',
            'avatar' => 'nullable|string|max:255',
        ]);

        Employee::create($data);

        if ($request->expectsJson()) {
            return response()->json(['status' => true, 'message' => 'Karyawan berhasil ditambahkan'], 201);
        }

        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil ditambahkan');
    }

    public function show($id)
    {
        $employee = Employee::with('device')->findOrFail($id);

        if (request()->expectsJson()) {
            return response()->json(['status' => true, 'data' => $employee]);
        }

        return view('content.employees.show', compact('employee'));
    }

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $devices = Devices::orderBy('name')->get();
        return view('content.employees.form', compact('employee', 'devices'));
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'client_id' => 'required|string|max:50',
            'pin' => 'required|integer|unique:employees,pin,' . $employee->id,
            'avatar' => 'nullable|string|max:255',
        ]);

        $employee->update($data);

        if ($request->expectsJson()) {
            return response()->json(['status' => true, 'message' => 'Karyawan berhasil diupdate']);
        }

        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil diupdate');
    }

    public function destroy($id)
    {
        Employee::destroy($id);

        if (request()->expectsJson()) {
            return response()->json(['status' => true, 'message' => 'Karyawan berhasil dihapus']);
        }

        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil dihapus');
    }
}
