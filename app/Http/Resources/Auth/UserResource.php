<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'created' => $this->created_at->diffForHumans(),
            'email' => $this->email,
            'is_admin' => $this->hasRole('admin'),
            'banned' => $this->banned
        ];
    }
}
