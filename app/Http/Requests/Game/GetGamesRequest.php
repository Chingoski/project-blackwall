<?php

namespace App\Http\Requests\Game;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class GetGamesRequest extends FormRequest
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
            'search'         => ['string'],
            'page'           => ['integer'],
            'genre_ids'      => ['array'],
            'genre_ids.*'    => ['integer', 'exists:genre,id'],
            'platform_ids'   => ['array'],
            'platform_ids.*' => ['integer', 'exists:platform,id'],
            'include'        => ['array'],
        ];
    }
}
