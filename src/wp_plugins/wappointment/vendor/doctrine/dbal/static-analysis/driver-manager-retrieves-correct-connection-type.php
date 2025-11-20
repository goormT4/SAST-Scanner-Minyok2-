<?php

declare (strict_types=1);
namespace WappoVendor\Doctrine\StaticAnalysis\DBAL;

use WappoVendor\Doctrine\DBAL\Connection;
use WappoVendor\Doctrine\DBAL\DriverManager;
final class MyConnection extends Connection
{
}
function makeMeACustomConnection() : MyConnection
{
    return DriverManager::getConnection(['wrapperClass' => MyConnection::class]);
}
