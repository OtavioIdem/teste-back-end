<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Product */
class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'price'       => (float) $this->price,
            'description' => $this->description,
            'image_url'   => $this->image_url,
            'categories'  => $this->whenLoaded('categories', fn() => $this->categories->pluck('name')),
            'external_id' => $this->external_id,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }
}
