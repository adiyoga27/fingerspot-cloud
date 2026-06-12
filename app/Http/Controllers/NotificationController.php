<?php

namespace App\Http\Controllers;

use Google\Auth\Credentials\ServiceAccountCredentials;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = [];

        try {
            $credentialsPath = config('firebase.projects.fingerspot.credentials');
            $credentialsPath = base_path($credentialsPath);
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
                    'pageSize' => 50,
                    'orderBy' => 'timestamp desc',
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            if (isset($data['documents'])) {
                foreach ($data['documents'] as $doc) {
                    $fields = [];
                    foreach ($doc['fields'] as $key => $value) {
                        $valType = array_key_first($value);
                        $val = $value[$valType];
                        if (is_array($val) && isset($val['values'])) {
                            $val = collect($val['values'])->pluck('stringValue')->implode(', ');
                        }
                        $fields[$key] = $val;
                    }
                    $fields['id'] = basename($doc['name']);
                    $notifications[] = $fields;
                }
            }

        } catch (\Throwable $e) {
            logger()->error('Firestore fetch failed: ' . $e->getMessage());
        }

        return view('content.notifications.index', compact('notifications'));
    }
}
