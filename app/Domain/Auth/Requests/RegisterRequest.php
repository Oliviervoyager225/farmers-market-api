<?php

declare(strict_types=1);

namespace App\Domain\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
            'role'          => ['required', 'string', 'in:admin,supervisor,operator'],
            'supervisor_id' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique'       => 'Cette adresse email est déjà utilisée.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'role.in'            => 'Le rôle doit être admin, supervisor ou operator.',
            'supervisor_id.exists' => 'Le superviseur spécifié n\'existe pas.',
        ];
    }
}
