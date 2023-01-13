<?php

declare(strict_types=1);

namespace App\Services\CommandBus;

use Illuminate\Bus\Dispatcher;

class IlluminateCommandBus implements CommandBus
{
    private readonly Dispatcher $bus;
    private array $middlewares = [];

    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }

    public function dispatch(object $command, array $middlewares = []): void
    {
        $this->bus->pipeThrough(array_merge($this->middlewares, $middlewares));
        $this->bus->dispatch($command);
        $this->bus->pipeThrough($this->middlewares);
    }

    public function map(array $map): void
    {
        $this->bus->map($map);
    }

    public function middleware(string $middleware): void
    {
        $this->middlewares[$middleware] = $middleware;
    }
}
