<?php

declare(strict_types=1);

namespace App\Domain\Auth\Requests;

use App\Domain\Auth\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

final class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('user');

        return [
            'name'          => ['sometimes', 'string', 'max:255'],
            'email'         => ['sometimes', 'string', 'email', 'max:255', "unique:users,email,{$id}"],
            'password'      => ['sometimes', 'string', 'min:8', 'confirmed'],
            'role'          => ['sometimes', new Enum(UserRole::class)],
            'supervisor_id' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }
}
