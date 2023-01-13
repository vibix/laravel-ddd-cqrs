<?php

declare(strict_types=1);

namespace Contexts\Account\Application\Models;

use Contexts\Account\Domain\ValueObjects\AccountId;
use Contexts\Account\Domain\ValueObjects\Email;
use DateTime;

class AccountView
{
    public function __construct(
        private readonly AccountId $accountId,
        private readonly string $name,
        private readonly Email $email,
        private readonly string $password,
        private readonly ?DateTime $emailVerifiedAt,
        private readonly ?DateTime $createdAt,
        private readonly ?DateTime $updatedAt
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

    public function getEmailVerifiedAt(): ?DateTime
    {
        return $this->emailVerifiedAt;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }
}
