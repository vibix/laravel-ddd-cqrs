<?php

declare(strict_types=1);

namespace Contexts\Account\Application\Exceptions;

use Exception;

class NotUniqueEmailException extends Exception
{
    public function __construct()
    {
        parent::__construct(__('accounts.exceptions.not_unique_email'));
    }
}
