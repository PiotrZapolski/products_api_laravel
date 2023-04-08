<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => ucfirst($this->name),
            'description' => ucfirst($this->description),
            'price' => $this->price,
            'created_at' => $this->created_at->toDateString(),
            'updated_at' => $this->updated_at->toDateString()
        ];
    }
}
