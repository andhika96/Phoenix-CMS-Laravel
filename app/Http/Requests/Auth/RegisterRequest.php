<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseFormRequest;
use App\Models\User;
use Illuminate\Validation\Rules;

class RegisterRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'phone.required' => 'Nomor telepon wajib diisi',
            'phone.min' => 'Nomor telepon minimal terdiri dari 10 digit',
            'phone.regex' => 'Nomor telepon tidak valid',
            'firstname.required' => 'Nama wajib diisi'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'firstname' => 'required|string|max:255',
            'lastname' => 'string|max:255',
            'username' => 'string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'phone' => 'required|min:10|regex:/^[1-9][0-9]{8}/',
            'password' => 'required|min:6',
            'confirm_password' => 'same:password',
            'terms' => $this->expectsJson() ? ['nullable'] : ['required']
        ];
    }
}
