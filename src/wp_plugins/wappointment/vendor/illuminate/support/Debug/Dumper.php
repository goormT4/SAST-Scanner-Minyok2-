<?php

namespace WappoVendor\Illuminate\Support\Debug;

use WappoVendor\Symfony\Component\VarDumper\Cloner\VarCloner;
use WappoVendor\Symfony\Component\VarDumper\Dumper\CliDumper;
class Dumper
{
    /**
     * Dump a value with elegance.
     *
     * @param  mixed  $value
     * @return void
     */
    public function dump($value)
    {
        if (\class_exists(\WappoVendor\Symfony\Component\VarDumper\Dumper\CliDumper::class)) {
            $dumper = \in_array(\PHP_SAPI, ['cli', 'phpdbg']) ? new \WappoVendor\Symfony\Component\VarDumper\Dumper\CliDumper() : new \WappoVendor\Illuminate\Support\Debug\HtmlDumper();
            $dumper->dump((new \WappoVendor\Symfony\Component\VarDumper\Cloner\VarCloner())->cloneVar($value));
        } else {
            \var_dump($value);
        }
    }
}
