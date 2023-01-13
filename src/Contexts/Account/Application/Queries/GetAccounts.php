<?php

declare(strict_types=1);

namespace Contexts\Account\Application\Queries;

use Contexts\Account\Application\Models\AccountView;
use Contexts\Account\Application\Repositories\AccountViewRepository;
use Illuminate\Support\Collection;

final class GetAccounts
{
    public function __construct(private readonly AccountViewRepository $repository)
    {
    }

    /**
     * @return Collection<AccountView>
     */
    public function __invoke(): Collection
    {
        return $this->repository->findAll();
    }
}
