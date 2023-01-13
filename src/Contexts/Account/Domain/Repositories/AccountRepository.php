<?php

declare(strict_types=1);

namespace Contexts\Account\Domain\Repositories;

use Contexts\Account\Domain\Entities\Account;
use Contexts\Account\Domain\ValueObjects\AccountId;
use Contexts\Account\Domain\ValueObjects\Email;

interface AccountRepository
{
    public function find(AccountId $id): ?Account;

    public function findByEmail(Email $email): ?Account;

    public function save(Account $account): void;
}
