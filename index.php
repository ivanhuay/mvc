<?php

define('SYSTEM', 'system/');
define('APPFOLDER', 'application/');
//load config
require APPFOLDER.'config/paths.php';
if (file_exists(APPFOLDER.'config/database_dev.php')) {
    require APPFOLDER.'config/database_dev.php';
} else {
    require APPFOLDER.'config/database.php';
}
define('PUBDIR', URL.'public/');

require SYSTEM.'core/database.php';
require SYSTEM.'orm/orm.php';
require SYSTEM.'core/session.php';

require SYSTEM.'core/logger.php';
require SYSTEM.'core/base-class.php';
require SYSTEM.'orm/rest.php';
require SYSTEM.'core/load.php';
require SYSTEM.'core/model.php';
require SYSTEM.'core/controller.php';
require SYSTEM.'core/collection.php';

require SYSTEM.'core/plank.php';

$app = new Plank();

//reference
function get_instance()
{
    return Controller::get_instance();
}
