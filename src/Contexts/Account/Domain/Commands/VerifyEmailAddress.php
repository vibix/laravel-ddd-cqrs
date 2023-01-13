<?php

declare(strict_types=1);

namespace Contexts\Account\Domain\Commands;

use Contexts\Account\Domain\ValueObjects\AccountId;

final class VerifyEmailAddress
{
    public function __construct(private readonly AccountId $accountId)
    {
    }

    public function getAccountId(): AccountId
    {
        return $this->accountId;
    }
}
