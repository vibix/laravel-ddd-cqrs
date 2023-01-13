<?php

declare(strict_types=1);

namespace Contexts\OneTimePassword\Application\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class NotConfirmableOneTimePasswordException extends Exception
{
    public function __construct()
    {
        parent::__construct(__('otp.exceptions.not_confirmable'), Response::HTTP_BAD_REQUEST);
    }
}
