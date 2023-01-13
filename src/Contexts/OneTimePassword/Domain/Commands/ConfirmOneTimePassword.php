<?php

declare(strict_types=1);

namespace Contexts\OneTimePassword\Domain\Commands;

use Contexts\OneTimePassword\Domain\Enums\OneTimePasswordType;
use Contexts\OneTimePassword\Domain\ValueObjects\SubjectId;

final class ConfirmOneTimePassword
{
    public function __construct(
        private readonly OneTimePasswordType $type,
        private readonly SubjectId $subjectId,
        private readonly string $code
    ) {
    }

    public function getCode(): string
    {
        return $this->code;
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
