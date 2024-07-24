<?php
namespace App\Services;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Firestore;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Google\Cloud\Firestore\FirestoreClient;
use Illuminate\Support\Facades\Http;

class FirebaseService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function sendNotification($title, $body, $to, $platform = 'android')
    {
        try {
            $response = Http::asMultipart()->post('http://fcm.galkasoft.id/api/send', [
                'to[0]' => $to,
                'title' => $title,
                'message' =>  $body,
                'type' => 'link',
                'clickable' => '0',
                'is_notif' => '1',
                'group' => 'gsfinger',
                'send_by' => 'adiyoga27',
                'is_all' => 'false',
            ]);
    
            if ($response->failed()) {
                return response()->json(['error' => $response->body()], 500);
            }
    
            return response()->json($response->json());

        } catch (\Throwable $th) {
            throw $th;
        }
     
    }
}
