<?php

declare(strict_types=1);

namespace UI\Api\Controllers\v1\Accounts;

use App\Services\CommandBus\CommandBus;
use App\Services\CommandBus\Middlewares\UseDatabaseTransactions;
use Contexts\Account\Domain\Commands\CreateAccount;
use Contexts\Account\Domain\ValueObjects\Email;
use Illuminate\Routing\Controller;
use UI\Api\Requests\Account\RegisterRequest;
use UI\Api\Responses\Account\RegisterResponse;

final class RegisterController extends Controller
{
    public function __construct(private readonly CommandBus $commandBus)
    {
    }

    public function __invoke(RegisterRequest $request): RegisterResponse
    {
        $this->commandBus->dispatch(
            new CreateAccount(
                name: $request->input('name'),
                email: new Email($request->input('email')),
                password: $request->input('password')
            ),
            [UseDatabaseTransactions::class]
        );

        return new RegisterResponse();
    }
}
