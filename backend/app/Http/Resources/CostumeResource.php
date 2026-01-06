<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CostumeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'brand' => $this->brand,
            'category' => $this->whenLoaded('category'),
            'category_id' => $this->category_id,
            'price_per_day' => (float) $this->price_per_day,
            'featured_image_url' => $this->featured_image_url,
            'is_active' => (bool) $this->is_active,
            'available' => (bool) $this->available,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
        
        if ($this->relationLoaded('costumeImages')) {
            $data['images'] = CostumeImageResource::collection($this->costumeImages);
        }
        
        if ($this->relationLoaded('sizes')) {
            $data['sizes'] = SizeResource::collection($this->sizes);
        }
        
        return $data;
    }
}
