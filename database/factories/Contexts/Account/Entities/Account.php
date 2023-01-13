<?php

declare(strict_types=1);

use Contexts\Account\Domain\Entities\Account;
use Contexts\Account\Domain\ValueObjects\AccountId;
use Contexts\Account\Domain\ValueObjects\Email;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use LaravelDoctrine\ORM\Testing\Factory;

/** @var Factory $factory */

$factory->define(Account::class, function(Faker\Generator $faker) {
    return [
        'accountId' => new AccountId(Str::uuid()->toString()),
        'name' => $faker->name,
        'email' => new Email($faker->email),
        'password' => Hash::make('password'),
    ];
});
