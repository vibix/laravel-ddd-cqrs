<?php

declare(strict_types=1);

namespace Contexts\OneTimePassword\Domain\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class WrongOneTimePasswordException extends Exception
{
    public function __construct()
    {
        parent::__construct('Wrong OTP', Response::HTTP_BAD_REQUEST);
    }
}
