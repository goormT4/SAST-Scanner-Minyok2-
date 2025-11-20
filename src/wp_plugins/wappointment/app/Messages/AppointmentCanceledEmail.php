<?php

namespace Wappointment\Messages;

class AppointmentCanceledEmail extends \Wappointment\Messages\ClientBookingConfirmationEmail
{
    use \Wappointment\Messages\HasNoAppointmentFooterLinks;
    protected $icsRequired = \false;
    const EVENT = \Wappointment\Models\Reminder::APPOINTMENT_CANCELLED;
}
