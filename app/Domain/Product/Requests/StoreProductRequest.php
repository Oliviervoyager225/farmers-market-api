<?php

declare(strict_types=1);

namespace App\Domain\Product\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'price_fcfa'  => ['required', 'numeric', 'min:1'],
        ];
    }
}
