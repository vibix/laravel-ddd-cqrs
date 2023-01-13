<?php

declare(strict_types=1);

namespace Contexts\OneTimePassword\Domain\Enums;

interface OneTimePasswordType
{
    public function getMaxAttempts(): int;
}
