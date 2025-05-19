<?php

namespace App\Http\Requests\NewCase;

use App\Models\ClientCase;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CourtDetailRequest extends FormRequest
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
        // dd($this->id);
        return [
            'client_id' => ['required'],
            'caseID' => ['required', 'regex:/^\d+$/', Rule::unique(ClientCase::class)->ignore($this->id)],
            'court' => ['nullable', 'regex:/^[a-zA-Z0-9\s]*$/'],
            'court_case_no' => ['nullable'],
            'court_catID' => ['required'],
            'case_court_address' => ['required'],
            'case_location' => ['nullable'],
            'responded_adv' => ['nullable'],
            'responded_adv_mobile' => ['nullable'],
            'fir_no' => ['nullable'],
            'case_legal_matter' => ['required'],
            'case_acts' => ['nullable', 'array'],
            'opponent_name' => ['required', 'regex:/^[a-zA-Z0-9\s]*$/'],
            'opponent_mobile' => ['nullable'],
            'opponent_address' => ['nullable'],
            'case_start_date' => ['nullable', 'required_with:case_end_date'],
            'case_end_date' => ['nullable'],
        ];
    }
    public function messages()
    {
        return [
            'client_id.required' => 'Client is Required.',
            'caseID.required' => 'CaseID is Required.',
            'caseID.regex' => 'CaseID must be unique.',
            'court_catID.required' => 'Select Court Category',
            'case_court_address.required' => 'Select Court Address',
            'court.regex' => 'Special Characters not allowed in Court Input.',
            'case_legal_matter.required' => 'Legal Matter is Required',
            'opponent_name.regex' => 'Special Characters not allowed in Opponent Name.',
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
