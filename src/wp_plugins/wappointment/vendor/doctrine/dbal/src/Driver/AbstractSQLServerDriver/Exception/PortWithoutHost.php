<?php

declare (strict_types=1);
namespace WappoVendor\Doctrine\DBAL\Driver\AbstractSQLServerDriver\Exception;

use WappoVendor\Doctrine\DBAL\Driver\AbstractException;
/**
 * @internal
 *
 * @psalm-immutable
 */
final class PortWithoutHost extends AbstractException
{
    public static function new() : self
    {
        return new self('Connection port specified without the host');
    }
}
