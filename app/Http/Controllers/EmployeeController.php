<?php

namespace App\Http\Controllers;

use App\Http\Resources\EmployeeResource;
use App\Models\Devices;
use App\Models\Employee;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $client_id = $request->header('cloud-id');
        // if(!Devices::where('cloud_id', $client_id)->exists()){
        //     return response()->json([
        //         'status' => false,
        //       'message' => "Device not found with provided cloud id ",
        //     ]);
        // }
        $client_id = "C2630450C31E1824";
        $employees =QueryBuilder::for(Employee::class)
        ->where('client_id', $client_id)
        ->allowedFilters(['client_id', 'name'])
        ->paginate()
        ->appends(request()->query());

        return EmployeeResource::collection($employees)->additional([
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
        $client_id = $request->header('cloud-id');
        if(!Devices::where('cloud_id', $client_id)->exists()){
            return response()->json([
                'status' => false,
               'message' => "Device not found with provided cloud id ",
            ]);
        }
        try {
            $payload = [
                'client_id' => $client_id,
                'name' => $request->name,
                'pin' => $request->pin,
            ];

            if(isset($request->thumbnail)) {
                $payload['avatar'] = $request->file('avatar')->store('employees', 'public');
            }
            Employee::create($payload);
            return response()->json([
               'status' => true,
               'message' => "Employee created successfully " ,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
               'status' => false,
               'message' => "Failed to create employee ".$th->getMessage() ,
            ]);
        }
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

        try {
           $payload = [
            'name' => $request->name,
            'pin' => $request->pin,
           ];
            if(isset($request->avatar)) {
                $payload['avatar'] = $request->file('avatar')->store('employees', 'public');
            }
            Employee::where('id', $id)->update($payload);
            return response()->json([
               'status' => true,
               'message' => "Employee updated successfully",
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
               'status' => false,
               'message' => "Failed to update employee",
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            Employee::where('id', $id)->delete();
            return response()->json([
               'status' => true,
               'message' => "Employee deleted successfully",
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
               'status' => false,
               'message' => "Failed to delete employee",
            ]);
        }        
    }
}
