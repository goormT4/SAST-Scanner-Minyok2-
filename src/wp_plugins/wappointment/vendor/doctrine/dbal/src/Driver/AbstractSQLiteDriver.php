<?php

namespace WappoVendor\Doctrine\DBAL\Driver;

use WappoVendor\Doctrine\DBAL\Connection;
use WappoVendor\Doctrine\DBAL\Driver;
use WappoVendor\Doctrine\DBAL\Driver\API\ExceptionConverter;
use WappoVendor\Doctrine\DBAL\Driver\API\SQLite;
use WappoVendor\Doctrine\DBAL\Platforms\AbstractPlatform;
use WappoVendor\Doctrine\DBAL\Platforms\SqlitePlatform;
use WappoVendor\Doctrine\DBAL\Schema\SqliteSchemaManager;
use function assert;
/**
 * Abstract base implementation of the {@see Doctrine\DBAL\Driver} interface for SQLite based drivers.
 */
abstract class AbstractSQLiteDriver implements Driver
{
    /**
     * {@inheritdoc}
     */
    public function getDatabasePlatform()
    {
        return new SqlitePlatform();
    }
    /**
     * {@inheritdoc}
     */
    public function getSchemaManager(Connection $conn, AbstractPlatform $platform)
    {
        assert($platform instanceof SqlitePlatform);
        return new SqliteSchemaManager($conn, $platform);
    }
    public function getExceptionConverter() : ExceptionConverter
    {
        return new SQLite\ExceptionConverter();
    }
}
