<?php

namespace Wappointment\Validators\HttpRequest;

class SubscribeAdmin extends \Wappointment\Validators\HttpRequest\AbstractProcessor
{
    protected $autoResponse = \true;
    protected function validationMessages()
    {
        return ['email' => 'Your email is not valid'];
    }
    protected function validationRules()
    {
        return ['email' => 'required|email', 'list' => ''];
    }
}
