<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use Carbon\Carbon;
use Google\Auth\Credentials\ServiceAccountCredentials;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class SyncNotifications extends Command
{
    protected $signature = 'fingerspot:sync-notifications {--chunk=50 : Chunk size per batch} {--from= : Start date (Y-m-d)} {--to= : End date (Y-m-d)}';
    protected $description = 'Sync attendance records to Firestore notifications';

    public function handle()
    {
        $credentialsPath = base_path(config('firebase.projects.fingerspot.credentials'));
        $config = json_decode(file_get_contents($credentialsPath), true);
        $projectId = $config['project_id'];

        $credentials = new ServiceAccountCredentials(
            ['https://www.googleapis.com/auth/cloud-platform'],
            $config
        );
        $token = $credentials->fetchAuthToken();
        $http = new Client();

        $url = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/notifications";

        $chunk = (int) $this->option('chunk');
        $from = $this->option('from');
        $to = $this->option('to');

        $query = Attendance::orderBy('scan_at', 'desc');

        if ($from) {
            $query->where('scan_at', '>=', $from);
        }
        if ($to) {
            $query->where('scan_at', '<=', $to . ' 23:59:59');
        }

        $total = $query->count();

        $this->info("Total attendance records: {$total}");
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        Attendance::orderBy('scan_at')->chunk($chunk, function ($attendances) use ($http, $url, $token, $bar) {
            foreach ($attendances as $att) {
                $scanTime = Carbon::parse($att->scan_at);
                $statusLabel = match ($att->scan_status) {
                    '0' => 'MASUK',
                    '1' => 'PULANG',
                    '2' => 'ISTIRAHAT',
                    '3' => 'KEMBALI ISTIRAHAT',
                    default => 'ABSENSI',
                };

                $title = strtoupper($att->employee_name) . ' SCAN ' . $statusLabel;
                $body = $att->employee_name . ' melakukan scan pada waktu ' . $scanTime->format('d F Y H:i') . ' wita';

                $fields = [
                    'title'       => ['stringValue' => $title],
                    'message'     => ['stringValue' => $body],
                    'type'        => ['stringValue' => 'info'],
                    'clickable'   => ['integerValue' => '1'],
                    'to'          => [
                        'arrayValue' => [
                            'values' => [['stringValue' => 'all']],
                        ],
                    ],
                    'read_by'     => ['arrayValue' => ['values' => []]],
                    'timestamp'   => ['integerValue' => (string) $scanTime->getTimestampMs()],
                    'data'        => [
                        'mapValue' => [
                            'fields' => [
                                'link' => ['nullValue' => null],
                            ],
                        ],
                    ],
                ];

                try {
                    $http->post($url, [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $token['access_token'],
                            'Content-Type' => 'application/json',
                        ],
                        'json' => ['fields' => $fields],
                    ]);
                } catch (\Throwable $e) {
                    $this->warn(' Skip: ' . $e->getMessage());
                }

                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine();
        $this->info('Sync completed!');
    }
}
