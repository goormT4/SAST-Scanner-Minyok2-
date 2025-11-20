<?php

declare (strict_types=1);
namespace WappoVendor\Doctrine\DBAL\Driver;

use WappoVendor\Doctrine\DBAL\Driver;
interface Middleware
{
    public function wrap(Driver $driver) : Driver;
}
