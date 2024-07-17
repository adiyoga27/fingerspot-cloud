<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeviceResource extends JsonResource
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
            'name' => $this->name,
            'cloud_id' => $this->cloud_id,
            'created_at' => Carbon::parse($this->created_at)->format('d/M/Y H:i'),
            'updated_at' => Carbon::parse($this->updated_at)->format('d/M/Y H:i'),
            'thumbnail' => $this->thumbnail ? asset('storage/'.$this->thumbnail) : null,
        ];
    }
}
