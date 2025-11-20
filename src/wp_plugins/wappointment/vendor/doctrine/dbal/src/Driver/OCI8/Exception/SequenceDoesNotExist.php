<?php

declare (strict_types=1);
namespace WappoVendor\Doctrine\DBAL\Driver\OCI8\Exception;

use WappoVendor\Doctrine\DBAL\Driver\AbstractException;
/**
 * @internal
 *
 * @psalm-immutable
 */
final class SequenceDoesNotExist extends AbstractException
{
    public static function new() : self
    {
        return new self('lastInsertId failed: Query was executed but no result was returned.');
    }
}
