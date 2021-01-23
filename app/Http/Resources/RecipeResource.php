<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RecipeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'description' => $this->description,
            'ingredients' => $this->ingredients,
            'steps' => $this->steps,
            'preparation_time' => $this->preparation_time,
            'difficulty' => $this->difficulty,
            'image' => $this->image,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
