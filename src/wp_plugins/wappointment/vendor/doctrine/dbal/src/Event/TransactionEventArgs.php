<?php

declare (strict_types=1);
namespace WappoVendor\Doctrine\DBAL\Event;

use WappoVendor\Doctrine\Common\EventArgs;
use WappoVendor\Doctrine\DBAL\Connection;
abstract class TransactionEventArgs extends EventArgs
{
    /** @var Connection */
    private $connection;
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }
    public function getConnection() : Connection
    {
        return $this->connection;
    }
}
