<?php

declare(strict_types=1);

namespace App\Services\CommandBus;

interface CommandBus
{
    public function dispatch(object $command, array $middlewares = []): void;

    public function map(array $map): void;

    public function middleware(string $middleware): void;
}
