<?php

declare(strict_types=1);

namespace Contexts\OneTimePassword\Infrastructure\Doctrine\Types;

use Contexts\OneTimePassword\Domain\ValueObjects\OneTimePasswordId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class DoctrineOneTimePasswordId extends GuidType
{
    private const TYPE_NAME = 'OneTimePasswordId';

    public function getName(): string
    {
        return self::TYPE_NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): OneTimePasswordId
    {
        return new OneTimePasswordId($value);
    }
}
