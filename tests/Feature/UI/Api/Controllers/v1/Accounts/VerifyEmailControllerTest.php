<?php

declare(strict_types=1);

namespace Tests\Feature\UI\Api\Controllers\v1\Accounts;

use Contexts\Account\Domain\Entities\Account;
use Contexts\Account\Domain\ValueObjects\AccountId;
use Contexts\OneTimePassword\Application\Enums\OneTimePasswordType;
use Contexts\OneTimePassword\Domain\Entities\OneTimePassword;
use Contexts\OneTimePassword\Domain\ValueObjects\SubjectId;
use DateTime;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class VerifyEmailControllerTest extends TestCase
{
    use WithFaker;

    public function test_it_should_verify_email_successful(): void
    {
        $accountId = new AccountId(Str::uuid()->toString());

        entity(Account::class)->create([
            'accountId' => $accountId,
            'emailVerifiedAt' => null,
        ]);

        $type = OneTimePasswordType::ACCOUNT_EMAIL_VERIFICATION;
        $subjectId = new SubjectId($accountId->getValue());
        $code = $this->faker->numerify('####');

        entity(OneTimePassword::class)->create([
            'type' => $type,
            'subjectId' => $subjectId,
            'code' => $code,
        ]);

        $response = $this->postJson(sprintf('/api/v1/accounts/verify-email/%s', $accountId), [
            'code' => $code,
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'id' => $accountId,
            'email_verified_at' => new DateTime(),
        ]);
    }

    public function test_it_should_throw_email_already_verified_error_on_email_verification(): void
    {
        $accountId = new AccountId(Str::uuid()->toString());

        entity(Account::class)->create([
            'accountId' => $accountId,
            'emailVerifiedAt' => new DateTime(),
        ]);

        $type = OneTimePasswordType::ACCOUNT_EMAIL_VERIFICATION;
        $subjectId = new SubjectId(Str::uuid()->toString());
        $code = $this->faker->numerify('####');

        entity(OneTimePassword::class)->create([
            'type' => $type,
            'subjectId' => $subjectId,
            'code' => $code,
        ]);

        $response = $this->postJson(sprintf('/api/v1/accounts/verify-email/%s', $accountId), [
            'code' => $code,
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function test_it_should_throw_wrong_code_error_on_email_verification(): void
    {
        $accountId = new AccountId(Str::uuid()->toString());

        entity(Account::class)->create([
            'accountId' => $accountId,
            'emailVerifiedAt' => null,
        ]);

        $type = OneTimePasswordType::ACCOUNT_EMAIL_VERIFICATION;
        $subjectId = new SubjectId(Str::uuid()->toString());
        $code = '1111';

        entity(OneTimePassword::class)->create([
            'type' => $type,
            'subjectId' => $subjectId,
            'code' => $code,
        ]);

        $response = $this->postJson(sprintf('/api/v1/accounts/verify-email/%s', $accountId), [
            'code' => '0000',
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }
}
