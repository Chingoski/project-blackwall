<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name'    => ['string'],
            'last_name'     => ['string'],
            'phone_number'  => ['string', 'regex:/^\+3897[012578]\d{6}$/'],
            'city_id'       => ['integer', 'exists:city,id'],
            'address'       => ['string'],
            'date_of_birth' => ['date'],
            'email'         => ['email', 'unique:user,email'],
        ];
    }
}
