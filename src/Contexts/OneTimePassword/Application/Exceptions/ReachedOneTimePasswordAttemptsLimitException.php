<?php

declare(strict_types=1);

namespace Contexts\OneTimePassword\Application\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class ReachedOneTimePasswordAttemptsLimitException extends Exception
{
    public function __construct()
    {
        parent::__construct(__('otp.exceptions.reached_attempts_limit'), Response::HTTP_BAD_REQUEST);
    }
}
