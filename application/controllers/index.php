<?php
class Index extends Controller
{
	function __construct()
	{
		parent::__construct();
	}
	public function index()
	{
		$this->logger->info('test');
		$this->logger->warning('test warning');
		$this->logger->error('test error');
		$this->load->view('index/index');
	}
}
