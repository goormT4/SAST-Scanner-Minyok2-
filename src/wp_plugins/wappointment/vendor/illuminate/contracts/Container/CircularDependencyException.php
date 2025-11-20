<?php

namespace WappoVendor\Illuminate\Contracts\Container;

use Exception;
use WappoVendor\Psr\Container\ContainerExceptionInterface;
class CircularDependencyException extends Exception implements ContainerExceptionInterface
{
    //
}
