<?php

namespace Wappointment\Jobs;

class AppointmentEmailPending extends \Wappointment\Jobs\AppointmentEmailConfirmed
{
    const CONTENT = '\\Wappointment\\Messages\\AppointmentPendingEmail';
}
