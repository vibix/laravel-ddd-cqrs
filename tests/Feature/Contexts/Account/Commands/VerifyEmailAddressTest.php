<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Account\Commands;

use App\Services\CommandBus\CommandBus;
use Contexts\Account\Application\Exceptions\AccountNotExistsException;
use Contexts\Account\Application\Exceptions\EmailAlreadyVerifiedException;
use Contexts\Account\Domain\Commands\VerifyEmailAddress;
use Contexts\Account\Domain\Entities\Account;
use Contexts\Account\Domain\Events\EmailVerified;
use Contexts\Account\Domain\ValueObjects\AccountId;
use DateTime;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Tests\TestCase;

class VerifyEmailAddressTest extends TestCase
{
    use WithFaker;

    private CommandBus $commandBus;

    protected function setUp(): void
    {
        parent::setUp();

        $this->commandBus = app(CommandBus::class);
    }

    public function test_it_should_verify_email_address_successful(): void
    {
        Event::fake();

        $accountId = new AccountId(Str::uuid()->toString());
        entity(Account::class)->create([
            'accountId' => $accountId,
            'emailVerifiedAt' => null,
        ]);

        $command = new VerifyEmailAddress($accountId);

        $this->commandBus->dispatch($command);

        Event::assertDispatched(EmailVerified::class);

        $this->assertDatabaseHas('users', [
            'id' => $command->getAccountId(),
            'email_verified_at' => new DateTime(),
        ]);
    }

    public function test_it_should_throw_account_not_exists_on_verify_email_address(): void
    {
        Event::fake();

        $accountId = new AccountId(Str::uuid()->toString());

        $command = new VerifyEmailAddress($accountId);

        $this->expectException(AccountNotExistsException::class);
        $this->expectExceptionMessage(__('accounts.exceptions.not_exists'));

        $this->commandBus->dispatch($command);

        Event::assertNothingDispatched();
    }

    public function test_it_should_throw_email_already_verified_on_verify_email_address(): void
    {
        Event::fake();

        $accountId = new AccountId(Str::uuid()->toString());
        entity(Account::class)->create([
            'accountId' => $accountId,
            'emailVerifiedAt' => new DateTime(),
        ]);

        $command = new VerifyEmailAddress($accountId);

        $this->expectException(EmailAlreadyVerifiedException::class);
        $this->expectExceptionMessage(__('accounts.exceptions.email_already_verified'));

        $this->commandBus->dispatch($command);

        Event::assertNothingDispatched();
    }
}
