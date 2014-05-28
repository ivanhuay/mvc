<?php

define('SYSTEM','system/');
define('APPFOLDER','application/');

require(SYSTEM.'core/load.php');
require(SYSTEM.'core/model.php');
require(SYSTEM.'core/controller.php');
require(SYSTEM.'core/view.php');

require('application/libs/bootstrap.php');
$app =new Bootstrap();

//referencia
	function get_instance()
	{
		return Controller::get_instance();
	}	
