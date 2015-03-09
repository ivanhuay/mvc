<?php

define('SYSTEM','system/');
define('APPFOLDER','application/');
//configuraciones 
require(APPFOLDER.'config/paths.php');
require(APPFOLDER.'config/database.php');
define('PUBDIR',URL.'public/');

require(SYSTEM.'core/load.php');
require(SYSTEM.'core/model.php');
require(SYSTEM.'core/controller.php');


require(SYSTEM.'core/plank.php');

require(SYSTEM.'core/database.php');
require(SYSTEM.'core/session.php');

$app =new Plank();

//referencia
	function get_instance()
	{
		return Controller::get_instance();
	}	
