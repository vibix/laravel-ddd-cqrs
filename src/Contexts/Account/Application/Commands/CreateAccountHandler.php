<?php

declare(strict_types=1);

namespace Contexts\Account\Application\Commands;

use Contexts\Account\Application\Exceptions\CreateAccountFailedException;
use Contexts\Account\Application\Exceptions\NotUniqueEmailException;
use Contexts\Account\Domain\Commands\CreateAccount;
use Contexts\Account\Domain\Entities\Account;
use Contexts\Account\Domain\Repositories\AccountRepository;
use Illuminate\Support\Facades\Event;
use Throwable;

final class CreateAccountHandler
{
    public function __construct(private readonly AccountRepository $repository)
    {
    }

    /**
     * @throws CreateAccountFailedException
     */
    public function handle(CreateAccount $command): void
    {
        try {
            $accountWithMail = $this->repository->findByEmail($command->getEmail());
            if ($accountWithMail) {
                throw new NotUniqueEmailException();
            }

            $account = Account::register(
                $command->getAccountId(),
                $command->getName(),
                $command->getEmail(),
                $command->getPassword()
            );

            $this->repository->save($account);

            foreach ($account->pullDomainEvents() as $domainEvent) {
                Event::dispatch($domainEvent);
            }
        } catch (Throwable $exception) {
            throw new CreateAccountFailedException($exception->getMessage());
        }
    }
}
