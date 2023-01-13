<?php

declare(strict_types=1);

namespace Contexts\Account\Infrastructure\Repositories;

use Contexts\Account\Application\Models\AccountView;
use Contexts\Account\Application\Repositories\AccountViewRepository;
use Contexts\Account\Domain\ValueObjects\AccountId;
use Contexts\Account\Domain\ValueObjects\Email;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectRepository;
use Illuminate\Support\Collection;

class DoctrineAccountViewRepository implements AccountViewRepository
{
    public function __construct(
        private readonly EntityManager $entityManager,
//        private readonly Connection $connection
    ) {
    }

    public function find(AccountId $id): ?AccountView
    {
        $account = $this->getRepository()->find($id->getValue());
        if ($account) {
            $this->entityManager->getUnitOfWork()->markReadOnly($account);
        }
        return $account;
    }

    public function findByEmail(Email $email): ?AccountView
    {
        // Query builder
//        $queryBuilder = $this->connection->createQueryBuilder();
//        $queryBuilder
//            ->select('id, name, email, email_verified_at, password')
//            ->from('users');
//
//        $accountData = $this->connection->fetchAllAssociative($queryBuilder->getSQL(), $queryBuilder->getParameters());
//
//        return $accountData ? new AccountView(
//            new AccountId((string) $accountData['id']),
//            (string) $accountData['name'],
//            new Email((string) $accountData['email']),
//            (string) $accountData['password'],
//            $accountData['emailVerifiedAt']
//        ) : null;
        // repository
        $account = $this->getRepository()->findOneBy([
            'email.value' => $email->getValue(),
        ]);
        if ($account) {
            $this->entityManager->getUnitOfWork()->markReadOnly($account);
        }
        return $account;
    }

    /**
     * @return Collection<AccountView>
     */
    public function findAll(): Collection
    {
        return collect($this->getRepository()->findBy([], ['createdAt' => 'desc']))
            ->each(fn (AccountView $account) => $this->entityManager->getUnitOfWork()->markReadOnly($account));
    }

    private function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(AccountView::class);
    }
}
