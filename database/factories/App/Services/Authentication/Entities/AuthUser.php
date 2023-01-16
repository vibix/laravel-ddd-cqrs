<?php

declare(strict_types=1);

use App\Services\Authentication\Entities\AuthUser;
use App\Services\Authentication\ValueObjects\AccountId;
use App\Services\Authentication\ValueObjects\Email;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use LaravelDoctrine\ORM\Testing\Factory;

/** @var Factory $factory */

$factory->define(AuthUser::class, function (Faker\Generator $faker) {
    return [
        'accountId' => new AccountId(Str::uuid()->toString()),
        'name' => $faker->name,
        'email' => new Email($faker->email),
        'password' => Hash::make('password'),
        'emailVerifiedAt' => new DateTime(),
    ];
});
