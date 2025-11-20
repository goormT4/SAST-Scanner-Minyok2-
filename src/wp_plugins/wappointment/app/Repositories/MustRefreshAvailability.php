<?php

namespace Wappointment\Repositories;

trait MustRefreshAvailability
{
    public function refreshAvailability()
    {
        (new \Wappointment\Repositories\Availability())->refresh();
    }
}
