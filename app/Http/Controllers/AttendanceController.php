<?php

namespace App\Http\Controllers;

use App\Http\Resources\AttendanceResource;
use App\Http\Resources\ScanAttendanceResource;
use App\Models\Attendance;
use App\Models\Devices;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
setlocale(LC_ALL, 'id_ID.utf8');
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
            ->allowedFilters(['pin', 'employee_name', AllowedFilter::scope('starts_between')])
            ->paginate()
            ->appends(request()->query());
    
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
    public function getAttendanceByDate(Request $request) {
 
            // $client_id = $request->header('cloud-id');
             $client_id = "C2630450C31E1824";
            
            if(!Devices::where('cloud_id', $client_id)->exists()){
                return response()->json([
                    'status' => false,
                   'message' => "Device not found with provided cloud id ",
                ]);
            }
    
            $begin = new DateTime($request->start_at);
            $end = new DateTime($request->end_at);
            $end->modify('+1 day');
            
            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);
            $datas = [];
            foreach ($period as $dt) {
                $scanAt= $dt->format('Y-m-d');
                $attendance = Attendance::where('cloud_id', $client_id)
                        ->whereDate('scan_at',  $scanAt)
                        ->groupBy('employee_id', 'employee_name')
                        ->select('employee_id', 'employee_name')->get();
                        foreach($attendance as $employee) {
                            $employees[] = [
                                'employee_id' => (int) $employee->employee_id,
                                'employee_name' => $employee->employee_name,
                                'avatar' => isset($employee->employee->avatar) ? url('storage').$employee->employee->avatar : null,
                                'attendance' => $this->getAttendance($client_id, $employee->employee_id,$scanAt)
                            ];
                        }
                if(count($attendance)>0) {
                    $datas[] = [
                        'date' => $this->hariIndo($dt->format("l")).",".$dt->format("d F Y"),
                        'employee' => $employees
                    ];
                }
                
            }
            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $datas
            ]);
        }
        
   function getAttendance($client_id, $employee_id, $scan_at) {
    $results = Attendance::where('cloud_id', $client_id)
                ->whereDate('scan_at', $scan_at)
                ->where('employee_id', $employee_id)
                ->orderBy('scan_at', 'asc')
                ->get();
    return ScanAttendanceResource::collection($results);
}
function hariIndo ($hariInggris) {
    switch ($hariInggris) {
      case 'Sunday':
        return 'Minggu';
      case 'Monday':
        return 'Senin';
      case 'Tuesday':
        return 'Selasa';
      case 'Wednesday':
        return 'Rabu';
      case 'Thursday':
        return 'Kamis';
      case 'Friday':
        return 'Jumat';
      case 'Saturday':
        return 'Sabtu';
      default:
        return 'hari tidak valid';
    }
  }
}
