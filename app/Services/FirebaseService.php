<?php

namespace App\Services;

use Carbon\Carbon;
use Google\Auth\Credentials\ServiceAccountCredentials;
use GuzzleHttp\Client;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Laravel\Firebase\Facades\Firebase;

class FirebaseService
{
    protected Messaging $messaging;

    protected Client $http;

    protected ?string $accessToken = null;

    protected array $firestoreConfig;

    public function __construct()
    {
        $this->messaging = Firebase::messaging();
        $this->http = new Client();

        $credentialsPath = base_path(config('firebase.projects.fingerspot.credentials'));
        $this->firestoreConfig = json_decode(file_get_contents($credentialsPath), true);
    }

    public function sendNotification($title, $body, $to, $platform = 'android')
    {
        $topic = $to === 'all' ? 'all' : $to;

        $data = [
            'to' => $to,
            'title' => $title,
            'message' => $body,
            'type' => 'info',
            'clickable' => '1',
            'is_notif' => '1',
            'group' => 'gsfinger',
            'send_by' => 'adiyoga27',
            'is_all' => $to === 'all' ? 'true' : 'false',
        ];

        $message = CloudMessage::withTarget('topic', $topic)
            ->withNotification(Notification::create($title, $body))
            ->withData($data);

        $result = $this->messaging->send($message);

        $this->saveToFirestore($title, $body, $to);

        return $result;
    }

    protected function saveToFirestore(string $title, string $body, string $to): void
    {
        try {
            $projectId = $this->firestoreConfig['project_id'];
            $url = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/notifications";

            $fields = [
                'title'       => ['stringValue' => $title],
                'message'     => ['stringValue' => $body],
                'type'        => ['stringValue' => 'info'],
                'clickable'   => ['booleanValue' => true],
                'to'          => [
                    'arrayValue' => [
                        'values' => [
                            ['stringValue' => $to],
                        ],
                    ],
                ],
                'read_by'     => ['arrayValue' => ['values' => []]],
                'timestamp'   => ['integerValue' => (string) (Carbon::now()->getTimestampMs())],
                'data'        => [
                    'mapValue' => [
                        'fields' => [
                            'link' => ['nullValue' => null],
                        ],
                    ],
                ],
            ];

            $this->http->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->getAccessToken(),
                    'Content-Type' => 'application/json',
                ],
                'json' => ['fields' => $fields],
            ]);
        } catch (\Throwable $e) {
            logger()->error('Firestore notification save failed: ' . $e->getMessage());
        }
    }

    protected function getAccessToken(): string
    {
        if ($this->accessToken) {
            return $this->accessToken;
        }

        $credentials = new ServiceAccountCredentials(
            ['https://www.googleapis.com/auth/cloud-platform'],
            $this->firestoreConfig
        );
        $token = $credentials->fetchAuthToken();
        $this->accessToken = $token['access_token'];

        return $this->accessToken;
    }
}
