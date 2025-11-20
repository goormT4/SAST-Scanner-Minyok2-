<?php

namespace WappoVendor\Illuminate\Database\PDO;

use WappoVendor\Doctrine\DBAL\Driver\AbstractPostgreSQLDriver;
use WappoVendor\Illuminate\Database\PDO\Concerns\ConnectsToDatabase;
class PostgresDriver extends AbstractPostgreSQLDriver
{
    use ConnectsToDatabase;
}
