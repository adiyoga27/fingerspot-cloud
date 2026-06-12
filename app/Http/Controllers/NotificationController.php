<?php

namespace App\Http\Controllers;

use Google\Auth\Credentials\ServiceAccountCredentials;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $pageSize = 15;
        $offset = ($page - 1) * $pageSize;

        $notifications = [];
        $total = 0;

        try {
            $credentialsPath = config('firebase.projects.fingerspot.credentials');
            $config = json_decode(file_get_contents($credentialsPath), true);
            $projectId = $config['project_id'];

            $credentials = new ServiceAccountCredentials(
                ['https://www.googleapis.com/auth/cloud-platform'],
                $config
            );
            $token = $credentials->fetchAuthToken();

            $http = new Client();
            $url = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/notifications";

            $response = $http->get($url, [
                'headers' => ['Authorization' => 'Bearer ' . $token['access_token']],
                'query' => [
                    'pageSize' => $pageSize,
                    'offset' => $offset,
                    'orderBy' => 'timestamp desc',
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            if (isset($data['documents'])) {
                foreach ($data['documents'] as $doc) {
                    $fields = [];
                    foreach ($doc['fields'] as $key => $value) {
                        $valType = array_key_first($value);
                        $fields[$key] = $value[$valType];
                    }
                    $fields['id'] = basename($doc['name']);
                    $notifications[] = $fields;
                }
            }

            $total = count($notifications);
            $totalPages = max(1, (int) ceil($total / $pageSize));

        } catch (\Throwable $e) {
            $totalPages = 1;
        }

        return view('content.notifications.index', compact('notifications', 'page', 'totalPages'));
    }
}
