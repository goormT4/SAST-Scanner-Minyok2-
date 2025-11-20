<?php

namespace WappoVendor\Egulias\EmailValidator\Parser\CommentStrategy;

use WappoVendor\Egulias\EmailValidator\EmailLexer;
use WappoVendor\Egulias\EmailValidator\Result\Result;
interface CommentStrategy
{
    /**
     * Return "true" to continue, "false" to exit
     */
    public function exitCondition(EmailLexer $lexer, int $openedParenthesis) : bool;
    public function endOfLoopValidations(EmailLexer $lexer) : Result;
    public function getWarnings() : array;
}
