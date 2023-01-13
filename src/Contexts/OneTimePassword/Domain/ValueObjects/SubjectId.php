<?php

declare(strict_types=1);

namespace Contexts\OneTimePassword\Domain\ValueObjects;

final class SubjectId
{
    public function __construct(protected string $value)
    {
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
