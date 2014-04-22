<?php
/**
 *  Main controler for session handlers
 *  This will load the session handler according to configuration of the system
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

if( is_array( $conf['auth'] )){
    if($conf['auth']['type']=='cas'){
        $conf['session_writer'] = 'php';
    }
}
switch ($conf['session_writer']){
    case 'adodb':
        // following will load the adodb session handler 
        include_once(APPROOT."3rd/adodb5/session/adodb-session2.php");
        ADOdb_Session::config($conf['db_engine'],$conf['db_host'],$conf['db_user'],$conf['db_pass'],$conf['db_name']);
        break;
    case 'php':
    default:
        // use php's default session handler
}

//give a name to the session to prevent conflict with others
session_name($conf['session_name']);

global $conf;

//start the session
session_start();

$t1=time();

if($_SESSION["t2"]+$conf['session_timeout']*60<$t1&&isset($_SESSION['username'])){
	$global['timeOut']="You've been logged out because your account has been inactive for ".$conf['session_timeout']." minutes. Please log in agin.";
	session_destroy();
	$_SESSION['username']=null;   
}

$_SESSION["t2"]=time();

if(isset($_SESSION['username'])){
    global $global;
    $results = $global['db']->GetRow("select * from user where username ='" . $_SESSION['username'] ."'");
    if (!empty($results['config'])) {
        $cfg = @json_decode($results['config'], true);
        if ($cfg['locale']) {
            $conf['locale'] = $cfg['locale'];
        }
    }
     
}

