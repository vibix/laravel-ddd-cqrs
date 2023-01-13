<?php

declare(strict_types=1);

namespace App\Services\CommandBus\Middlewares;

use Doctrine\ORM\EntityManager;
use Throwable;

final class UseDatabaseTransactions
{
    public function __construct(private readonly EntityManager $entityManager)
    {
    }

    /**
     * @throws Throwable
     */
    public function handle($command, $next)
    {
        return $this->entityManager->wrapInTransaction(fn () => $next($command));
    }
}
