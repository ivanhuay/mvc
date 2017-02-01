<?php

class Logger
{
    public function __construct($className = null, $logPrefix = 'plankApplication')
    {
        $this->className = $className;
        if (!empty($className)) {
            $logPrefix = $className;
        }
        openlog($logPrefix, LOG_PID | LOG_PERROR, LOG_LOCAL0);
    }
    public function debug($message)
    {
        syslog(LOG_DEBUG, '[DEBUG] '.$message);
    }
    public function info($message)
    {
        syslog(LOG_INFO, '[INFO] '.$message);
    }
    public function warning($message)
    {
        syslog(LOG_WARNING, '[WARNING] '.$message);
    }
    public function error($message)
    {
        syslog(LOG_ERR, '[ERROR] '.$message);
    }
}
