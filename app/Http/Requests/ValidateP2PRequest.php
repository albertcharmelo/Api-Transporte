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

    public function messages()
    {
        return [
            'Amount.required' => 'Amount is required',
            'Amount.numeric' => 'Amount must be a number',
            'Amount.regex' => 'Amount must be a number with a maximum of 11 digits and 2 decimals',
            'BankCode.required' => 'BankCode is required',
            'BankCode.integer' => 'BankCode must be an integer',
            'PhoneNumber.required' => 'PhoneNumber is required',
            'PhoneNumber.string' => 'PhoneNumber must be a string',
            'PhoneNumber.regex' => 'PhoneNumber must be a string with 12 digits starting with 58 and the rest of the digits',
            'Reference.required' => 'Reference is required',
            'Reference.integer' => 'Reference must be an integer',

        ];
    }
}
