<?php

namespace Wappointment\Jobs;

abstract class AbstractEmailJob extends \Wappointment\Jobs\AbstractTransportableJob
{
    use \Wappointment\Jobs\IsEmailableJob, \Wappointment\Jobs\IsAdminAppointmentJob;
}
