<?php

namespace Wappointment\Messages;

class AppointmentEmailFiller extends \Wappointment\Messages\AbstractEmail
{
    use \Wappointment\Messages\HasAppointmentFooterLinks;
    protected function loadContent()
    {
        $this->subject = $this->params['subject'];
        $this->body = $this->params['body'];
    }
}
