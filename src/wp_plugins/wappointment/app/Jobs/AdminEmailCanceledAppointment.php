<?php

namespace Wappointment\Jobs;

class AdminEmailCanceledAppointment extends \Wappointment\Jobs\AbstractAppointmentEmailJob
{
    use \Wappointment\Jobs\IsAdminAppointmentJob;
    const CONTENT = '\\Wappointment\\Messages\\AdminCanceledAppointmentEmail';
}
