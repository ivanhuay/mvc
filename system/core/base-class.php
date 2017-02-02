<?php
class BaseClass{
  function __construct(){
    $this->logger = new Logger(get_called_class());
  }
}
