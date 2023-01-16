<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\OneTimePassword\Commands;

use App\Services\CommandBus\CommandBus;
use Contexts\Account\Application\Exceptions\AccountNotExistsException;
use Contexts\Account\Application\Exceptions\CreateAccountFailedException;
use Contexts\Account\Application\Exceptions\EmailAlreadyVerifiedException;
use Contexts\Account\Domain\Commands\CreateAccount;
use Contexts\Account\Domain\Commands\VerifyEmailAddress;
use Contexts\Account\Domain\Entities\Account;
use Contexts\Account\Domain\Events\AccountCreated;
use Contexts\Account\Domain\Events\EmailVerified;
use Contexts\Account\Domain\ValueObjects\AccountId;
use Contexts\Account\Domain\ValueObjects\Email;
use Contexts\OneTimePassword\Application\Enums\OneTimePasswordType;
use Contexts\OneTimePassword\Application\Exceptions\NotConfirmableOneTimePasswordException;
use Contexts\OneTimePassword\Application\Exceptions\ReachedOneTimePasswordAttemptsLimitException;
use Contexts\OneTimePassword\Application\Exceptions\WrongOneTimePasswordException;
use Contexts\OneTimePassword\Domain\Commands\ConfirmOneTimePassword;
use Contexts\OneTimePassword\Domain\Entities\OneTimePassword;
use Contexts\OneTimePassword\Domain\Events\OneTimePasswordConfirmed;
use Contexts\OneTimePassword\Domain\ValueObjects\OneTimePasswordId;
use Contexts\OneTimePassword\Domain\ValueObjects\SubjectId;
use DateTime;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Tests\TestCase;

class ConfirmOneTimePasswordTest extends TestCase
{
    use WithFaker;

    private CommandBus $commandBus;

    protected function setUp(): void
    {
        parent::setUp();

        $this->commandBus = app(CommandBus::class);
    }

    public function test_it_should_confirm_otp_successful(): void
    {
        Event::fake();

        $type = OneTimePasswordType::ACCOUNT_EMAIL_VERIFICATION;
        $subjectId = new SubjectId(Str::uuid()->toString());
        $code = $this->faker->numerify('####');

        entity(OneTimePassword::class)->create([
            'type' => $type,
            'subjectId' => $subjectId,
            'code' => $code,
        ]);

        $command = new ConfirmOneTimePassword($type, $subjectId, $code);

        $this->commandBus->dispatch($command);

        Event::assertDispatched(OneTimePasswordConfirmed::class);

        $this->assertDatabaseHas('one_time_passwords', [
            'type' => $command->getType(),
            'subject_id' => $command->getSubjectId()->getValue(),
            'confirmed_at' => new DateTime(),
        ]);
    }

    public function test_it_should_throw_wrong_otp_error_on_not_exists_otp_confirmation(): void
    {
        Event::fake();

        $type = OneTimePasswordType::ACCOUNT_EMAIL_VERIFICATION;
        $subjectId = new SubjectId(Str::uuid()->toString());
        $code = $this->faker->numerify('####');

        $command = new ConfirmOneTimePassword($type, $subjectId, $code);

        $this->expectException(WrongOneTimePasswordException::class);
        $this->expectExceptionMessage(__('otp.exceptions.wrong'));

        $this->commandBus->dispatch($command);

        Event::assertNothingDispatched();
    }

    public function test_it_should_throw_reach_attempts_limit_on_otp_confirmation_attempt(): void
    {
        Event::fake();

        $type = OneTimePasswordType::ACCOUNT_EMAIL_VERIFICATION;
        $subjectId = new SubjectId(Str::uuid()->toString());
        $code = $this->faker->numerify('####');

        entity(OneTimePassword::class)->create([
            'type' => $type,
            'subjectId' => $subjectId,
            'code' => $code,
            'attempts' => 3,
            'maxAttempts' => 3,
        ]);

        $command = new ConfirmOneTimePassword($type, $subjectId, $code);

        $this->expectException(ReachedOneTimePasswordAttemptsLimitException::class);
        $this->expectExceptionMessage(__('otp.exceptions.reached_attempts_limit'));

        $this->commandBus->dispatch($command);

        Event::assertNothingDispatched();
    }

    public function test_it_should_throw_not_confirmable_otp_error_on_deleted_otp_confirmation(): void
    {
        Event::fake();

        $type = OneTimePasswordType::ACCOUNT_EMAIL_VERIFICATION;
        $subjectId = new SubjectId(Str::uuid()->toString());
        $code = $this->faker->numerify('####');

        entity(OneTimePassword::class)->create([
            'type' => $type,
            'subjectId' => $subjectId,
            'code' => $code,
            'deletedAt' => new DateTime(),
        ]);

        $command = new ConfirmOneTimePassword($type, $subjectId, $code);

        $this->expectException(NotConfirmableOneTimePasswordException::class);
        $this->expectExceptionMessage(__('otp.exceptions.not_confirmable'));

        $this->commandBus->dispatch($command);

        Event::assertNothingDispatched();
    }

    public function test_it_should_throw_not_confirmable_otp_error_on_already_confirmed_otp_confirmation(): void
    {
        Event::fake();

        $type = OneTimePasswordType::ACCOUNT_EMAIL_VERIFICATION;
        $subjectId = new SubjectId(Str::uuid()->toString());
        $code = $this->faker->numerify('####');

        entity(OneTimePassword::class)->create([
            'type' => $type,
            'subjectId' => $subjectId,
            'code' => $code,
            'confirmedAt' => new DateTime(),
        ]);

        $command = new ConfirmOneTimePassword($type, $subjectId, $code);

        $this->expectException(NotConfirmableOneTimePasswordException::class);
        $this->expectExceptionMessage(__('otp.exceptions.not_confirmable'));

        $this->commandBus->dispatch($command);

        Event::assertNothingDispatched();
    }

    public function test_it_should_throw_not_confirmable_otp_error_on_expired_otp_confirmation(): void
    {
        Event::fake();

        $type = OneTimePasswordType::ACCOUNT_EMAIL_VERIFICATION;
        $subjectId = new SubjectId(Str::uuid()->toString());
        $code = $this->faker->numerify('####');

        entity(OneTimePassword::class)->create([
            'type' => $type,
            'subjectId' => $subjectId,
            'code' => $code,
            'expirationDate' => new DateTime('-1 minute'),
        ]);

        $command = new ConfirmOneTimePassword($type, $subjectId, $code);

        $this->expectException(NotConfirmableOneTimePasswordException::class);
        $this->expectExceptionMessage(__('otp.exceptions.not_confirmable'));

        $this->commandBus->dispatch($command);

        Event::assertNothingDispatched();
    }

    public function test_it_should_throw_wrong_otp_error_on_otp_confirmation_with_wrong_code(): void
    {
        Event::fake();

        $type = OneTimePasswordType::ACCOUNT_EMAIL_VERIFICATION;
        $subjectId = new SubjectId(Str::uuid()->toString());
        $code = $this->faker->numerify('####');

        entity(OneTimePassword::class)->create([
            'type' => $type,
            'subjectId' => $subjectId,
            'code' => $code,
        ]);

        $command = new ConfirmOneTimePassword($type, $subjectId, '1234');

        $this->expectException(WrongOneTimePasswordException::class);
        $this->expectExceptionMessage(__('otp.exceptions.wrong'));

        $this->commandBus->dispatch($command);

        Event::assertNothingDispatched();
    }
}
