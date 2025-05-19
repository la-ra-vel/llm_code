<?php

namespace App\Http\Requests;

use App\Models\Country;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CountryRequest extends FormRequest
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
            'name' => ['required', 'regex:/^[a-zA-Z0-9\s]*$/', Rule::unique(Country::class)->ignore($this->id)],
            'code' => ['required', Rule::unique(Country::class)->ignore($this->id)],
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Country Name is Required.',
            'name.regex' => 'Special Characters not allowed in Country Name.',
            'code.required' => 'Country code is Required.',
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
