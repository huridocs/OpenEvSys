<?php
define('DS',DIRECTORY_SEPARATOR);

//generate the base location of the system
define('APPROOT',realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR);
define('WWWWROOT',realpath(dirname(__FILE__).DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR);

/******** If the system is installed start the bootstrap process ********/

//Include the configuration file at the begining since
//rest of the handlers will require configuration values
require_once(APPROOT.'conf/sysconf.php');

//load error and exception handlers
require_once(APPROOT.'inc/handler_error.inc');
require_once(APPROOT.'inc/handler_exception.inc');

//load db handler
require_once(APPROOT.'inc/handler_db.inc');

//overide conf values from db
require_once(APPROOT.'inc/handler_config.inc');

//input ( $_GET , $_POST ) validation utf-8
require_once(APPROOT.'inc/handler_filter.inc');

//utility function used by the system
//this contain autoload to string manipulation
require_once(APPROOT.'inc/lib_util.inc');

//load l10n library
require_once(APPROOT.'inc/i18n/handler_l10n.inc');