<?php

declare (strict_types=1);
namespace WappoVendor\Doctrine\DBAL\Driver\Mysqli\Exception;

use WappoVendor\Doctrine\DBAL\Driver\AbstractException;
/**
 * @internal
 *
 * @psalm-immutable
 */
final class HostRequired extends AbstractException
{
    public static function forPersistentConnection() : self
    {
        return new self('The "host" parameter is required for a persistent connection');
    }
}
