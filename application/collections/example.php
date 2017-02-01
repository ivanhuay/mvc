<?php

class Example extends Collection
{
    public function __construct()
    {
        parent::__construct();
        $this->structure = [
      'id' => 'int auto_increment primary key',
      'fullname' => 'text',
      'date2' => 'timestamp',
      'lastname' => 'text',
    ];
        $this->migrate = [
      'date' => 'date2',
    ];
    }
}
