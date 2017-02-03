<?php
class ErrorManager extends Controller{
	function __construct()
	{
		parent::__construct();
	}
	public function index()
	{
		$data['error']="This page doesn't exist.";
		$this->load->view('error/index');
	}
}
