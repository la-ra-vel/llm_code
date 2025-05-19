<?php

namespace App\Http\Requests;

use App\Models\Court;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CourtRequest extends FormRequest
{
    protected $courtId;

    public function __construct()
    {
        $this->courtId = $this->route('courts');
    }
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
        $courtId = $this->route('court');
        return [
            'city_id' => 'required',
            'court_categoryID' => 'required',
            'location' => ['nullable','regex:/^[a-zA-Z0-9\s]*$/'],
            'court_name' => ['required', 'regex:/^[a-zA-Z0-9\s]*$/', Rule::unique(Court::class)->ignore($courtId)],
            'court_room_no' => 'nullable',
            'description' => 'nullable'
        ];
    }
    public function messages()
    {
        return [
            'city_id.required' => 'City is Required.',
            'court_categoryID.required' => 'Court Category is Required.',
            'location.regex' => 'Special Characters not allowed in location.',
            'court_name.required' => 'Court Name is Required.',
            'court_name.regex' => 'Special Characters not allowed in court name.',
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
