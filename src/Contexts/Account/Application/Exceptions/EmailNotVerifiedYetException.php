<?php

declare(strict_types=1);

namespace Contexts\Account\Application\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class EmailNotVerifiedYetException extends Exception
{
    public function __construct()
    {
        parent::__construct(__('accounts.exceptions.email_not_verified_yet'), Response::HTTP_FORBIDDEN);
    }
}
