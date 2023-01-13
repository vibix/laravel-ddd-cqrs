<?php

declare(strict_types=1);

namespace Tests\Feature\UI\Api\Controllers\v1\Accounts;

use Contexts\Account\Domain\Entities\Account;
use Contexts\Account\Domain\ValueObjects\Email;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use WithFaker;


    public function test_it_should_login_successful(): void
    {
        $email = $this->faker->email;
        $password = 'Password!23';

        entity(Account::class)->create([
            'email' => new Email($email),
            'password' => Hash::make($password),
        ]);

        $response = $this->postJson('/api/v1/accounts/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $response->assertSuccessful();

        self::assertArrayHasKey('token', $response->json('data'));
    }

    public function test_it_should_throw_account_not_exists_error_on_login(): void
    {
        $email = $this->faker->email;
        $password = 'Password!23';

        $response = $this->postJson('/api/v1/accounts/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_it_should_throw_wrong_password_error_on_login(): void
    {
        $email = $this->faker->email;
        $password = 'Password!23';

        entity(Account::class)->create([
            'email' => new Email($email),
            'password' => $password, // not hashed
        ]);

        $response = $this->postJson('/api/v1/accounts/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @dataProvider invalidLoginCases
     */
    public function test_it_should_throw_validation_errors_on_login(array $invalidData, array $invalidFields): void
    {
        $response = $this->postJson('/api/v1/accounts/login', $invalidData);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors($invalidFields);
    }

    protected function invalidLoginCases(): array
    {
        return [
            [
                [],
                ['email', 'password'],
            ],
            [
                ['email' => '', 'password' => ''],
                ['email', 'password'],
            ],
            [
                ['email' => false, 'password' => null],
                ['email', 'password'],
            ],
            [
                ['email' => 'example@email@address.com', 'password' => 'Pass1'],
                ['email'],
            ],
        ];
    }
}
