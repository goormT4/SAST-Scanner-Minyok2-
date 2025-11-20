<?php

namespace WappoVendor\Doctrine\DBAL\Driver;

use WappoVendor\Doctrine\DBAL\Connection;
use WappoVendor\Doctrine\DBAL\Driver;
use WappoVendor\Doctrine\DBAL\Driver\API\ExceptionConverter as ExceptionConverterInterface;
use WappoVendor\Doctrine\DBAL\Driver\API\SQLSrv\ExceptionConverter;
use WappoVendor\Doctrine\DBAL\Platforms\AbstractPlatform;
use WappoVendor\Doctrine\DBAL\Platforms\SQLServer2012Platform;
use WappoVendor\Doctrine\DBAL\Schema\SQLServerSchemaManager;
use function assert;
/**
 * Abstract base implementation of the {@see Driver} interface for Microsoft SQL Server based drivers.
 */
abstract class AbstractSQLServerDriver implements Driver
{
    /**
     * {@inheritdoc}
     */
    public function getDatabasePlatform()
    {
        return new SQLServer2012Platform();
    }
    /**
     * {@inheritdoc}
     */
    public function getSchemaManager(Connection $conn, AbstractPlatform $platform)
    {
        assert($platform instanceof SQLServer2012Platform);
        return new SQLServerSchemaManager($conn, $platform);
    }
    public function getExceptionConverter() : ExceptionConverterInterface
    {
        return new ExceptionConverter();
    }
}
