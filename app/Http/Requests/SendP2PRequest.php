<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendP2PRequest extends FormRequest
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
            'Amount' => 'required|numeric|regex:/^\d{1,11}(\.\d{1,2})?$/',
            'BeneficiaryBankCode' => 'required|string',
            'BeneficiaryCellPhone' => 'required|string|regex:/^58\d{10}$/',
            'BeneficiaryEmail' => 'nullable|email',
            'BeneficiaryID' => 'required|string|regex:/^[A-Z]\d{1,9}$/', //ejemplo V123456789
            'BeneficiaryName' => 'required|string', //ejemplo Juan Perez
            'Description' => 'required|string|max:100', //ejemplo Pago de servicios
            'OperationRef' => 'nullable|string|max:20', //ejemplo 123456
        ];
    }

    public function messages()
    {
        return [
            'Amount.required' => 'El monto es requerido',
            'Amount.numeric' => 'El monto debe ser un número',
            'Amount.regex' => 'El monto debe ser un número con máximo 2 decimales',
            'BeneficiaryBankCode.required' => 'El código del banco del beneficiario es requerido',
            'BeneficiaryBankCode.string' => 'El código del banco del beneficiario debe ser un string',
            'BeneficiaryCellPhone.required' => 'El número de teléfono del beneficiario es requerido',
            'BeneficiaryCellPhone.string' => 'El número de teléfono del beneficiario debe ser una cadena de texto',
            'BeneficiaryCellPhone.regex' => 'El número de teléfono del beneficiario debe tener el formato 58XXXXXXXXXX',
            'BeneficiaryEmail.email' => 'El correo electrónico del beneficiario debe ser un correo válido',
            'BeneficiaryID.required' => 'La cédula del beneficiario es requerida',
            'BeneficiaryID.string' => 'La cédula del beneficiario debe ser una cadena de texto',
            'BeneficiaryID.regex' => 'La cédula del beneficiario debe tener el formato VXXXXXXXXX',
            'BeneficiaryName.required' => 'El nombre del beneficiario es requerido',
            'BeneficiaryName.string' => 'El nombre del beneficiario debe ser una cadena de texto',
            'Description.required' => 'La descripción de la operación es requerida',
            'Description.string' => 'La descripción de la operación debe ser una cadena de texto',
            'Description.max' => 'La descripción de la operación debe tener máximo 100 caracteres',
            'OperationRef.string' => 'La referencia de la operación debe ser una cadena de texto',
            'OperationRef.max' => 'La referencia de la operación debe tener máximo 20 caracteres',
        ];
    }
}
