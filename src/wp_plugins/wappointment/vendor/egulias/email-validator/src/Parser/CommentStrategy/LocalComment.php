<?php

namespace WappoVendor\Egulias\EmailValidator\Parser\CommentStrategy;

use WappoVendor\Egulias\EmailValidator\EmailLexer;
use WappoVendor\Egulias\EmailValidator\Result\Result;
use WappoVendor\Egulias\EmailValidator\Result\ValidEmail;
use WappoVendor\Egulias\EmailValidator\Warning\CFWSNearAt;
use WappoVendor\Egulias\EmailValidator\Result\InvalidEmail;
use WappoVendor\Egulias\EmailValidator\Result\Reason\ExpectingATEXT;
class LocalComment implements CommentStrategy
{
    /**
     * @var array
     */
    private $warnings = [];
    public function exitCondition(EmailLexer $lexer, int $openedParenthesis) : bool
    {
        return !$lexer->isNextToken(EmailLexer::S_AT);
    }
    public function endOfLoopValidations(EmailLexer $lexer) : Result
    {
        if (!$lexer->isNextToken(EmailLexer::S_AT)) {
            return new InvalidEmail(new ExpectingATEXT('ATEX is not expected after closing comments'), $lexer->token['value']);
        }
        $this->warnings[CFWSNearAt::CODE] = new CFWSNearAt();
        return new ValidEmail();
    }
    public function getWarnings() : array
    {
        return $this->warnings;
    }
}
