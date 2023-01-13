<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Account\Commands;

use App\Services\CommandBus\CommandBus;
use Contexts\Account\Application\Exceptions\CreateAccountFailedException;
use Contexts\Account\Domain\Commands\CreateAccount;
use Contexts\Account\Domain\Entities\Account;
use Contexts\Account\Domain\Events\AccountCreated;
use Contexts\Account\Domain\ValueObjects\Email;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CreateAccountTest extends TestCase
{
    use WithFaker;

    private CommandBus $commandBus;

    protected function setUp(): void
    {
        parent::setUp();

        $this->commandBus = app(CommandBus::class);
    }

    public function test_it_should_create_account(): void
    {
        Event::fake();

        $command = new CreateAccount(
            name: $this->faker->firstName,
            email: new Email($this->faker->email),
            password: $this->faker->password,
        );

        $this->commandBus->dispatch($command);

        Event::assertDispatched(AccountCreated::class);

        $this->assertDatabaseHas('users', [
            'name' => $command->getName(),
            'email' => $command->getEmail()->getValue(),
        ]);
    }

    public function test_it_should_throw_email_is_not_unique_on_create_account(): void
    {
        Event::fake();

        $command = new CreateAccount(
            name: $this->faker->firstName,
            email: new Email($this->faker->email),
            password: $this->faker->password,
        );

        entity(Account::class)->create([
            'email' => $command->getEmail(),
        ]);

        $this->expectException(CreateAccountFailedException::class);
        $this->expectExceptionMessage(__('accounts.exceptions.create_account_failed', [
            'reason' => __('accounts.exceptions.not_unique_email'),
        ]));

        $this->commandBus->dispatch($command);

        Event::assertNothingDispatched();
    }
}
