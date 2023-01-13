<?php

declare(strict_types=1);

namespace Contexts\Account\Domain\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class EmailAlreadyVerifiedException extends Exception
{
    public function __construct()
    {
        parent::__construct('Email already verified', Response::HTTP_BAD_REQUEST);
    }
}
