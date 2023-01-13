<?php

declare(strict_types=1);

namespace Contexts\Account\Infrastructure\Doctrine\Types;

use Contexts\Account\Domain\ValueObjects\Email;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class DoctrineEmail extends GuidType
{
    public function getName(): string
    {
        return 'Email';
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->email;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): Email
    {
        return new Email($value);
    }
}
