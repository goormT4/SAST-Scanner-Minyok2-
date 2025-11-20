<?php

declare (strict_types=1);
namespace WappoVendor\Doctrine\DBAL\Driver\Mysqli\Exception;

use WappoVendor\Doctrine\DBAL\Driver\AbstractException;
use function sprintf;
/**
 * @internal
 *
 * @psalm-immutable
 */
final class InvalidOption extends AbstractException
{
    /**
     * @param mixed $value
     */
    public static function fromOption(int $option, $value) : self
    {
        return new self(sprintf('Failed to set option %d with value "%s"', $option, $value));
    }
}
