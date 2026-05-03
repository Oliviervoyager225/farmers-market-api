<?php

declare(strict_types=1);

namespace App\Domain\Category\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('category');

        return [
            'name'        => ['required', 'string', 'max:100', "unique:categories,name,{$id}"],
            'icon'        => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string', 'max:500'],
            'parent_id'   => ['nullable', 'integer', 'exists:categories,id'],
        ];
    }
}
