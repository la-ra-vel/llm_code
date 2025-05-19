<?php

namespace App\Http\Requests\NewCase;

use App\Rules\UniqueFeeDetailForClientCase;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class FeeDetailRequest extends FormRequest
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
            // 'fee_description_id' => ['required'],
            'fee_description_id' => [
                'required',
                'exists:fee_descriptions,id',
                new UniqueFeeDetailForClientCase(
                    $this->input('client_case_pid'),
                    $this->input('fee_description_id'),
                    $this->route('id')
                ),
            ],
            'client_case_pid' => ['required'],
            'amount' => ['required', 'regex:/^\d+$/'],
            'remarks' => ['required'],
        ];
    }
    public function messages()
    {
        return [
            'fee_description_id.required' => 'Fee Description is Required.',
            'client_case_pid.required' => 'Invaild CaseID.',
            'amount.required' => 'Amount is Required.',
            'remarks.required' => 'Remarks is Required.',
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
