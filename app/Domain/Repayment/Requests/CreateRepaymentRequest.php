<?php

declare(strict_types=1);

namespace App\Domain\Repayment\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class CreateRepaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // Map Flutter's rate_per_kg → commodity_rate_fcfa
        if ($this->has('rate_per_kg') && ! $this->has('commodity_rate_fcfa')) {
            $this->merge(['commodity_rate_fcfa' => $this->input('rate_per_kg')]);
        }
    }

    public function rules(): array
    {
        return [
            'farmer_id'           => ['required', 'integer', 'exists:farmers,id'],
            'commodity'           => ['required', 'string', 'max:100'],
            'kg_received'         => ['required', 'numeric', 'min:0.001'],
            'commodity_rate_fcfa' => ['required', 'numeric', 'min:1'],
        ];
    }
}
