<?php

declare(strict_types=1);

namespace Contexts\OneTimePassword\Domain\Commands;

use Contexts\OneTimePassword\Domain\Enums\OneTimePasswordType;
use Contexts\OneTimePassword\Domain\ValueObjects\SubjectId;

final class GenerateOneTimePassword
{
    public function __construct(
        private readonly OneTimePasswordType $type,
        private readonly SubjectId $subjectId,
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
