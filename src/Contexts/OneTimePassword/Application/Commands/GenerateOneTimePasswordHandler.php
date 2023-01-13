<?php

declare(strict_types=1);

namespace Contexts\OneTimePassword\Application\Commands;

use Contexts\OneTimePassword\Domain\Commands\GenerateOneTimePassword;
use Contexts\OneTimePassword\Domain\Entities\OneTimePassword;
use Contexts\OneTimePassword\Domain\Repositories\OneTimePasswordRepository;
use DateTime;
use Faker\Factory;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;

final class GenerateOneTimePasswordHandler
{
    public function __construct(private readonly OneTimePasswordRepository $repository)
    {
    }

    public function handle(GenerateOneTimePassword $command): void
    {
        $faker = Factory::create();

        $otp = OneTimePassword::generate(
            type: $command->getType(),
            subjectId: $command->getSubjectId(),
            code: $faker->numerify('####'),
            maxAttempts: $command->getType()->getMaxAttempts(),
            expirationDate: new DateTime('+1 hour')
        );

        $this->repository->save($otp);

        foreach ($otp->pullDomainEvents() as $domainEvent) {
            Event::dispatch($domainEvent);
        }
    }
}
