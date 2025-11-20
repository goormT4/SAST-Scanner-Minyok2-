<?php

namespace WappoVendor\Doctrine\DBAL\Portability;

use WappoVendor\Doctrine\DBAL\Driver\Middleware\AbstractStatementMiddleware;
use WappoVendor\Doctrine\DBAL\Driver\Result as ResultInterface;
use WappoVendor\Doctrine\DBAL\Driver\Statement as DriverStatement;
/**
 * Portability wrapper for a Statement.
 */
final class Statement extends AbstractStatementMiddleware
{
    /** @var Converter */
    private $converter;
    /**
     * Wraps <tt>Statement</tt> and applies portability measures.
     */
    public function __construct(DriverStatement $stmt, Converter $converter)
    {
        parent::__construct($stmt);
        $this->converter = $converter;
    }
    /**
     * {@inheritdoc}
     */
    public function execute($params = null) : ResultInterface
    {
        return new Result(parent::execute($params), $this->converter);
    }
}
