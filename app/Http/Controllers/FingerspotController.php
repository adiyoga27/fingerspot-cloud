<?php

namespace App\Http\Controllers;

use App\Enums\ScanEnum;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Tran;
use App\Models\Webhooks;
use App\ScanType;
use App\Services\FirebaseService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Throwable;

class FingerspotController extends Controller
{
    public function test(Request $request) {
        $response = (new FirebaseService)->sendNotification('Test Title', 'Test Body', 'all', 'android');
        return response()->json(['message' => 'Notification sent successfully', 'response' => $response]);
    }
    public function webhook(Request $request) {
      
        try {
          $this->saveText($request);
          $validation = $request->all();
            $payload = [
                'type_hit' => $validation['type'],
                'cloud_id' => $validation['cloud_id'],
                'data' => json_encode($validation),
            ];
            if(isset($payload['trans_id'])){
              $payload['trans_id'] = $validation['trans_id'];
            }
            Webhooks::create($payload);

            if($payload['type_hit'] == 'attlog'){
                $attlog = $validation['data'];
                    $employee = Employee::where('pin', $attlog['pin'])->where('client_id', $payload['cloud_id'] )->first();

                    if($employee){
                        Attendance::create([
                            'employee_id' => $employee->id,
                            'cloud_id' => $payload['cloud_id'],
                            'device_id' => $employee->device->id,
                            'device_name' => $employee->device->name,
                            'employee_name' => $employee->name,
                            'pin' => $attlog['pin'],
                            'scan_at' => $attlog['scan'],
                            'scan_verify' => $attlog['verify'],
                            'scan_status' => $attlog['status_scan'],
                        ]) ;   
                        (new FirebaseService)->sendNotification(strtoupper($employee->name) . ' SCAN '.$this->statusScan($attlog['status_scan']), $employee->name." melakukan scan pada waktu ".date("d F Y H:i", strtotime($attlog['scan']))." wita", 'all', 'android');

                    }
            }
            return response()->json([
                'status' => true,
                'message' => 'success'
            ]);
        } catch (\Throwable $th) {
          $this->errorInfo($th->getMessage(),$th);
          $this->logInfo(json_encode($request->all()));

            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
       
    }
    public function statusScan($status){
       switch ($status) {
        case '0':
          return "MASUK";
          break;
          case '1':
            return "PULANG";
            break;
            case '2':
              return "ISTIRAHAT";
              break;
              case '3':
                return "KEMBALI ISTIRAHAT";
                break;
        default:
          return "ABSENSI";
          break;
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

public static function errorInfo($content, Throwable $e, $title = 'Fingerspot')
  {
    $content = json_encode($content);
    Http::post(env("DISCORD_WEBHOOK", "https://discord.com/api/webhooks/1175802860198436914/eZKoG9VTyi4J1rjmDXCOh6C7y3oi0jqKaCK4jMkR-VwqPlxL82c0HscbtFxBdxhuNbr7"), [

      "username" => "Dicsystime",
      // "avatar_url"=> "https://i.imgur.com/4M34hi2.png",
      "embeds" => [
        [
          "title" => $title,
          "description" => "```$content```",
          "color" => 15258703,
          "fields" => [
            array(
              'name' => 'Path',
              'value' =>  request()->path(),
              "inline" => true
            ),
            array(
              'name' => 'File',
              'value' =>  $e->getFile(),
              "inline" => true
            ),
            array(
              'name' => 'Line',
              'value' =>  $e->getLine(),
              "inline" => true

            ),
          ]
        ],
      ],

    ]);
  }


public static function logInfo($content,$title = 'Fingerspot')
{
  $content = json_encode($content);
  Http::post(env("DISCORD_WEBHOOK", "https://discord.com/api/webhooks/1175802860198436914/eZKoG9VTyi4J1rjmDXCOh6C7y3oi0jqKaCK4jMkR-VwqPlxL82c0HscbtFxBdxhuNbr7"), [

    "username" => "Dicsystime",
    // "avatar_url"=> "https://i.imgur.com/4M34hi2.png",
    "embeds" => [
      [
        "title" => $title,
        "description" => "```$content```",
        "color" => 15258703,
        "fields" => [
          array(
            'name' => 'Path',
            'value' =>  request()->path(),
            "inline" => true
          ),
       
      
        ]
      ],
    ],

  ]);
}

  function saveText(Request $request) {
    try {
      $body = $request->getContent();
            
      $file = 'data-finger.txt';
      if (Storage::exists($file)) {
          $data = Storage::get($file);
      } else {
          $data = '';
      }

      $data .= $body . "\n";
      Storage::put($file, $data);
      $this->logInfo("Data Fingerspot Terhit");
    } catch (\Throwable $th) {
      //throw $th;
      $this->logInfo("Data Fingerspot Gagal Menyimpan Log ".$th->getMessage());

    }
  
  }
}
