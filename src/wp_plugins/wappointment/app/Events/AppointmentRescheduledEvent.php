<?php

namespace Wappointment\Events;

class AppointmentRescheduledEvent extends \Wappointment\Events\AppointmentBookedEvent
{
    const NAME = 'appointment.rescheduled';
}
