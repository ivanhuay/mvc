<?php
class BaseClass{
  function __construct(){
    $this->logger = new Logger(get_called_class());
  }
  public function __call($method, $arguments){
    $this->logger->info('base class method:'.$method);
  }
}
