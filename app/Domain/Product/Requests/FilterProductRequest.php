<?php

declare(strict_types=1);

namespace App\Domain\Product\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class FilterProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'category_id'    => ['sometimes', 'integer', 'exists:categories,id'],
            'vendor_id'      => ['sometimes', 'integer', 'exists:vendors,id'],
            'min_price'      => ['sometimes', 'numeric', 'min:0'],
            'max_price'      => ['sometimes', 'numeric', 'min:0'],
            'search'         => ['sometimes', 'string', 'max:100'],
            'available_only' => ['sometimes', 'boolean'],
            'per_page'       => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
