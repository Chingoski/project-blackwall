<?php

namespace App\Http\Requests\Trade;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTradeRequest extends FormRequest
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
            'games'               => ['array', 'required_without:offered_amount'],
            'games.*'             => ['array'],
            'games.*.game_id'     => ['integer', 'exists:game,id'],
            'games.*.platform_id' => ['integer', 'exists:platform,id'],
            'offered_amount'      => ['numeric', 'min:0', 'nullable', 'required_without:games'],
        ];
    }
}
