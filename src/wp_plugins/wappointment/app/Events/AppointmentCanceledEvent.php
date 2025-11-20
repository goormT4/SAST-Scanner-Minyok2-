<?php

namespace Wappointment\Events;

class AppointmentCanceledEvent extends \Wappointment\Events\AppointmentBookedEvent
{
    const NAME = 'appointment.canceled';
}
