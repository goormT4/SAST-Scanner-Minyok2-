<?php

namespace Wappointment\Services;

class TempFile extends \Wappointment\Services\AbstractFile
{
    public function initialize()
    {
        $this->setHandler();
    }
    protected function setName($name)
    {
        return empty($name) ? \uniqid('temp') . '.txt' : $name;
    }
}
