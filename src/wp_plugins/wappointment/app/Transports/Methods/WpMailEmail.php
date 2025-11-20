<?php

namespace Wappointment\Transports\Methods;

use Wappointment\Transports\WpMail;
class WpMailEmail implements \Wappointment\Transports\Methods\InterfaceEmailTransport
{
    public function setMethod($config)
    {
        return new WpMail($config);
    }
}
