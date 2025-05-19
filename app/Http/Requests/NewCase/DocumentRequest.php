<?php

namespace App\Http\Requests\NewCase;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class DocumentRequest extends FormRequest
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
            'document_name' => ['required'],
            'client_case_pid' => ['required'],
            'file' => ['nullable', 'mimes:pdf,jpg,png,jpeg,xlsx,xls,doc,docx,ppt,pptx,txt'],
        ];
    }
    public function messages()
    {
        return [
            'document_name.required' => 'Document Name is Required.',
            'client_case_pid.required' => 'Invaild CaseID.',
            'file.required' => 'Please choose a file.',
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
