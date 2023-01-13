<?php

declare(strict_types=1);

namespace Contexts\OneTimePassword\Domain\Events;

use Contexts\OneTimePassword\Domain\Enums\OneTimePasswordType;
use Contexts\OneTimePassword\Domain\ValueObjects\SubjectId;
use Contexts\Shared\Events\DomainEventInterface;

final class ReachedOneTimePasswordAttemptsLimit implements DomainEventInterface
{
    public function __construct(
        private readonly OneTimePasswordType $type,
        private readonly SubjectId $subjectId
    ) {
    }

    public function getType(): OneTimePasswordType
    {
        return $this->type;
    }

    public function getSubjectId(): SubjectId
    {
        return $this->subjectId;
    }
}
