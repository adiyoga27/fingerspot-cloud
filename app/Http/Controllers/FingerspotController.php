<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Webhooks;
use App\Services\FirebaseService;
use Illuminate\Http\Request;

class FingerspotController extends Controller
{
    public function test(Request $request) {
        $response = (new FirebaseService)->sendNotification('Test Title', 'Test Body', 'all', 'android');
        return response()->json(['message' => 'Notification sent successfully', 'response' => $response]);
    }
    public function webhook(Request $request) {
        try {
            Webhooks::create([
                'type_hit' => $request->type,
                'trans_id' => $request->trans_id,
                'cloud_id' => $request->cloud_id,
                'data' => json_encode($request->data),
            ]);

            if($request->type == 'attlog'){

                    $employee = Employee::where('pin', $request->data['pin'])->where('client_id', $request->cloud_id )->first();

                    if($employee){
                        Attendance::create([
                            'employee_id' => $employee->id,
                            'cloud_id' => $request->cloud_id,
                            'device_id' => $employee->device->id,
                            'device_name' => $employee->device->name,
                            'employee_name' => $employee->name,
                            'pin' => $request->data['pin'],
                           'scan_at' => $request->data['scan'],
                           'scan_verify' => $request->data['verify'],
                           'scan_status' => $request->data['status_scan'],
                        ]) ;   
                    }
            }
            return response()->json([
                'status' => true,
                'message' => 'success'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
       
    }
}
