<?php

namespace App\Http\Requests\GameListing;

use App\Enums\TradePreferenceEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateGameListingsRequest extends FormRequest
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
            'platform_id'      => ['required', 'integer', 'exists:platform,id'],
            'owner_id'         => ['required', 'integer', 'exists:user,id'],
            'game_id'          => ['required', 'integer', 'exists:game,id'],
            'description'      => ['string'],
            'trade_preference' => ['string', 'in:' . TradePreferenceEnum::Any->value . ',' . TradePreferenceEnum::Cash->value . ',' . TradePreferenceEnum::GameTitlesOnly->value],
        ];
    }
}
