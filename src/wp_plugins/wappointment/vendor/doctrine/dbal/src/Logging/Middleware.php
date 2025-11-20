<?php

declare (strict_types=1);
namespace WappoVendor\Doctrine\DBAL\Logging;

use WappoVendor\Doctrine\DBAL\Driver as DriverInterface;
use WappoVendor\Doctrine\DBAL\Driver\Middleware as MiddlewareInterface;
use WappoVendor\Psr\Log\LoggerInterface;
final class Middleware implements MiddlewareInterface
{
    /** @var LoggerInterface */
    private $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    public function wrap(DriverInterface $driver) : DriverInterface
    {
        return new Driver($driver, $this->logger);
    }
}
