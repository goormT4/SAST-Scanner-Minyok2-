<?php

namespace WappoVendor\Doctrine\DBAL\Event;

use WappoVendor\Doctrine\Common\EventArgs;
use WappoVendor\Doctrine\DBAL\Connection;
/**
 * Event Arguments used when a Driver connection is established inside Doctrine\DBAL\Connection.
 */
class ConnectionEventArgs extends EventArgs
{
    /** @var Connection */
    private $connection;
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }
    /**
     * @return Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }
}
