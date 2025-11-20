<?php

namespace WappoVendor\Doctrine\DBAL\Driver\Middleware;

use WappoVendor\Doctrine\DBAL\Connection;
use WappoVendor\Doctrine\DBAL\Driver;
use WappoVendor\Doctrine\DBAL\Driver\API\ExceptionConverter;
use WappoVendor\Doctrine\DBAL\Platforms\AbstractPlatform;
use WappoVendor\Doctrine\DBAL\VersionAwarePlatformDriver;
abstract class AbstractDriverMiddleware implements VersionAwarePlatformDriver
{
    /** @var Driver */
    private $wrappedDriver;
    public function __construct(Driver $wrappedDriver)
    {
        $this->wrappedDriver = $wrappedDriver;
    }
    /**
     * {@inheritdoc}
     */
    public function connect(array $params)
    {
        return $this->wrappedDriver->connect($params);
    }
    /**
     * {@inheritdoc}
     */
    public function getDatabasePlatform()
    {
        return $this->wrappedDriver->getDatabasePlatform();
    }
    /**
     * {@inheritdoc}
     */
    public function getSchemaManager(Connection $conn, AbstractPlatform $platform)
    {
        return $this->wrappedDriver->getSchemaManager($conn, $platform);
    }
    public function getExceptionConverter() : ExceptionConverter
    {
        return $this->wrappedDriver->getExceptionConverter();
    }
    /**
     * {@inheritdoc}
     */
    public function createDatabasePlatformForVersion($version)
    {
        if ($this->wrappedDriver instanceof VersionAwarePlatformDriver) {
            return $this->wrappedDriver->createDatabasePlatformForVersion($version);
        }
        return $this->wrappedDriver->getDatabasePlatform();
    }
}
