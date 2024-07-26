<?php

namespace App\Jobs;

use App\Models\Tran;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class FingerSyncJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $trans = Tran::count();
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
                        'hits' => $result,
                        'results' => $result->json(),
                    ]);
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }
}
