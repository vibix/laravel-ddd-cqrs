<?php

declare(strict_types=1);

namespace App\Services\CommandBus\Middlewares;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Psr\Log\LoggerInterface;
use Throwable;

final class LogCommand
{
    private readonly LoggerInterface $logger;

    public function __construct()
    {
        $this->logger = Log::channel('commandsLog');
    }

    /**
     * @throws Throwable
     */
    public function handle($command, $next)
    {
        $uuid = Str::uuid()->toString();
        $this->logger->debug(sprintf('[%s] Start command handle: %s', $uuid, $command::class));

        try {
            $result = $next($command);
            $this->logger->debug(sprintf('[%s] Command handled', $uuid));
        } catch (Throwable $throwable) {
            $this->logger->debug(sprintf('[%s] Command not handled: %s', $uuid, $throwable->getMessage()));
            throw $throwable;
        }

        return $result;
    }
}
