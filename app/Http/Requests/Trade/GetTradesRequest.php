<?php

namespace App\Http\Requests\Trade;

use App\Enums\TradeStatusEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class GetTradesRequest extends FormRequest
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
            'game_listing_id' => ['integer', 'exists:game_listing,id'],
            'trader_user_id'  => ['required_without:owner_id', 'integer', 'exists:user,id'],
            'owner_id'        => ['required_without:trader_user_id', 'integer', 'exists:user,id'],
            'status'          => ['integer', 'in:' . TradeStatusEnum::Pending->value . ',' . TradeStatusEnum::Accepted->value . ',' . TradeStatusEnum::Finished->value . ',' . TradeStatusEnum::Canceled->value . ',' . TradeStatusEnum::Expired->value],
            'search'          => ['string'],
        ];
    }
}
