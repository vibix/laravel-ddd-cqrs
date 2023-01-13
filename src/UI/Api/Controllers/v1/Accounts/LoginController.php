<?php

declare(strict_types=1);

namespace UI\Api\Controllers\v1\Accounts;

use App\Services\Authentication\Entities\AuthUser;
use Contexts\Account\Application\Exceptions\AccountNotExistsException;
use Contexts\Account\Application\Exceptions\WrongPasswordException;
use Contexts\Account\Application\Queries\GetAccountByLoginData;
use Contexts\Account\Domain\ValueObjects\Email;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use UI\Api\Requests\Account\LoginRequest;
use UI\Api\Responses\Account\LoginResponse;

final class LoginController extends Controller
{
    /**
     * @throws WrongPasswordException
     * @throws AccountNotExistsException
     */
    public function __invoke(LoginRequest $request, GetAccountByLoginData $query): LoginResponse
    {
        $user = $query(
            email: new Email($request->input('email')),
            password: $request->input('password'),
        );

        /** @var AuthUser $loggedAccount */
        $loggedAccount = Auth::loginUsingId($user->getAccountId()->getValue());

        return new LoginResponse($loggedAccount->createToken('API Account token')->accessToken);
    }
}
