<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordRequest extends FormRequest
{
    protected $user;

    public function __construct()
    {
        parent::__construct();
        $this->user = auth()->user(); // Default to authenticated user
    }

    public function setUser($user)
    {
        $this->user = $user;
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
    public function rules()
    {
        return [
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', 'min:8'],
        ];
    }

    public function messages()
    {
        return [
            'current_password.required' => 'Please enter your current password.',
            'password.required' => 'Please enter a new password.',
            'password.confirmed' => 'New password and confirm password do not match.',
            'password.min' => 'The new password must be at least 8 characters long.',
        ];
    }

    protected function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            if ($this->checkCurrentPassword() === false) {
                $validator->errors()->add('current_password', 'Your current password is incorrect.');
            }
        });
    }

    protected function checkCurrentPassword()
    {
        $user = auth()->user();
        return Hash::check($this->input('current_password'), $user->password);
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
