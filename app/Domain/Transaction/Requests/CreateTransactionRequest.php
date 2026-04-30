<?php

declare(strict_types=1);

namespace App\Domain\Transaction\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class CreateTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'farmer_id'            => ['required', 'integer', 'exists:farmers,id'],
            'payment_method'       => ['required', 'string', 'in:cash,credit'],
            'interest_rate'        => ['required_if:payment_method,credit', 'nullable', 'numeric', 'min:0', 'max:100'],
            'items'                => ['required', 'array', 'min:1'],
            'items.*.product_id'   => ['required', 'integer', 'exists:products,id'],
            'items.*.quantity'     => ['required', 'integer', 'min:1'],
            'items.*.unit_price_fcfa' => ['required', 'numeric', 'min:1'],
        ];
    }
}
