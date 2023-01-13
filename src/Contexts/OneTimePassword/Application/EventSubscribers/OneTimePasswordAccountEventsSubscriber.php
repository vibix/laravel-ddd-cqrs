<?php

declare(strict_types=1);

namespace Contexts\OneTimePassword\Application\EventSubscribers;

use App\Services\CommandBus\CommandBus;
use Contexts\Account\Domain\Events\AccountCreated;
use Contexts\OneTimePassword\Application\Enums\OneTimePasswordType;
use Contexts\OneTimePassword\Domain\Commands\GenerateOneTimePassword;
use Contexts\OneTimePassword\Domain\ValueObjects\SubjectId;
use Illuminate\Events\Dispatcher;

final class OneTimePasswordAccountEventsSubscriber
{
    public function __construct(private readonly CommandBus $commandBus)
    {
    }

    public function subscribe(Dispatcher $events): array
    {
        return [
            AccountCreated::class => 'handleAccountCreated',
        ];
    }

    public function handleAccountCreated(AccountCreated $event): void
    {
        $this->commandBus->dispatch(
            new GenerateOneTimePassword(
                OneTimePasswordType::ACCOUNT_EMAIL_VERIFICATION,
                new SubjectId($event->getUuid()->getValue())
            )
        );
    }
}
