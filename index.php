<?php

/*mvc*/



/* Nombre de carpeta del sistema */

$system_folder='system';


/* Nombre de la carpeta de la aplicacion */

$application_folder='application';


//me aseguro que la system tenga / al final
$system_folder = rtrim($system_folder, '/').'/';

//defino la carpeta de del sistema
define('BASEPATH',str_replace("\\","/",$system_folder));




//compruebo que la carpeta aplicattion este vien seteada
if(is_dir($application_folder))
{
	define('APPPATH',$application_folder.'/');
}else
{
	exit('La carpeta de la aplicacion no parece ser correcta.');
}

require_once(BASEPATH.'core/mvc.php');