<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
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
            'title' => 'required',
            'fname' => ['required', 'regex:/^[a-zA-Z0-9\s]*$/'],
            'lname' => ['required', 'regex:/^[a-zA-Z0-9\s]*$/'],
            'mobile' => ['required', 'regex:/^[0-9]{10}$/', Rule::unique('clients')->ignore($this->id)],
            'wp_no' => ['required', 'regex:/^[0-9]{10}$/', Rule::unique('clients')->ignore($this->id)],
            'email' => [Rule::unique('clients')->ignore($this->id)],
            'address' => 'required',
            'pincode' => ['required', 'regex:/^[0-9]{6}$/'],
            // 'visiting_date' => 'required',
            'gender' => 'required',
            'city' => 'required',
            'occupation' => 'nullable'
        ];
    }
    public function messages()
    {
        return [
            'title.required' => 'Title is Required.',
            'fname.required' => 'First Name is Required.',
            'fname.regex' => 'Special Characters not allowed in First Name.',
            'lname.required' => 'Last Name is Required.',
            'lname.regex' => 'Special Characters not allowed in Last Name.',
            'mobile.required' => 'Mobile is Required.',
            'mobile.regex' => 'The mobile number must be a 10-digit number.',
            'wp_no.required' => 'WhatsApp Number is Required.',
            'wp_no.regex' => 'The whatsapp number must be a 10-digit number.',
            'address.required' => 'Address is Required.',
            'pincode.required' => 'Pincode is Required.',
            'pincode.regex' => 'pincode must be a 6-digit number.',
            // 'visiting_date.required' => 'Visiting Date is Required.',
            'gender.required' => 'Gender is Required.',
            'city.required' => 'City is Required.',
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
