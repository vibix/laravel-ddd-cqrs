<?php

declare(strict_types=1);

namespace Contexts\Account\Application\Exceptions;

use Exception;

class CreateAccountFailedException extends Exception
{
    public function __construct(string $reason = null)
    {
        parent::__construct(__('accounts.exceptions.create_account_failed', ['reason' => $reason]));
    }
}
