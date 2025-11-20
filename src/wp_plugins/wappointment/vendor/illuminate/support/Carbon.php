<?php

namespace WappoVendor\Illuminate\Support;

use WappoVendor\Carbon\Carbon as BaseCarbon;
use WappoVendor\Carbon\CarbonImmutable as BaseCarbonImmutable;
class Carbon extends BaseCarbon
{
    /**
     * {@inheritdoc}
     */
    public static function setTestNow($testNow = null)
    {
        BaseCarbon::setTestNow($testNow);
        BaseCarbonImmutable::setTestNow($testNow);
    }
}
