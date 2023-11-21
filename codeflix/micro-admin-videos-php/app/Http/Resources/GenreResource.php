<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class GenreResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "is_active" => $this->is_active,
            "created_at" => Carbon::make($this->created_at)->format('Y-m-d H:i:s'),
            "updated_at" => Carbon::make($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
