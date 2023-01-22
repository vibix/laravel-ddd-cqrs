<?php

declare(strict_types=1);

namespace Contexts\Account\Domain\Commands;

use Contexts\Account\Domain\ValueObjects\AccountId;
use Contexts\Account\Domain\ValueObjects\Email;

final class CreateAccount
{
    public function __construct(
        private readonly AccountId $accountId,
        private readonly string $name,
        private readonly Email $email,
        private readonly string $password
    ) {
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

    public function getPassword(): string
    {
        return $this->password;
    }
}
