<?php

declare(strict_types=1);

namespace App\Providers;

use Contexts\Account\Application\Repositories\AccountViewRepository;
use Contexts\Account\Domain\Repositories\AccountRepository;
use Contexts\Account\Infrastructure\Repositories\DoctrineAccountRepository;
use Contexts\Account\Infrastructure\Repositories\DoctrineAccountViewRepository;
use Contexts\OneTimePassword\Domain\Repositories\OneTimePasswordRepository;
use Contexts\OneTimePassword\Infrastructure\Repositories\DoctrineOneTimePasswordRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    private const REPOSITORIES = [
        AccountRepository::class => DoctrineAccountRepository::class,
        AccountViewRepository::class => DoctrineAccountViewRepository::class,
        OneTimePasswordRepository::class => DoctrineOneTimePasswordRepository::class,
    ];

    public function register(): void
    {
        foreach (self::REPOSITORIES as $interface => $repository) {
            $this->app->bind($interface, $repository);
        }
    }
}
