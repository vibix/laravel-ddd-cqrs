<?php

declare(strict_types=1);

namespace Contexts\Account\Domain\Events;

use Contexts\Account\Domain\ValueObjects\AccountId;
use Contexts\Account\Domain\ValueObjects\Email;
use Contexts\Shared\Events\DomainEventInterface;

final class EmailVerified implements DomainEventInterface
{
    public function __construct(private readonly AccountId $uuid, private readonly Email $email)
    {
    }

    public function getUuid(): AccountId
    {
        return $this->uuid;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }
}
