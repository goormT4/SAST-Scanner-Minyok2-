<?php

namespace WappoVendor\Egulias\EmailValidator\Parser;

use WappoVendor\Egulias\EmailValidator\EmailLexer;
use WappoVendor\Egulias\EmailValidator\Result\Result;
use WappoVendor\Egulias\EmailValidator\Result\ValidEmail;
use WappoVendor\Egulias\EmailValidator\Result\InvalidEmail;
use WappoVendor\Egulias\EmailValidator\Result\Reason\ExpectingATEXT;
class IDRightPart extends DomainPart
{
    protected function validateTokens(bool $hasComments) : Result
    {
        $invalidDomainTokens = [EmailLexer::S_DQUOTE => \true, EmailLexer::S_SQUOTE => \true, EmailLexer::S_BACKTICK => \true, EmailLexer::S_SEMICOLON => \true, EmailLexer::S_GREATERTHAN => \true, EmailLexer::S_LOWERTHAN => \true];
        if (isset($invalidDomainTokens[$this->lexer->token['type']])) {
            return new InvalidEmail(new ExpectingATEXT('Invalid token in domain: ' . $this->lexer->token['value']), $this->lexer->token['value']);
        }
        return new ValidEmail();
    }
}
