<?php

declare (strict_types=1);
namespace WappoVendor\Doctrine\DBAL\Driver\API\OCI;

use WappoVendor\Doctrine\DBAL\Driver\API\ExceptionConverter as ExceptionConverterInterface;
use WappoVendor\Doctrine\DBAL\Driver\Exception;
use WappoVendor\Doctrine\DBAL\Exception\ConnectionException;
use WappoVendor\Doctrine\DBAL\Exception\DatabaseDoesNotExist;
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
 */
final class ExceptionConverter implements ExceptionConverterInterface
{
    /**
     * @link http://www.dba-oracle.com/t_error_code_list.htm
     */
    public function convert(Exception $exception, ?Query $query) : DriverException
    {
        switch ($exception->getCode()) {
            case 1:
            case 2299:
            case 38911:
                return new UniqueConstraintViolationException($exception, $query);
            case 904:
                return new InvalidFieldNameException($exception, $query);
            case 918:
            case 960:
                return new NonUniqueFieldNameException($exception, $query);
            case 923:
                return new SyntaxErrorException($exception, $query);
            case 942:
                return new TableNotFoundException($exception, $query);
            case 955:
                return new TableExistsException($exception, $query);
            case 1017:
            case 12545:
                return new ConnectionException($exception, $query);
            case 1400:
                return new NotNullConstraintViolationException($exception, $query);
            case 1918:
                return new DatabaseDoesNotExist($exception, $query);
            case 2289:
            case 2443:
            case 4080:
                return new DatabaseObjectNotFoundException($exception, $query);
            case 2266:
            case 2291:
            case 2292:
                return new ForeignKeyConstraintViolationException($exception, $query);
        }
        return new DriverException($exception, $query);
    }
}
