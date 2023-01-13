<?php

declare(strict_types=1);

namespace Contexts\Account\Application\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class EmailAlreadyVerifiedException extends Exception
{
    public function __construct()
    {
        parent::__construct(__('accounts.exceptions.email_already_verified'), Response::HTTP_BAD_REQUEST);
    }
}
