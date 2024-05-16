<?php

namespace App\Http\Resources;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\UserResource;
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
        $array = parent::toArray($request);

        $array['image_url'] = $this->image_url;
        $array['category']  = new CategoryResource($this->whenLoaded('category'));
        $array['user']      = new UserResource($this->whenLoaded('user'));

        return $array;
    }
}
