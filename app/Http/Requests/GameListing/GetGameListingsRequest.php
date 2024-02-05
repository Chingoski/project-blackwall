<?php

namespace App\Http\Requests\GameListing;

use App\Enums\TradePreferenceEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class GetGameListingsRequest extends FormRequest
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
            'search'           => ['string'],
            'platform_id'      => ['integer', 'exists:platform,id'],
            'owner_id'         => ['integer', 'exists:user,id'],
            'city_id'          => ['integer', 'exists:city,id'],
            'order_by'         => ['string', 'in:alphabetical,chronological'],
            'include'          => ['array'],
            'trade_preference' => ['string', 'in:' . TradePreferenceEnum::Any->value . ',' . TradePreferenceEnum::Cash->value . ',' . TradePreferenceEnum::GameTitlesOnly->value],
            'page'             => ['integer'],
            'finished'         => ['boolean'],
            'ongoing'          => ['boolean'],
            'accepted'         => ['boolean'],
            'available'        => ['booelan'],
        ];
    }
}
