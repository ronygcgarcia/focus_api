<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CheckoutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'checkout_date' => $this->checkout_date,
            'user_id' => $this->user_id,
            'first_name' =>  $this->whenLoaded('user', function () {
                return $this->user->first_name;
            }),
            'last_name' =>  $this->whenLoaded('user', function () {
                return $this->user->last_name;
            }),
            'status' => $this->status,
            'book' => $this->whenLoaded('book', function () {
                return new BookResource($this->book);
            })
        ];
    }
}
