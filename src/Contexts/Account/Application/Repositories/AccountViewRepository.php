<?php

declare(strict_types=1);

namespace Contexts\Account\Application\Repositories;

use Contexts\Account\Application\Models\AccountView;
use Contexts\Account\Domain\Entities\Account;
use Contexts\Account\Domain\ValueObjects\AccountId;
use Contexts\Account\Domain\ValueObjects\Email;
use Illuminate\Support\Collection;

interface AccountViewRepository
{
    public function find(AccountId $id): ?AccountView;

    public function findByEmail(Email $email): ?AccountView;

    /**
     * @return Collection<AccountView>
     */
    public function findAll(): Collection;
}
