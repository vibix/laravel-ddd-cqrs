<?php

declare(strict_types=1);

namespace Contexts\Account\Application\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class AccountNotExistsException extends Exception
{
    public function __construct()
    {
        parent::__construct(__('accounts.exceptions.not_exists'), Response::HTTP_NOT_FOUND);
    }
}
