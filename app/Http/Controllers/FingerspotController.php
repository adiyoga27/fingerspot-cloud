<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Tran;
use App\Models\Webhooks;
use App\Services\FirebaseService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
                        (new FirebaseService)->sendNotification($employee->name. ' Scan Absensi', $employee->name." melakukan scan pada waktu ".$request->data['scan'], 'all', 'android');

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

    function test2() {
        try {
            $trans = Tran::get()->count();
        $payload = [
            "trans_id" => $trans + 1,
            "cloud_id" => "C2630450C31E1824",
            "start_date" => Carbon::now()->format('Y-m-d'),
            "end_date" => Carbon::now()->format('Y-m-d'),
        ];
        $result = Http::withHeaders(['Authorization' => 'Bearer C613PAKIHDWXJK5D'])
                    ->post("https://developer.fingerspot.io/api/get_attlog", $payload);
            Tran::create([
                        'title' => 'Get Att Logs',
                        'hits' => $payload,
                        'results' => $result->json()['data'] ?? [],
                    ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
