<?php

declare(strict_types=1);

namespace App\Domain\Farmer\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreFarmerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'firstname'        => $this->input('first_name', $this->input('firstname')),
            'lastname'         => $this->input('last_name', $this->input('lastname')),
            'credit_limit_fcfa' => $this->input('credit_limit', $this->input('credit_limit_fcfa', 0)),
        ]);
    }

    public function rules(): array
    {
        return [
            'identifier'        => ['nullable', 'string', 'max:50', 'unique:farmers,identifier'],
            'firstname'         => ['required', 'string', 'max:100'],
            'lastname'          => ['required', 'string', 'max:100'],
            'phone'             => ['nullable', 'string', 'max:20'],
            'email'             => ['nullable', 'email', 'max:255'],
            'state'             => ['nullable', 'string', 'max:100'],
            'city'              => ['nullable', 'string', 'max:100'],
            'address'           => ['nullable', 'string', 'max:255'],
            'bio'               => ['nullable', 'string'],
            'farm_size'         => ['nullable', 'numeric', 'min:0'],
            'experience'        => ['nullable', 'integer', 'min:0'],
            'categories'        => ['nullable', 'array'],
            'specialties'       => ['nullable', 'array'],
            'certification'     => ['nullable', 'string', 'max:100'],
            'primary_market'    => ['nullable', 'string', 'max:100'],
            'credit_limit_fcfa' => ['sometimes', 'numeric', 'min:0'],
        ];
    }
}
