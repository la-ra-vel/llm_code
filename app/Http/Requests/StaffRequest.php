<?php
namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class StaffRequest extends FormRequest
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
        $rules = [
            'fname' => ['required', 'regex:/^[a-zA-Z0-9\s]*$/'],
            'lname' => ['required', 'regex:/^[a-zA-Z0-9\s]*$/'],
            'username' => ['nullable', 'regex:/^[a-zA-Z0-9_]+$/', Rule::unique(User::class)->ignore($this->id)],
            'mobile' => ['required', 'regex:/^[0-9]{10}$/', Rule::unique(User::class)->ignore($this->id)],
            'email' => ['required', Rule::unique(User::class)->ignore($this->id)],
            'firm_name' => 'required',
            'address' => 'required',

            'group_id' => 'required',
        ];

        if ($this->isMethod('post')) {
            // Add password validation rules only for creation
            $rules['password'] = ['required', 'confirmed', Rules\Password::defaults()];
            $rules['logo'] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            // Add password validation rules only if password is present for updates
            $rules['password'] = ['sometimes', 'nullable', 'confirmed', Rules\Password::defaults()];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'username.regex' => 'Special Characters not allowed in username.',
            'fname.required' => 'First Name is Required.',
            'fname.regex' => 'Special Characters not allowed in First Name.',
            'lname.required' => 'Last Name is Required.',
            'lname.regex' => 'Special Characters not allowed in Last Name.',
            'mobile.required' => 'Mobile is Required.',
            'mobile.regex' => 'The mobile number must be a 10-digit number.',
            'email.required' => 'Email is Required.',
            'firm_name.required' => 'Firm Name is Required.',
            'address.required' => 'Firm Address is Required.',
            'logo.required' => 'Logo is Required',
            'password.required' => 'Password is Required.',
            'group_id.required' => 'Role is Required.',
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

