<?php

namespace WappoVendor\Illuminate\Database\PDO;

use WappoVendor\Doctrine\DBAL\Driver\AbstractSQLiteDriver;
use WappoVendor\Illuminate\Database\PDO\Concerns\ConnectsToDatabase;
class SQLiteDriver extends AbstractSQLiteDriver
{
    use ConnectsToDatabase;
}
