<?php

declare(strict_types=1);

namespace UI\Api\Controllers\v1\Accounts;

use App\Services\CommandBus\CommandBus;
use App\Services\CommandBus\Middlewares\UseDatabaseTransactions;
use Contexts\OneTimePassword\Application\Enums\OneTimePasswordType;
use Contexts\OneTimePassword\Domain\Commands\ConfirmOneTimePassword;
use Contexts\OneTimePassword\Domain\ValueObjects\SubjectId;
use Illuminate\Routing\Controller;
use UI\Api\Requests\Account\VerifyEmailRequest;
use UI\Api\Responses\Account\RegisterResponse;

final class VerifyEmailController extends Controller
{
    public function __construct(private readonly CommandBus $commandBus)
    {
    }

    public function __invoke(VerifyEmailRequest $request, string $id): RegisterResponse
    {
        $this->commandBus->dispatch(
            new ConfirmOneTimePassword(
                type: OneTimePasswordType::ACCOUNT_EMAIL_VERIFICATION,
                subjectId: new SubjectId($id),
                code: $request->input('code')
            ),
            [UseDatabaseTransactions::class]
        );

        return new RegisterResponse();
    }
}
