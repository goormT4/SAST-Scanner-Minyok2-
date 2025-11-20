<?php

declare (strict_types=1);
namespace WappoVendor\Doctrine\DBAL\Driver\API\SQLSrv;

use WappoVendor\Doctrine\DBAL\Driver\API\ExceptionConverter as ExceptionConverterInterface;
use WappoVendor\Doctrine\DBAL\Driver\Exception;
use WappoVendor\Doctrine\DBAL\Exception\ConnectionException;
use WappoVendor\Doctrine\DBAL\Exception\DatabaseObjectNotFoundException;
use WappoVendor\Doctrine\DBAL\Exception\DriverException;
use WappoVendor\Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use WappoVendor\Doctrine\DBAL\Exception\InvalidFieldNameException;
use WappoVendor\Doctrine\DBAL\Exception\NonUniqueFieldNameException;
use WappoVendor\Doctrine\DBAL\Exception\NotNullConstraintViolationException;
use WappoVendor\Doctrine\DBAL\Exception\SyntaxErrorException;
use WappoVendor\Doctrine\DBAL\Exception\TableExistsException;
use WappoVendor\Doctrine\DBAL\Exception\TableNotFoundException;
use WappoVendor\Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use WappoVendor\Doctrine\DBAL\Query;
/**
 * @internal
 *
 * @link https://docs.microsoft.com/en-us/sql/relational-databases/errors-events/database-engine-events-and-errors
 */
final class ExceptionConverter implements ExceptionConverterInterface
{
    public function convert(Exception $exception, ?Query $query) : DriverException
    {
        switch ($exception->getCode()) {
            case 102:
                return new SyntaxErrorException($exception, $query);
            case 207:
                return new InvalidFieldNameException($exception, $query);
            case 208:
                return new TableNotFoundException($exception, $query);
            case 209:
                return new NonUniqueFieldNameException($exception, $query);
            case 515:
                return new NotNullConstraintViolationException($exception, $query);
            case 547:
            case 4712:
                return new ForeignKeyConstraintViolationException($exception, $query);
            case 2601:
            case 2627:
                return new UniqueConstraintViolationException($exception, $query);
            case 2714:
                return new TableExistsException($exception, $query);
            case 3701:
            case 15151:
                return new DatabaseObjectNotFoundException($exception, $query);
            case 11001:
            case 18456:
                return new ConnectionException($exception, $query);
        }
        return new DriverException($exception, $query);
    }
}
