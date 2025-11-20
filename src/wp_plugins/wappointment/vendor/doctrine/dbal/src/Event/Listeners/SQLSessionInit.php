<?php

namespace WappoVendor\Doctrine\DBAL\Event\Listeners;

use WappoVendor\Doctrine\Common\EventSubscriber;
use WappoVendor\Doctrine\DBAL\Event\ConnectionEventArgs;
use WappoVendor\Doctrine\DBAL\Events;
use WappoVendor\Doctrine\DBAL\Exception;
/**
 * Session init listener for executing a single SQL statement right after a connection is opened.
 */
class SQLSessionInit implements EventSubscriber
{
    /** @var string */
    protected $sql;
    /**
     * @param string $sql
     */
    public function __construct($sql)
    {
        $this->sql = $sql;
    }
    /**
     * @return void
     *
     * @throws Exception
     */
    public function postConnect(ConnectionEventArgs $args)
    {
        $args->getConnection()->executeStatement($this->sql);
    }
    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [Events::postConnect];
    }
}
