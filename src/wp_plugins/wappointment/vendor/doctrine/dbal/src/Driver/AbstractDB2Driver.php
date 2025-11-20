<?php

namespace WappoVendor\Doctrine\DBAL\Driver;

use WappoVendor\Doctrine\DBAL\Connection;
use WappoVendor\Doctrine\DBAL\Driver;
use WappoVendor\Doctrine\DBAL\Driver\API\ExceptionConverter as ExceptionConverterInterface;
use WappoVendor\Doctrine\DBAL\Driver\API\IBMDB2\ExceptionConverter;
use WappoVendor\Doctrine\DBAL\Platforms\AbstractPlatform;
use WappoVendor\Doctrine\DBAL\Platforms\DB2Platform;
use WappoVendor\Doctrine\DBAL\Schema\DB2SchemaManager;
use function assert;
/**
 * Abstract base implementation of the {@see Driver} interface for IBM DB2 based drivers.
 */
abstract class AbstractDB2Driver implements Driver
{
    /**
     * {@inheritdoc}
     */
    public function getDatabasePlatform()
    {
        return new DB2Platform();
    }
    /**
     * {@inheritdoc}
     */
    public function getSchemaManager(Connection $conn, AbstractPlatform $platform)
    {
        assert($platform instanceof DB2Platform);
        return new DB2SchemaManager($conn, $platform);
    }
    public function getExceptionConverter() : ExceptionConverterInterface
    {
        return new ExceptionConverter();
    }
}
