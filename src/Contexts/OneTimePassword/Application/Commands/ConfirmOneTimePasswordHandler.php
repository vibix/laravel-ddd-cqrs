<?php

declare(strict_types=1);

namespace Contexts\OneTimePassword\Application\Commands;

use Contexts\OneTimePassword\Application\Exceptions\NotConfirmableOneTimePasswordException;
use Contexts\OneTimePassword\Application\Exceptions\ReachedOneTimePasswordAttemptsLimitException;
use Contexts\OneTimePassword\Application\Exceptions\WrongOneTimePasswordException;
use Contexts\OneTimePassword\Domain\Commands\ConfirmOneTimePassword;
use Contexts\OneTimePassword\Domain\Exceptions\NotConfirmableOneTimePasswordException as NotConfirmableOneTimePasswordDomainException;
use Contexts\OneTimePassword\Domain\Exceptions\ReachedOneTimePasswordAttemptsLimitException as ReachedOneTimePasswordAttemptsLimitDomainException;
use Contexts\OneTimePassword\Domain\Exceptions\WrongOneTimePasswordException as WrongOneTimePasswordDomainException;
use Contexts\OneTimePassword\Domain\Repositories\OneTimePasswordRepository;
use Illuminate\Support\Facades\Event;

final class ConfirmOneTimePasswordHandler
{
    public function __construct(private readonly OneTimePasswordRepository $repository)
    {
    }

    /**
     * @throws WrongOneTimePasswordException
     * @throws NotConfirmableOneTimePasswordException
     * @throws ReachedOneTimePasswordAttemptsLimitException
     */
    public function handle(ConfirmOneTimePassword $command): void
    {
        $otp = $this->repository->findByTypeAndSubject($command->getType(), $command->getSubjectId());
        if (!$otp) {
            throw new WrongOneTimePasswordException();
        }

        try {
            $otp->confirm($command->getCode());
        } catch (ReachedOneTimePasswordAttemptsLimitDomainException) {
            throw new ReachedOneTimePasswordAttemptsLimitException();
        } catch (NotConfirmableOneTimePasswordDomainException) {
            throw new NotConfirmableOneTimePasswordException();
        } catch (WrongOneTimePasswordDomainException) {
            throw new WrongOneTimePasswordException();
        } finally {
            $this->repository->save($otp);

            foreach ($otp->pullDomainEvents() as $domainEvent) {
                Event::dispatch($domainEvent);
            }
        }
    }
}
