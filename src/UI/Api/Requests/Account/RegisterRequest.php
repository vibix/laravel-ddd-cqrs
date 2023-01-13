<?php

declare(strict_types=1);

namespace UI\Api\Requests\Account;

use Contexts\Account\Domain\Entities\Account;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

final class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:' . Account::class . ',email.value',
            'password' => ['required', Password::min(8)->letters()->numbers()->mixedCase()->symbols()],
        ];
    }
}
