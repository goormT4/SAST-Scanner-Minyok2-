<?php

namespace Wappointment\Managers;

class Client
{
    public static function model()
    {
        return \Wappointment\Managers\Central::get('Client');
    }
}
