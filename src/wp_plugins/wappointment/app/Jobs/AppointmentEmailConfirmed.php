<?php

namespace Wappointment\Jobs;

class AppointmentEmailConfirmed extends \Wappointment\Jobs\AbstractAppointmentEmailJob
{
    const CONTENT = '\\Wappointment\\Messages\\ClientBookingConfirmationEmail';
}
