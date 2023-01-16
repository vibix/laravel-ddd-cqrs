<?php

declare(strict_types=1);

namespace Contexts\Account\Application\EventSubscribers;

use Contexts\Account\Application\Exceptions\AccountNotExistsException;
use Contexts\Account\Application\Notifications\EmailConfirmedNotification;
use Contexts\Account\Application\Repositories\AccountViewRepository;
use Contexts\Account\Domain\Events\EmailVerified;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Notification;

final class AccountEventsSubscriber
{
    public function __construct(private readonly AccountViewRepository $accountViewRepository)
    {
    }

    public function subscribe(Dispatcher $events): array
    {
        return [
            EmailVerified::class => 'handleAccountEmailVerified',
        ];
    }

    /**
     * @throws AccountNotExistsException
     */
    public function handleAccountEmailVerified(EmailVerified $event): void
    {
        $account = $this->accountViewRepository->find($event->getUuid());

        if (!$account) {
            throw new AccountNotExistsException();
        }

        Notification::route('mail', $account->getEmail()->getValue())
            ->notify(new EmailConfirmedNotification($account->getName()));
    }
}
