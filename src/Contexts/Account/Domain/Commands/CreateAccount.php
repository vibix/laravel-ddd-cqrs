<?php

declare(strict_types=1);

namespace Contexts\Account\Domain\Commands;

use Contexts\Account\Domain\ValueObjects\Email;

final class CreateAccount
{
    public function __construct(
        private readonly string $name,
        private readonly Email $email,
        private readonly string $password
    ) {
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
