<?php

namespace Wappointment\Messages;

use Wappointment\Models\Reminder;
class AppointmentReminderEmail extends \Wappointment\Messages\AbstractEmail
{
    use \Wappointment\Messages\HasAppointmentFooterLinks;
    use \Wappointment\Messages\HasTagsToReplace;
    use \Wappointment\Messages\AttachesIcs;
    use \Wappointment\Messages\PreparesClientEmail;
    protected $icsRequired = \true;
    public $client;
    public $appointment;
    public const EVENT = Reminder::APPOINTMENT_STARTS;
    public function loadContent()
    {
        $reminder_id = empty($this->params['reminder_id']) ? \false : $this->params['reminder_id'];
        if ($reminder_id) {
            if (!$this->prepareClientEmail($this->params['client'], $this->params['appointment'], static::EVENT, $reminder_id)) {
                return \false;
            }
            $this->attachIcs([$this->params['appointment']], 'appointment');
        }
    }
}
