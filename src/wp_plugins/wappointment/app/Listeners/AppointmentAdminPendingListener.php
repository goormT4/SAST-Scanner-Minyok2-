<?php

namespace Wappointment\Listeners;

class AppointmentAdminPendingListener extends \Wappointment\Listeners\AbstractJobAppointmentListener
{
    protected $jobClass = '\\Wappointment\\Jobs\\AdminEmailPendingAppointment';
}
