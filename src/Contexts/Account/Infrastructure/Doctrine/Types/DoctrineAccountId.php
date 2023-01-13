<?php

declare(strict_types=1);

namespace Contexts\Account\Infrastructure\Doctrine\Types;

use Contexts\Account\Domain\ValueObjects\AccountId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class DoctrineAccountId extends GuidType
{
    private const TYPE_NAME = 'AccountId';

    public function getName(): string
    {
        return self::TYPE_NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): AccountId
    {
        return new AccountId($value);
    }
}
