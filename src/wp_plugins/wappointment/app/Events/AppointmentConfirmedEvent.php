<?php

namespace Wappointment\Events;

class AppointmentConfirmedEvent extends \Wappointment\Events\AppointmentBookedEvent
{
    const NAME = 'appointment.confirmed';
}
