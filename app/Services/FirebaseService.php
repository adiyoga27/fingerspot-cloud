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
            $notification = Notification::create($title, $body);
            $message = CloudMessage::withTarget('topic', $to)->withNotification($notification);
            $result = Firebase::project('gsfinger');
            $result->messaging()->send($message);
            $formData = array(
                'title' => $title,
                'message' => $body,
                'to' => $to,
                'type' => 'notif',
                'clickable' => false,
                'timestamp' => Carbon::now(),
                'read_by' => [],
                // 'data' => $data
            );
            // Save Kalau Notif 
            $result->firestore()->database()->collection('notifications')->newDocument()->set($formData);
        return $result;

        } catch (\Throwable $th) {
            throw $th;
        }
     
    }
}
