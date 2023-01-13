<?php

declare(strict_types=1);

namespace Contexts\Account\Application\EventSubscribers;

use App\Services\CommandBus\CommandBus;
use Contexts\Account\Application\Exceptions\AccountNotExistsException;
use Contexts\Account\Application\Notifications\EmailVerificationNotification;
use Contexts\Account\Application\Repositories\AccountViewRepository;
use Contexts\Account\Domain\Commands\VerifyEmailAddress;
use Contexts\Account\Domain\ValueObjects\AccountId;
use Contexts\OneTimePassword\Application\Enums\OneTimePasswordType;
use Contexts\OneTimePassword\Domain\Events\OneTimePasswordConfirmed;
use Contexts\OneTimePassword\Domain\Events\OneTimePasswordGenerated;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Notification;

final class AccountOneTimePasswordEventsSubscriber
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly AccountViewRepository $accountViewRepository
    ) {
    }

    public function subscribe(Dispatcher $events): array
    {
        return [
            OneTimePasswordGenerated::class => 'handleOneTimePasswordGenerated',
            OneTimePasswordConfirmed::class => 'handleOneTimePasswordConfirmed',
        ];
    }

    /**
     * @throws AccountNotExistsException
     */
    public function handleOneTimePasswordGenerated(OneTimePasswordGenerated $event): void
    {
        match ($event->getType()) {
            OneTimePasswordType::ACCOUNT_EMAIL_VERIFICATION => $this->handleGeneratedAccountEmailVerificationOtp($event)
        };
    }

    public function handleOneTimePasswordConfirmed(OneTimePasswordConfirmed $event): void
    {
        match ($event->getType()) {
            OneTimePasswordType::ACCOUNT_EMAIL_VERIFICATION => $this->handleConfirmedAccountEmailVerificationOtp($event)
        };
    }

    /**
     * @throws AccountNotExistsException
     */
    private function handleGeneratedAccountEmailVerificationOtp(OneTimePasswordGenerated $event): void
    {
        $account = $this->accountViewRepository->find(new AccountId($event->getSubjectId()->getValue()));

        if (!$account) {
            throw new AccountNotExistsException();
        }

        Notification::route('mail', $account->getEmail()->getValue())
            ->notify(new EmailVerificationNotification($account->getName(), $event->getCode()));
    }

    private function handleConfirmedAccountEmailVerificationOtp(OneTimePasswordConfirmed $event): void
    {
        $this->commandBus->dispatch(new VerifyEmailAddress(new AccountId($event->getSubjectId()->getValue())));
    }
}
