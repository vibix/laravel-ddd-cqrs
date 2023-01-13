<?php

declare(strict_types=1);

namespace Contexts\OneTimePassword\Domain\Repositories;

use Contexts\OneTimePassword\Domain\Entities\OneTimePassword;
use Contexts\OneTimePassword\Domain\Enums\OneTimePasswordType;
use Contexts\OneTimePassword\Domain\ValueObjects\OneTimePasswordId;
use Contexts\OneTimePassword\Domain\ValueObjects\SubjectId;

interface OneTimePasswordRepository
{
    public function find(OneTimePasswordId $id): ?OneTimePassword;

    public function findByTypeAndSubject(OneTimePasswordType $type, SubjectId $subjectId): ?OneTimePassword;

    public function save(OneTimePassword $oneTimePassword): void;
}
