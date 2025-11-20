<?php

declare (strict_types=1);
namespace WappoVendor\Doctrine\DBAL\Schema\Exception;

use WappoVendor\Doctrine\DBAL\Schema\SchemaException;
use function sprintf;
/**
 * @psalm-immutable
 */
final class InvalidTableName extends SchemaException
{
    public static function new(string $tableName) : self
    {
        return new self(sprintf('Invalid table name specified "%s".', $tableName));
    }
}
