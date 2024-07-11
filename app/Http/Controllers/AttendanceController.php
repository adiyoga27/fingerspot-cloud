<?php

namespace App\Http\Controllers;

use App\Http\Resources\AttendanceResource;
use App\Models\Attendance;
use App\Models\Devices;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $client_id = $request->header('cloud-id');
        if(!Devices::where('cloud_id', $client_id)->exists()){
            return response()->json([
                'status' => false,
               'message' => "Device not found with provided cloud id ",
            ]);
        }
        $attendances =  QueryBuilder::for(Attendance::class)
            ->where('cloud_id', $client_id)
            ->allowedFilters(['pin', 'employee_name'])
            ->paginate();
    
        return AttendanceResource::collection($attendances)->additional([
            'status' => true,
            'message' => 'success'  
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
