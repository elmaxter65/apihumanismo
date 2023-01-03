<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name'      => 'required|string|max:200',
            'email'     => 'required|string|email|max:200',
            'image'     => 'nullable|mimes:jpeg,png,jpg|max:10240',
            'role'      => 'required|integer',
        ];
    }
}
