<?php

define('SYSTEM','system/');
define('APPFOLDER','application/');

require(SYSTEM.'core/load.php');
require(SYSTEM.'core/model.php');
require(SYSTEM.'core/controller.php');
require(SYSTEM.'core/view.php');

require(SYSTEM.'core/bootstrap.php');


//configuraciones 

require(APPFOLDER.'config/paths.php');
require(APPFOLDER.'config/database.php');
$app =new Bootstrap();

//referencia
	function get_instance()
	{
		return Controller::get_instance();
	}	
