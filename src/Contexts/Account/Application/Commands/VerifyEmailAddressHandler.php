<?php

declare(strict_types=1);

namespace Contexts\Account\Application\Commands;

use Contexts\Account\Application\Exceptions\AccountNotExistsException;
use Contexts\Account\Application\Exceptions\EmailAlreadyVerifiedException;
use Contexts\Account\Domain\Commands\VerifyEmailAddress;
use Contexts\Account\Domain\Exceptions\EmailAlreadyVerifiedException as EmailAlreadyVerifiedDomainException;
use Contexts\Account\Domain\Repositories\AccountRepository;
use Illuminate\Support\Facades\Event;

final class VerifyEmailAddressHandler
{
    public function __construct(private readonly AccountRepository $repository)
    {
    }

    /**
     * @throws AccountNotExistsException
     * @throws EmailAlreadyVerifiedException
     */
    public function handle(VerifyEmailAddress $command): void
    {
        $account = $this->repository->find($command->getAccountId());
        if (!$account) {
            throw new AccountNotExistsException();
        }

        try {
            $account->verifyEmail();
        } catch (EmailAlreadyVerifiedDomainException) {
            throw new EmailAlreadyVerifiedException();
        }

        $this->repository->save($account);

        foreach ($account->pullDomainEvents() as $domainEvent) {
            Event::dispatch($domainEvent);
        }
    }
}
