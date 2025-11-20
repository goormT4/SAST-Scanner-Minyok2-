<?php

namespace WappoVendor\Egulias\EmailValidator\Parser;

use WappoVendor\Egulias\EmailValidator\Result\Result;
use WappoVendor\Egulias\EmailValidator\Result\InvalidEmail;
use WappoVendor\Egulias\EmailValidator\Result\Reason\CommentsInIDRight;
class IDLeftPart extends LocalPart
{
    protected function parseComments() : Result
    {
        return new InvalidEmail(new CommentsInIDRight(), $this->lexer->token['value']);
    }
}
