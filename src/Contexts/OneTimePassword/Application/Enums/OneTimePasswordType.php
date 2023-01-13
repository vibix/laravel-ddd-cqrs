<?php

declare(strict_types=1);

namespace Contexts\OneTimePassword\Application\Enums;

use Contexts\OneTimePassword\Domain\Enums\OneTimePasswordType as OneTimePasswordTypeInterface;

enum OneTimePasswordType: string implements OneTimePasswordTypeInterface
{
    case ACCOUNT_EMAIL_VERIFICATION = 'account_email_verification';

    public function getMaxAttempts(): int
    {
        return match ($this) {
            self::ACCOUNT_EMAIL_VERIFICATION => 1,
            default => 3,
        };
    }
}
