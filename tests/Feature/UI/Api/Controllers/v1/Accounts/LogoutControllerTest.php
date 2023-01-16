<?php

declare(strict_types=1);

namespace Tests\Feature\UI\Api\Controllers\v1\Accounts;

use App\Services\Authentication\Entities\AuthUser;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogoutControllerTest extends TestCase
{
    use WithFaker;

    public function test_it_should_logout_successful(): void
    {
        /** @var AuthUser $account */
        $account = entity(AuthUser::class)->create();
        $token = $account->createToken('API Account token')->accessToken;

        $response = $this
            ->postJson('/api/v1/accounts/logout', [], ['Authorization' => sprintf('Bearer %s', $token)]);

        $response->assertSuccessful();
        self::assertTrue($account->token()->getAttribute('revoked'));
    }

    public function test_it_should_throw_unauthorized_error_on_logout_not_logged_account(): void
    {
        $account = entity(AuthUser::class)->create();

        $response = $this->actingAs($account)->postJson('/api/v1/accounts/logout');

        $response->assertUnauthorized();
    }
}
