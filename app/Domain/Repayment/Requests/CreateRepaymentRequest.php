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

    public function rules(): array
    {
        return [
            'farmer_id'          => ['required', 'integer', 'exists:farmers,id'],
            'kg_received'        => ['required', 'numeric', 'min:0.001'],
            'commodity_rate_fcfa' => ['required', 'numeric', 'min:1'],
        ];
    }
}
