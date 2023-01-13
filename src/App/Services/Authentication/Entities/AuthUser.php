<?php

declare(strict_types=1);

namespace App\Services\Authentication\Entities;

use App\Services\Authentication\ValueObjects\AccountId;
use App\Services\Authentication\ValueObjects\Email;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Laravel\Passport\HasApiTokens;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use LaravelDoctrine\ORM\Notifications\Notifiable;

final class AuthUser implements AuthenticatableContract, CanResetPasswordContract
{
    use HasApiTokens;
    use CanResetPassword, Timestamps, Notifiable;

    private readonly AccountId $accountId;
    private readonly string $name;
    private readonly Email $email;
    private readonly string $password;
    private readonly string $rememberToken;

    public function getKey(): string
    {
        return $this->getAccountId()->getValue();
    }

    public function getAccountId(): AccountId
    {
        return $this->accountId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getAuthIdentifierName(): string
    {
        return 'accountId';
    }

    public function getAuthIdentifier(): string
    {
        return $this->getAccountId()->getValue();
    }

    public function getAuthPassword(): string
    {
        return $this->password;
    }

    public function getRememberToken(): string
    {
        return $this->rememberToken;
    }

    public function setRememberToken($value): void
    {
        $this->rememberToken = $value;
    }

    public function getRememberTokenName(): string
    {
        return 'rememberToken';
    }
}
