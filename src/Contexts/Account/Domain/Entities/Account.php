<?php

declare(strict_types=1);

namespace Contexts\Account\Domain\Entities;

use Contexts\Account\Domain\Events\EmailVerified;
use Contexts\Account\Domain\Events\AccountCreated;
use Contexts\Account\Domain\Exceptions\EmailAlreadyVerifiedException;
use Contexts\Account\Domain\ValueObjects\AccountId;
use Contexts\Account\Domain\ValueObjects\Email;
use Contexts\Shared\Aggregate\AggregateRoot;
use DateTime;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

final class Account extends AggregateRoot
{
    private ?DateTime $emailVerifiedAt;
    private DateTime $updatedAt;
    private readonly DateTime $createdAt;

    public function __construct(
        private readonly AccountId $accountId,
        private readonly string $name,
        private readonly Email $email,
        private readonly string $password
    ) {
    }

    /**
     * @throws EmailAlreadyVerifiedException
     */
    public function verifyEmail(): void
    {
        if ($this->emailVerifiedAt) {
            throw new EmailAlreadyVerifiedException();
        }

        $this->emailVerifiedAt = new DateTime();
        $this->recordDomainEvent(new EmailVerified($this->accountId, $this->email));
    }

    public static function register(AccountId $accountId, string $name, Email $email, string $password): self
    {
        $account = new self($accountId, $name, $email, Hash::make($password));

        $account->recordDomainEvent(new AccountCreated($accountId, $email));

        return $account;
    }
}
