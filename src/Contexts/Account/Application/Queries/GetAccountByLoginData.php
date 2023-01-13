<?php

declare(strict_types=1);

namespace Contexts\Account\Application\Queries;

use Contexts\Account\Application\Exceptions\AccountNotExistsException;
use Contexts\Account\Application\Exceptions\EmailNotVerifiedYetException;
use Contexts\Account\Application\Exceptions\WrongPasswordException;
use Contexts\Account\Application\Models\AccountView;
use Contexts\Account\Application\Repositories\AccountViewRepository;
use Contexts\Account\Domain\ValueObjects\Email;
use Illuminate\Support\Facades\Hash;

final class GetAccountByLoginData
{
    public function __construct(private readonly AccountViewRepository $repository)
    {
    }

    /**
     * @throws AccountNotExistsException
     * @throws WrongPasswordException
     * @throws EmailNotVerifiedYetException
     */
    public function __invoke(Email $email, string $password): AccountView
    {
        $account = $this->repository->findByEmail($email);
        if (!$account) {
            throw new AccountNotExistsException();
        }

        if (!$account->getEmailVerifiedAt()) {
            throw new EmailNotVerifiedYetException();
        }

        if (!Hash::check($password, $account->getPassword())) {
            throw new WrongPasswordException();
        }

        return $account;
    }
}
