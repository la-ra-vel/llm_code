<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class QuotationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'quotation_no' => ['required'],
            'date' => ['required'],
            'time' => ['nullable'],
            'subject' => ['required'],
            'client_name' => ['required'],
            'client_mobile' => ['required'],
            'client_address' => ['required'],
        ];
    }
    public function messages()
    {
        return [
            'quotation_no.required' => 'Invalid Quotation #',
            'date.required' => 'Quotation date is Required',
            'subject.required' => 'Subject is Required',
            'client_name.required' => 'Client Name is Required.',
            'client_mobile.required' => 'Client Mobile is Required.',
            'client_address.required' => 'Client Address is Required.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        if ($this->ajax()) {
            throw new HttpResponseException(response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
            ]));
        } else {
            parent::failedValidation($validator);
        }
    }
}
