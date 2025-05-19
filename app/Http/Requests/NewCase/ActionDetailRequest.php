<?php

namespace App\Http\Requests\NewCase;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class ActionDetailRequest extends FormRequest
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
            'hearing_date' => ['required'],
            'client_case_pid' => ['required'],
            'note' => ['required'],
        ];
    }
    public function messages()
    {
        return [
            'hearing_date.required' => 'Hearing Date is Required.',
            'client_case_pid.required' => 'Invaild CaseID.',
            'note.required' => 'Note is Required.',
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
