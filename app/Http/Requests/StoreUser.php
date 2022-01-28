<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUser extends FormRequest
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
            'name' => 'required|string',
            'type_id_card'=>'required|string|min:1|max:1',
            'id_card'=>'required|string|unique:users,id_card',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed|min:8|max:16',
    
        ];
    }
}
