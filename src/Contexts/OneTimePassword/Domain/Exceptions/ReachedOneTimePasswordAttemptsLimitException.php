<?php

declare(strict_types=1);

namespace Contexts\OneTimePassword\Domain\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class ReachedOneTimePasswordAttemptsLimitException extends Exception
{
    public function __construct()
    {
        parent::__construct('Reached OTP attempts limit', Response::HTTP_BAD_REQUEST);
    }
}
