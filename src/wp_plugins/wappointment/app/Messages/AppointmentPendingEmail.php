<?php

namespace Wappointment\Messages;

class AppointmentPendingEmail extends \Wappointment\Messages\ClientBookingConfirmationEmail
{
    use \Wappointment\Messages\HasNoAppointmentFooterLinks;
    protected $icsRequired = \false;
    public const EVENT = \Wappointment\Models\Reminder::APPOINTMENT_PENDING;
}
