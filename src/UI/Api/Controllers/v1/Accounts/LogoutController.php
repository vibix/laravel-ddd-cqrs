<?php

declare(strict_types=1);

namespace UI\Api\Controllers\v1\Accounts;

use Illuminate\Routing\Controller;
use UI\Api\Requests\Account\LogoutRequest;
use UI\Api\Responses\Account\LogoutResponse;

final class LogoutController extends Controller
{
    public function __invoke(LogoutRequest $request): LogoutResponse
    {
        $token = $request->user()->token();
        $token->revoke();
        $token->delete();

        return new LogoutResponse();
    }
}
