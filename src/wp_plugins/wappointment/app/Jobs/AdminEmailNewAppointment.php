<?php

namespace Wappointment\Jobs;

class AdminEmailNewAppointment extends \Wappointment\Jobs\AbstractAppointmentEmailJob
{
    use \Wappointment\Jobs\IsAdminAppointmentJob;
    const CONTENT = '\\Wappointment\\Messages\\AdminNewAppointmentEmail';
}
