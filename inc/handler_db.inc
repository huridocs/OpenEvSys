<?php
/**
 * Database handler
 *  
 * This will create the database connection for system wide use.
 * The db resource will be stored in $global['db'] variable which is
 * in global scope. 
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

//we need to enable adodb 
include_once(APPROOT.'3rd/adodb5/adodb-exceptions.inc.php'); 
require_once(APPROOT.'3rd/adodb5/adodb.inc.php');

//Instantiate an adodb connection object in $global['db']
$global['db'] = NewADOConnection($conf['db_engine']);
//$global['db']->debug = true;
//enable debug mode if configured
if(isset($conf['adodb_debug'])&&$conf['adodb_debug'])
    $global['db']->debug = true;

//make the connection
try{ 
    $host = $conf['db_host'].(isset($conf['db_port'])?':'.$conf['db_port']:'');
    $global['db']->Connect($host , $conf['db_user'] , $conf['db_pass'] , $conf['db_name']);
}catch(ADODB_Exception $e){
    echo "System Error: unable to connect to the database.";
    exit(1);
}

//If you need to add more functions please extend the adodb connection object

$global['db']->Execute("SET NAMES 'utf8'");
