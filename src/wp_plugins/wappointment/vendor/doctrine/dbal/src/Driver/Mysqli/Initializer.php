<?php

declare (strict_types=1);
namespace WappoVendor\Doctrine\DBAL\Driver\Mysqli;

use WappoVendor\Doctrine\DBAL\Driver\Exception;
use mysqli;
interface Initializer
{
    /**
     * @throws Exception
     */
    public function initialize(mysqli $connection) : void;
}
