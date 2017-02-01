<?php

class Rest extends BaseClass
{
    public function __construct($enabledMethod = ['GET', 'POST', 'PUT', 'DELETE'])
    {
        parent::__construct();
        $this->enabledMethod = $enabledMethod;
    }
    public function __call($method, $arguments)
    {
        if (!in_array($_SERVER['REQUEST_METHOD'], $this->enabledMethod)) {
            $this->logger->info('Unauthorized access - method:'.$method);

            return false;
        }
    }
}
