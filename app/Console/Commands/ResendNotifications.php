<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Services\FirebaseService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ResendNotifications extends Command
{
    protected $signature = 'fingerspot:resend-notifications';
    protected $description = 'Resend notifications from attendance records (April 25 - June 12 2026)';

    public function handle()
    {
        $from = '2026-04-25';
        $to = '2026-06-12';

        $attendances = Attendance::whereBetween('scan_at', [$from, $to . ' 23:59:59'])
            ->orderBy('scan_at', 'asc')
            ->get();

        $total = $attendances->count();
        $this->info("Found {$total} attendance records from {$from} to {$to}");

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $firebase = new FirebaseService();
        $sent = 0;
        $failed = 0;

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

            try {
                $firebase->sendNotification($title, $body, 'all', 'android');
                $sent++;
            } catch (\Throwable $e) {
                $this->warn("\nFailed: {$title} - {$e->getMessage()}");
                $failed++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Done! Sent: {$sent}, Failed: {$failed}");
    }
}
