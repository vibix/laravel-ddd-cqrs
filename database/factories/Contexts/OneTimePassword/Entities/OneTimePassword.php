<?php

declare(strict_types=1);

use Contexts\OneTimePassword\Domain\Entities\OneTimePassword;
use Contexts\OneTimePassword\Domain\ValueObjects\OneTimePasswordId;
use Illuminate\Support\Str;
use LaravelDoctrine\ORM\Testing\Factory;

/** @var Factory $factory */

$factory->define(OneTimePassword::class, function (Faker\Generator $faker) {
    return [
        'oneTimePasswordId' => new OneTimePasswordId(Str::uuid()->toString()),
        'code' => $faker->numerify('####'),
        'attempts' => 0,
        'maxAttempts' => 3,
        'expirationDate' => new DateTime('+15 minutes'),
        'confirmedAt' => null,
        'deletedAt' => null,
    ];
});
