<?php

namespace WappoVendor\Doctrine\DBAL\Driver;

use WappoVendor\Doctrine\DBAL\Connection;
use WappoVendor\Doctrine\DBAL\Driver;
use WappoVendor\Doctrine\DBAL\Driver\AbstractOracleDriver\EasyConnectString;
use WappoVendor\Doctrine\DBAL\Driver\API\ExceptionConverter;
use WappoVendor\Doctrine\DBAL\Driver\API\OCI;
use WappoVendor\Doctrine\DBAL\Platforms\AbstractPlatform;
use WappoVendor\Doctrine\DBAL\Platforms\OraclePlatform;
use WappoVendor\Doctrine\DBAL\Schema\OracleSchemaManager;
use function assert;
/**
 * Abstract base implementation of the {@see Driver} interface for Oracle based drivers.
 */
abstract class AbstractOracleDriver implements Driver
{
    /**
     * {@inheritdoc}
     */
    public function getDatabasePlatform()
    {
        return new OraclePlatform();
    }
    /**
     * {@inheritdoc}
     */
    public function getSchemaManager(Connection $conn, AbstractPlatform $platform)
    {
        assert($platform instanceof OraclePlatform);
        return new OracleSchemaManager($conn, $platform);
    }
    public function getExceptionConverter() : ExceptionConverter
    {
        return new OCI\ExceptionConverter();
    }
    /**
     * Returns an appropriate Easy Connect String for the given parameters.
     *
     * @param mixed[] $params The connection parameters to return the Easy Connect String for.
     *
     * @return string
     */
    protected function getEasyConnectString(array $params)
    {
        return (string) EasyConnectString::fromConnectionParameters($params);
    }
}
