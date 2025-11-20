<?php

namespace Wappointment\Jobs;

abstract class AbstractTransportableJob implements \Wappointment\Jobs\JobInterface
{
    public $transport = null;
    protected $params = [];
    public abstract function setTransport();
    public function __construct($params)
    {
        $this->setTransport();
        $this->parseParams($params);
    }
    protected function parseParams($params)
    {
        $this->params = $params;
    }
    protected function generateContent()
    {
        $contentClass = static::CONTENT;
        return new $contentClass($this->params);
    }
}
