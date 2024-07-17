<?php

namespace App\Http\Controllers;

use App\Http\Resources\DeviceResource;
use App\Models\Devices;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $devices =  QueryBuilder::for(Devices::class)
        ->allowedFilters(['cloud_id', 'name'])
        ->paginate();
        return DeviceResource::collection($devices)->additional([
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
        try {
            $payload = [
                'cloud_id' => $request->cloud_id,
                'name' => $request->name
            ];

            if(isset($request->thumbnail)) {
                $payload['thumbnail'] = $request->file('thumbnail')->store('devices', 'public');
            }
            Devices::create($payload);
            return response()->json([
               'status' => true,
               'message' => 'Device created successfully'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
               'status' => false,
               'message' => $th->getMessage()
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
        try {
            $payload = [
                'cloud_id' => $request->cloud_id,
                'name' => $request->name
            ];
            if(isset($request->thumbnail)) {
                $payload['thumbnail'] = $request->file('thumbnail')->store('devices', 'public');
            }

            Devices::find($id)->update($payload);
            return response()->json([
               'status' => true,
               'message' => 'Device updated successfully'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
               'status' => false,
               'message' => $th->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            Devices::find($id)->delete();
            return response()->json([
               'status' => true,
               'message' => 'Device deleted successfully'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
               'status' => false,
               'message' => $th->getMessage()
            ]);
        }
    }
}
