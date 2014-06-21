<?php

class Index extends Controller
{
	function __construct()
	{
		parent::__construct();
	}
	public function index()
	{
		$data['msg']="<h1>Bienvenido | index page</h1>";
		$this->load->view('index/index',$data);
	}
}