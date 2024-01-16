<?php

namespace App\Http\Requests\Trade;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateTradeRequest extends FormRequest
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
            'game_listing_id'     => ['required', 'integer', 'exists:game_listing,id'],
            'trader_user_id'      => ['required', 'integer', 'exists:user,id'],
            'games'               => ['array', 'required_without:offered_amount'],
            'games.*'             => ['array'],
            'games.*.game_id'     => ['integer', 'exists:game,id'],
            'games.*.platform_id' => ['integer', 'exists:platform,id'],
            'offered_amount'      => ['numeric', 'min:0', 'nullable', 'required_without:games'],
        ];
    }
}
