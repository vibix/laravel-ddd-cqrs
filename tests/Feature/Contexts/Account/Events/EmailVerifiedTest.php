<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Account\Events;

use App\Services\CommandBus\CommandBus;
use Contexts\Account\Application\Exceptions\AccountNotExistsException;
use Contexts\Account\Application\Exceptions\EmailAlreadyVerifiedException;
use Contexts\Account\Application\Notifications\EmailConfirmedNotification;
use Contexts\Account\Domain\Commands\VerifyEmailAddress;
use Contexts\Account\Domain\Entities\Account;
use Contexts\Account\Domain\Events\EmailVerified;
use Contexts\Account\Domain\ValueObjects\AccountId;
use Contexts\Account\Domain\ValueObjects\Email;
use DateTime;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Tests\TestCase;

class EmailVerifiedTest extends TestCase
{
    use WithFaker;

    public function test_it_should_send_email_on_email_verified_event(): void
    {
        Notification::fake();

        $accountId = new AccountId(Str::uuid()->toString());
        $email = new Email($this->faker->email);

        entity(Account::class)->create([
            'accountId' => $accountId,
            'email' => $email,
        ]);

        Event::dispatch(new EmailVerified($accountId, $email));

        Notification::assertSentTimes(EmailConfirmedNotification::class, 1);
    }

    public function test_it_should_not_send_email_on_email_verified_event_when_account_not_exists(): void
    {
        Notification::fake();

        $accountId = new AccountId(Str::uuid()->toString());
        $email = new Email($this->faker->email);

        $this->expectException(AccountNotExistsException::class);
        $this->expectExceptionMessage(__('accounts.exceptions.not_exists'));

        Event::dispatch(new EmailVerified($accountId, $email));

        Notification::assertNothingSent();
    }
}
