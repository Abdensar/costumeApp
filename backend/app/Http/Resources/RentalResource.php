<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RentalResource extends JsonResource
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
            'user' => new UserResource($this->whenLoaded('user')),
            'costume' => new CostumeResource($this->whenLoaded('costume')),
            'start_date' => $this->start_date->format('Y-m-d'),
            'end_date' => $this->end_date->format('Y-m-d'),
            'total_price' => (float) $this->total_price,
            'status' => $this->status,
            'qr_code' => $this->qr_code,
            'days_count' => $this->getDaysCount(),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
