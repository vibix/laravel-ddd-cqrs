<?php

declare(strict_types=1);

namespace Contexts\OneTimePassword\Infrastructure\Repositories;

use Contexts\OneTimePassword\Domain\Entities\OneTimePassword;
use Contexts\OneTimePassword\Domain\Enums\OneTimePasswordType;
use Contexts\OneTimePassword\Domain\Repositories\OneTimePasswordRepository;
use Contexts\OneTimePassword\Domain\ValueObjects\OneTimePasswordId;
use Contexts\OneTimePassword\Domain\ValueObjects\SubjectId;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ObjectRepository;

class DoctrineOneTimePasswordRepository implements OneTimePasswordRepository
{
    public function __construct(private readonly EntityManager $entityManager)
    {
    }

    public function find(OneTimePasswordId $id): ?OneTimePassword
    {
        return $this->getRepository()->find($id->getValue());
    }

    public function findByTypeAndSubject(OneTimePasswordType $type, SubjectId $subjectId): ?OneTimePassword
    {
        return $this->getRepository()->findOneBy([
            'type' => $type,
            'subjectId.value' => $subjectId->getValue(),
        ]);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function save(OneTimePassword $oneTimePassword): void
    {
        $this->entityManager->persist($oneTimePassword);
        $this->entityManager->flush();
    }

    private function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(OneTimePassword::class);
    }
}
