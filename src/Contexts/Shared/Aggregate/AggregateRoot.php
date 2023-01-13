<?php

declare(strict_types=1);

namespace Contexts\Shared\Aggregate;

use Contexts\Shared\Events\DomainEventInterface;

abstract class AggregateRoot
{
    protected array $domainEvents = [];

    protected function recordDomainEvent(DomainEventInterface $event): self
    {
        $this->domainEvents[] = $event;
        return $this;
    }

    public function pullDomainEvents(): array
    {
        $domainEvents = $this->domainEvents;
        $this->domainEvents = [];

        return $domainEvents;
    }
}
