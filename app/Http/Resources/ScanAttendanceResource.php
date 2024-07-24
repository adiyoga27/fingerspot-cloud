<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScanAttendanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'scan_at' => Carbon::parse($this->scan_at)->format('d F Y H:i'),
            'scan_verify' => $this->scan_verify,
            'scan_status' => $this->checkScanStatus($this->scan_status),
        ];
    }
    public function checkScanStatus($scan_status)
    {
        switch ($scan_status) {
            case 1:
                return 'Masuk';
                break;
            case 2 : 
                return 'Pulang';
                break;
            default:
                # code...
                return 'Istirahat';
                break;
        }
    }
}
