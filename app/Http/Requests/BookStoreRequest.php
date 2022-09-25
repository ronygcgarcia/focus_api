<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|max:255',
            'description' => 'required|string|max:255',
            'author' => 'required',
            'link_image' => 'required|string',
            'publish_year' => 'required|integer',
            'genre_id' => 'required|integer|exists:genres,id',
            'stock' => 'required|integer'
        ];
    }
}
