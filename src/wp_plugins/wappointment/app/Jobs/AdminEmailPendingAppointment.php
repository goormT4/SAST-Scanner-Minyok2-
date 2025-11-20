<?php

namespace Wappointment\Jobs;

class AdminEmailPendingAppointment extends \Wappointment\Jobs\AbstractAppointmentEmailJob
{
    use \Wappointment\Jobs\IsAdminAppointmentJob;
    const CONTENT = '\\Wappointment\\Messages\\AdminPendingAppointmentEmail';
}
