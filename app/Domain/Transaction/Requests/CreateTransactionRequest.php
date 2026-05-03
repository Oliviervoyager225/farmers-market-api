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

    protected function prepareForValidation(): void
    {
        // Map Flutter's payment_type → payment_method
        if ($this->has('payment_type') && ! $this->has('payment_method')) {
            $this->merge(['payment_method' => $this->input('payment_type')]);
        }

        // Map Flutter's items.*.unit_price → items.*.unit_price_fcfa
        if ($this->has('items')) {
            $items = array_map(function (array $item): array {
                if (isset($item['unit_price']) && ! isset($item['unit_price_fcfa'])) {
                    $item['unit_price_fcfa'] = $item['unit_price'];
                }
                return $item;
            }, (array) $this->input('items'));
            $this->merge(['items' => $items]);
        }
    }

    public function rules(): array
    {
        return [
            'farmer_id'               => ['required', 'integer', 'exists:farmers,id'],
            'payment_method'          => ['required', 'string', 'in:cash,credit'],
            // Flutter sends decimal (0.30 = 30%) — validated as 0–1
            'interest_rate'           => ['required_if:payment_method,credit', 'nullable', 'numeric', 'min:0', 'max:1'],
            'items'                   => ['required', 'array', 'min:1'],
            'items.*.product_id'      => ['required', 'integer', 'exists:products,id'],
            'items.*.quantity'        => ['required', 'integer', 'min:1'],
            'items.*.unit_price_fcfa' => ['required', 'numeric', 'min:0'],
        ];
    }
}
