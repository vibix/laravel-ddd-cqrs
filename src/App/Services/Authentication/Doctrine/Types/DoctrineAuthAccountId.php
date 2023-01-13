<?php

declare(strict_types=1);

namespace App\Services\Authentication\Doctrine\Types;

use App\Services\Authentication\ValueObjects\AccountId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class DoctrineAuthAccountId extends GuidType
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
