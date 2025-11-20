<?php

namespace WappoVendor\Doctrine\Common\Cache\Psr6;

use InvalidArgumentException;
use WappoVendor\Psr\Cache\InvalidArgumentException as PsrInvalidArgumentException;
/**
 * @internal
 */
final class InvalidArgument extends InvalidArgumentException implements PsrInvalidArgumentException
{
}
