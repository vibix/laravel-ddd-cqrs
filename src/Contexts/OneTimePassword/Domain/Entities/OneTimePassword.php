<?php

declare(strict_types=1);

namespace Contexts\OneTimePassword\Domain\Entities;

use Contexts\OneTimePassword\Domain\Enums\OneTimePasswordType;
use Contexts\OneTimePassword\Domain\Events\OneTimePasswordConfirmed;
use Contexts\OneTimePassword\Domain\Events\OneTimePasswordGenerated;
use Contexts\OneTimePassword\Domain\Events\ReachedOneTimePasswordAttemptsLimit;
use Contexts\OneTimePassword\Domain\Exceptions\NotConfirmableOneTimePasswordException;
use Contexts\OneTimePassword\Domain\Exceptions\ReachedOneTimePasswordAttemptsLimitException;
use Contexts\OneTimePassword\Domain\Exceptions\WrongOneTimePasswordException;
use Contexts\OneTimePassword\Domain\ValueObjects\OneTimePasswordId;
use Contexts\OneTimePassword\Domain\ValueObjects\SubjectId;
use Contexts\Shared\Aggregate\AggregateRoot;
use DateTime;
use Illuminate\Support\Str;

final class OneTimePassword extends AggregateRoot
{
    private int $attempts = 0;
    private ?DateTime $confirmedAt;
    private readonly ?DateTime $deletedAt;
    private readonly DateTime $createdAt;
    private DateTime $updatedAt;

    public function __construct(
        private readonly OneTimePasswordId $oneTimePasswordId,
        private readonly OneTimePasswordType $type,
        private readonly SubjectId $subjectId,
        private readonly string $code,
        private readonly int $maxAttempts,
        private readonly DateTime $expirationDate
    ) {
    }

    /**
     * @throws NotConfirmableOneTimePasswordException
     * @throws ReachedOneTimePasswordAttemptsLimitException
     * @throws WrongOneTimePasswordException
     */
    public function confirm(string $code): void
    {
        $this->attempt();

        if (!$this->isConfirmable()) {
            throw new NotConfirmableOneTimePasswordException();
        }

        if ($this->code !== $code) {
            throw new WrongOneTimePasswordException();
        }

        $this->confirmedAt = new DateTime();
        $this->recordDomainEvent(new OneTimePasswordConfirmed($this->type, $this->subjectId));
    }

    /**
     * @throws ReachedOneTimePasswordAttemptsLimitException
     */
    private function attempt(): void
    {
        if ($this->attempts >= $this->maxAttempts) {
            $this->recordDomainEvent(new ReachedOneTimePasswordAttemptsLimit($this->type, $this->subjectId));
            throw new ReachedOneTimePasswordAttemptsLimitException();
        }

        ++$this->attempts;
    }

    private function isConfirmable(): bool
    {
        return $this->attempts <= $this->maxAttempts
            && null === $this->deletedAt
            && null === $this->confirmedAt
            && !$this->isExpired();
    }

    private function isExpired(): bool
    {
        return new DateTime() > $this->expirationDate;
    }

    public static function generate(
        OneTimePasswordType $type,
        SubjectId $subjectId,
        string $code,
        int $maxAttempts,
        DateTime $expirationDate
    ): self {
        $otpId = new OneTimePasswordId(Str::uuid()->toString());
        $otp = new self($otpId, $type, $subjectId, $code, $maxAttempts, $expirationDate);

        $otp->recordDomainEvent(new OneTimePasswordGenerated($type, $subjectId, $code));

        return $otp;
    }
}
