<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
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
            'client_id' => $this->client_id,
            'name' => $this->name,
            'pin' => $this->pin,
            'avatar' => $this->avatar ? asset('storage/'.$this->avatar) : null,
            'created_at' => $this->created_at->format('d/M/Y H:i'),
            'updated_at' => $this->updated_at->format('d/M/Y H:i'),
        ];
    }
}
