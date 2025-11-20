<?php

namespace Wappointment\Services;

class Regenerate
{
    public static function all()
    {
        foreach (\Wappointment\Services\Staff::get() as $staff) {
            (new \Wappointment\Services\Availability($staff['id']))->regenerate();
        }
    }
}
