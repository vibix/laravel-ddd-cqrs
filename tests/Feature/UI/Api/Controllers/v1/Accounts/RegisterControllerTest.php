<?php

declare(strict_types=1);

namespace Tests\Feature\UI\Api\Controllers\v1\Accounts;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use WithFaker;

    public function test_it_should_register_successful(): void
    {
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => 'Password!23',
        ];

        $response = $this->postJson('/api/v1/accounts/register', $data);

        $response->assertCreated();

        $this->assertDatabaseHas('users', [
            'name' => $data['name'],
            'email' => $data['email'],
        ]);
    }

    /**
     * @dataProvider invalidRegisterCases
     */
    public function test_it_should_throw_validation_errors_on_register(array $invalidData, array $invalidFields): void
    {
        $response = $this->postJson('/api/v1/accounts/register', $invalidData);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors($invalidFields);
    }

    protected function invalidRegisterCases(): array
    {
        return [
            [
                [],
                ['name', 'email', 'password'],
            ],
            [
                ['name' => '', 'email' => '', 'password' => ''],
                ['name', 'email', 'password'],
            ],
            [
                ['name' => true, 'email' => false, 'password' => null],
                ['name', 'email', 'password'],
            ],
            [
                ['name' => 'John', 'email' => 'example@email@address.com', 'password' => 'Pass1'],
                ['email', 'password'],
            ],
            [
                ['name' => 'John', 'email' => 'example@address.com', 'password' => 'password'],
                ['password'],
            ],
            [
                ['name' => 'John', 'email' => 'example@address.com', 'password' => 'Password'],
                ['password'],
            ],
            [
                ['name' => 'John', 'email' => 'example@address.com', 'password' => 'Password1'],
                ['password'],
            ],
        ];
    }
}
