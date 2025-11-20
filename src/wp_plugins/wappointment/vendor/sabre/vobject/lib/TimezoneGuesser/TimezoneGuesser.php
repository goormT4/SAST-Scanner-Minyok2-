<?php

namespace WappoVendor\Sabre\VObject\TimezoneGuesser;

use DateTimeZone;
use WappoVendor\Sabre\VObject\Component\VTimeZone;
interface TimezoneGuesser
{
    public function guess(VTimeZone $vtimezone, bool $failIfUncertain = \false) : ?DateTimeZone;
}
