<?php

namespace App\Http\Requests;

use App\Enums\FormValidationAcceptedFileEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class BaseFormRequest extends FormRequest
{
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama tidak boleh kosong',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator): void
    {
        if ($this->expectsJson()) {
            throw new HttpResponseException(response()->json([
                "success" => false,
                "message" => "Field validation error",
                "errors" => $validator->errors(),
            ], 422));
        }

        // throw (new ValidationException($validator))
        //     ->errorBag($this->errorBag)
        //     ->redirectTo($this->getRedirectUrl());

        $this->validator = $validator;

        throw new HttpResponseException(
            redirect()
                ->back()
                ->with('error', "There is still validation error when submiting form")
                ->withInput($this->input())
                ->withErrors($validator, $this->errorBag)
        );
    }
}
