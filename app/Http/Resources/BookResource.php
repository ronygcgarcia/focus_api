<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'link_image' => $this->link_image,
            'author' => $this->author,
            'genre_id' => $this->genre_id,
            'genre' => $this->whenLoaded('genre', function () {
                return $this->genre->name;
            }),
            'publish_year' => $this->publish_year,
            'stock' => $this->stock,
            'checkout' => !!$this->whenLoaded('checkouts', function () {
                return Checkout::where('book_id', $this->id)
                    ->where('user_id', Auth::id())
                    ->where('status', false)
                    ->first();
            })
        ];
    }
}
