<?php

declare(strict_types=1);

namespace App\Domain\Farmer\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateFarmerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'firstname'        => ['required', 'string', 'max:100'],
            'lastname'         => ['required', 'string', 'max:100'],
            'phone'            => ['nullable', 'string', 'max:20'],
            'credit_limit_fcfa' => ['sometimes', 'numeric', 'min:0'],
        ];
    }
}
