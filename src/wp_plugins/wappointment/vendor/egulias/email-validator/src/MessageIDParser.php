<?php

namespace WappoVendor\Egulias\EmailValidator;

use WappoVendor\Egulias\EmailValidator\Result\Result;
use WappoVendor\Egulias\EmailValidator\Parser\IDLeftPart;
use WappoVendor\Egulias\EmailValidator\Parser\IDRightPart;
use WappoVendor\Egulias\EmailValidator\Result\ValidEmail;
use WappoVendor\Egulias\EmailValidator\Result\InvalidEmail;
use WappoVendor\Egulias\EmailValidator\Warning\EmailTooLong;
use WappoVendor\Egulias\EmailValidator\Result\Reason\NoLocalPart;
class MessageIDParser extends Parser
{
    public const EMAILID_MAX_LENGTH = 254;
    /**
     * @var string
     */
    protected $idLeft = '';
    /**
     * @var string
     */
    protected $idRight = '';
    public function parse(string $str) : Result
    {
        $result = parent::parse($str);
        $this->addLongEmailWarning($this->idLeft, $this->idRight);
        return $result;
    }
    protected function preLeftParsing() : Result
    {
        if (!$this->hasAtToken()) {
            return new InvalidEmail(new NoLocalPart(), $this->lexer->token["value"]);
        }
        return new ValidEmail();
    }
    protected function parseLeftFromAt() : Result
    {
        return $this->processIDLeft();
    }
    protected function parseRightFromAt() : Result
    {
        return $this->processIDRight();
    }
    private function processIDLeft() : Result
    {
        $localPartParser = new IDLeftPart($this->lexer);
        $localPartResult = $localPartParser->parse();
        $this->idLeft = $localPartParser->localPart();
        $this->warnings = \array_merge($localPartParser->getWarnings(), $this->warnings);
        return $localPartResult;
    }
    private function processIDRight() : Result
    {
        $domainPartParser = new IDRightPart($this->lexer);
        $domainPartResult = $domainPartParser->parse();
        $this->idRight = $domainPartParser->domainPart();
        $this->warnings = \array_merge($domainPartParser->getWarnings(), $this->warnings);
        return $domainPartResult;
    }
    public function getLeftPart() : string
    {
        return $this->idLeft;
    }
    public function getRightPart() : string
    {
        return $this->idRight;
    }
    private function addLongEmailWarning(string $localPart, string $parsedDomainPart) : void
    {
        if (\strlen($localPart . '@' . $parsedDomainPart) > self::EMAILID_MAX_LENGTH) {
            $this->warnings[EmailTooLong::CODE] = new EmailTooLong();
        }
    }
}
