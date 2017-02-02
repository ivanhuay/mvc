<?php

class Example extends Collection
{
    public function __construct()
    {
        parent::__construct();
        //only with this structure you can build a rest api
        $this->structure = [
          'fullname' => 'text',
          'date2' => 'timestamp',
          'lastname' => 'text'
        ];
    }
}
