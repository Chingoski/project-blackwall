<?php

namespace App\Http\Requests\GameListing;

use App\Enums\TradePreferenceEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGameListingsRequest extends FormRequest
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
            'description'      => ['nullable', 'string'],
            'trade_preference' => ['string', 'in:' . TradePreferenceEnum::Any->value . ',' . TradePreferenceEnum::Cash->value . ',' . TradePreferenceEnum::GameTitlesOnly->value],
        ];
    }
}
