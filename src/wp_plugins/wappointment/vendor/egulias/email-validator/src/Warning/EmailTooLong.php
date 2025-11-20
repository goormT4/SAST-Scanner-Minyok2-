<?php

namespace WappoVendor\Egulias\EmailValidator\Warning;

use WappoVendor\Egulias\EmailValidator\EmailParser;
class EmailTooLong extends Warning
{
    public const CODE = 66;
    public function __construct()
    {
        $this->message = 'Email is too long, exceeds ' . EmailParser::EMAIL_MAX_LENGTH;
    }
}
