<?php

class Model extends BaseClass
{
    public function __construct()
    {
        parent::__construct();
        $this->db = new Database();
        $this->session = new Session();
    }
}
