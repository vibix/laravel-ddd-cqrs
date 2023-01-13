<?php

declare(strict_types=1);

namespace Contexts\Account\Infrastructure\Repositories;

use Contexts\Account\Domain\Entities\Account;
use Contexts\Account\Domain\Repositories\AccountRepository;
use Contexts\Account\Domain\ValueObjects\AccountId;
use Contexts\Account\Domain\ValueObjects\Email;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ObjectRepository;
use Illuminate\Support\Collection;

class DoctrineAccountRepository implements AccountRepository
{
    public function __construct(private readonly EntityManager $entityManager)
    {
    }

    public function find(AccountId $id): ?Account
    {
        return $this->getRepository()->find($id->getValue());
    }

    public function findByEmail(Email $email): ?Account
    {
        return $this->getRepository()->findOneBy([
            'email.value' => $email->getValue(),
        ]);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function save(Account $account): void
    {
        $this->entityManager->persist($account);
        $this->entityManager->flush();
    }

    private function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(Account::class);
    }
}
