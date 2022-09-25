<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class BookIndexRequest extends FormRequest
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
            'title' => 'sometimes|string|nullable',
            'author' => 'sometimes|string|nullable',
            'genre_id' => 'sometimes|integer|exists:genres,id|nullable',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function () {
            if (is_null($this->title)) {
                $this->merge(['title' => null]);
            }
            if (is_null($this->author)) {
                $this->merge(['author' => null]);
            }
            if (is_null($this->genre_id)) {
                $this->merge(['genre_id' => null]);
            }
        });
    }
}
