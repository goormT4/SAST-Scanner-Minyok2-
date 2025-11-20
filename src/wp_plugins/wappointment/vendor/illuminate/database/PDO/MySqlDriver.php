<?php

namespace WappoVendor\Illuminate\Database\PDO;

use WappoVendor\Doctrine\DBAL\Driver\AbstractMySQLDriver;
use WappoVendor\Illuminate\Database\PDO\Concerns\ConnectsToDatabase;
class MySqlDriver extends AbstractMySQLDriver
{
    use ConnectsToDatabase;
}
