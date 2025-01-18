<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateP2PRequest extends FormRequest
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
            'Amount' => 'required|numeric|regex:/^\d{1,11}(\.\d{1,2})?$/', //ejemplo 100.00
            'BankCode' => 'required|string|max:3', //. Ejemplo: 191
            'PhoneNumber' => 'required|string|regex:/^58\d{10}$/', // Ejemplo: 584249999999
            'Reference' => 'required|integer',
        ];
    }
}
