<?php

declare (strict_types=1);
namespace WappoVendor\Doctrine\DBAL\Portability;

use WappoVendor\Doctrine\DBAL\Platforms\AbstractPlatform;
use WappoVendor\Doctrine\DBAL\Platforms\DB2Platform;
use WappoVendor\Doctrine\DBAL\Platforms\OraclePlatform;
use WappoVendor\Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use WappoVendor\Doctrine\DBAL\Platforms\SqlitePlatform;
use WappoVendor\Doctrine\DBAL\Platforms\SQLServerPlatform;
final class OptimizeFlags
{
    /**
     * Platform-specific portability flags that need to be excluded from the user-provided mode
     * since the platform already operates in this mode to avoid unnecessary conversion overhead.
     *
     * @var array<string,int>
     */
    private static $platforms = [DB2Platform::class => 0, OraclePlatform::class => Connection::PORTABILITY_EMPTY_TO_NULL, PostgreSQLPlatform::class => 0, SqlitePlatform::class => 0, SQLServerPlatform::class => 0];
    public function __invoke(AbstractPlatform $platform, int $flags) : int
    {
        foreach (self::$platforms as $class => $mask) {
            if ($platform instanceof $class) {
                $flags &= ~$mask;
                break;
            }
        }
        return $flags;
    }
}
