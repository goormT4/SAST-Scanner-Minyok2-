<?php

namespace Wappointment\Jobs;

class AppointmentEmailCanceled extends \Wappointment\Jobs\AppointmentEmailConfirmed
{
    const CONTENT = '\\Wappointment\\Messages\\AppointmentCanceledEmail';
}
