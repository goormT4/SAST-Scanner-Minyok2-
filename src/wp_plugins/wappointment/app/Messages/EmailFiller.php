<?php

namespace Wappointment\Messages;

class EmailFiller extends \Wappointment\Messages\AbstractEmail
{
    protected function loadContent($subject = '', $body = '')
    {
        $this->subject = $subject;
        $this->body = $body;
    }
}
