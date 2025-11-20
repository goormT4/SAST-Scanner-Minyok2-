<?php

namespace Wappointment\Messages;

use Wappointment\Models\Reminder;
class AppointmentRescheduledEmail extends \Wappointment\Messages\AbstractEmail
{
    use \Wappointment\Messages\HasAppointmentFooterLinks, \Wappointment\Messages\HasTagsToReplace, \Wappointment\Messages\AttachesIcs, \Wappointment\Messages\PreparesClientEmail;
    protected $client = null;
    protected $appointment = null;
    protected $icsRequired = \true;
    const EVENT = Reminder::APPOINTMENT_RESCHEDULED;
    public function loadContent()
    {
        if (!$this->prepareClientEmail($this->params['client'], $this->params['appointment'], static::EVENT)) {
            return \false;
        }
        if ($this->icsRequired) {
            $this->attachIcs([$this->params['appointment']], 'appointment');
        }
    }
}
