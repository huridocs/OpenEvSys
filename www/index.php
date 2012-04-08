<?php
/**
 * This is a re-write of Sahana framework using MVC architecture.
 *
 * Copyright (C) 2009
 *   Respere Lanka (PVT) Ltd. http://respere.com, info@respere.com
 * Copyright (C) 2009
 *   Human Rights Information and Documentation Systems,
 *   HURIDOCS), http://www.huridocs.org/, info@huridocs.org
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @auther  H J P Fonseka <jo@respere.com>
 * @package Framework
 * 
 */


//generate the base location of the system
define('APPROOT',realpath(dirname(__FILE__).'/../').'/');

//if the sysconf file does not exist then the system has not setuped yet
//call the web instaler and exit
if(!file_exists(APPROOT.'conf/sysconf.php')){
    require_once(APPROOT.'inst/install.inc');
    exit(0);
}

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

//load session handler
require_once(APPROOT.'inc/session/handler_session.inc');

//load l10n library
require_once(APPROOT.'inc/i18n/handler_l10n.inc');

//load authentication handler
require_once(APPROOT.'inc/security/handler_auth.inc');
//load acl 
require_once(APPROOT.'inc/security/handler_acl.inc');



//sahana compatibility library

/******** Load the Controler ********************************************/

//let's get in to the business

$controller =shnFrontController::getController();
$controller->dispatch();
