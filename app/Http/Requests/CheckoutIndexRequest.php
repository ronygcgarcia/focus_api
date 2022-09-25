<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CheckoutIndexRequest extends FormRequest
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
            'user_id' => 'sometimes|exists:users,id|integer'
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function () {
            if (is_null($this->user_id)) {
                $this->merge(['user_id' => null]);
            }            
        });
    }
}
