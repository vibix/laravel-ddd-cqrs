<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\CommandBus\CommandBus;
use App\Services\CommandBus\IlluminateCommandBus;
use App\Services\CommandBus\Middlewares\LogCommand;
use Contexts\Account\Application\Commands\CreateAccountHandler;
use Contexts\Account\Application\Commands\VerifyEmailAddressHandler;
use Contexts\Account\Domain\Commands\CreateAccount;
use Contexts\Account\Domain\Commands\VerifyEmailAddress;
use Contexts\OneTimePassword\Application\Commands\ConfirmOneTimePasswordHandler;
use Contexts\OneTimePassword\Application\Commands\GenerateOneTimePasswordHandler;
use Contexts\OneTimePassword\Domain\Commands\GenerateOneTimePassword;
use Contexts\OneTimePassword\Domain\Commands\ConfirmOneTimePassword;
use Illuminate\Support\ServiceProvider;

class CommandBusServiceProvider extends ServiceProvider
{
    /**
     * Commands with their handlers
     */
    private const COMMANDS = [
        // Account
        CreateAccount::class => CreateAccountHandler::class,
        VerifyEmailAddress::class => VerifyEmailAddressHandler::class,

        // OTP
        GenerateOneTimePassword::class => GenerateOneTimePasswordHandler::class,
        ConfirmOneTimePassword::class => ConfirmOneTimePasswordHandler::class,
    ];

    /**
     * Every Command middlewares
     */
    private const MIDDLEWARES = [
        LogCommand::class,
    ];

    public function register(): void
    {
        $this->app->singleton(CommandBus::class, IlluminateCommandBus::class);
    }

    public function boot(): void
    {
        /** @var CommandBus $commandBus */
        $commandBus = app(CommandBus::class);
        $commandBus->map(self::COMMANDS);

        foreach (self::MIDDLEWARES as $middleware) {
            $commandBus->middleware($middleware);
        }
    }
}
