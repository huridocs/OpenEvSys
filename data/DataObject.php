<?php 
/**
 * Sets up ADODB active record.
 *
 * This file is part of OpenEvsys.
 *
 * Copyright (C) 2009  Human Rights Information and Documentation Systems,
 *                     HURIDOCS), http://www.huridocs.org/, info@huridocs.org
 *
 * OpenEvsys is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OpenEvsys is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 
 * @author	Nilushan Silva <nilushan@respere.com>
 * 
 * @package	OpenEvsys
 * @subpackage	DataModel
 *
 */

require_once ( APPROOT . '3rd/adodb5/adodb-active-record.inc.php');
require_once ( APPROOT. '3rd/adodb5/adodb.inc.php');
require_once ( APPROOT . '3rd/adodb5/adodb-exceptions.inc.php');
include(APPROOT . '3rd/adodb5/adodb.inc.php'); # load code common to ADOdb

//require_once ( APPROOT . 'data/DbException.php');

//require_once ( APPROOT . 'data/User.php');
//require_once ( APPROOT . 'data/UserHelper.php');


//require_once ( APPROOT . 'data/UserProfile.php');
//require_once ( APPROOT . 'data/UserCode.php');
//require_once ( APPROOT . 'data/UserCodeHelper.php');

//require_once ( APPROOT . 'data/Event.php');
//require_once ( APPROOT . 'data/Person.php');
//require_once ( APPROOT . 'data/Act.php');



//require_once ( APPROOT . 'data/MtField.php');
//require_once ( APPROOT . 'data/MtFieldWrapper.php');

//require_once ( APPROOT . 'data/MtIndex.php');
//require_once ( APPROOT . 'data/MtTerms.php');


$ADODB_CACHE_DIR = '/usr/ADODB_cache';


		//$db = NewADOConnection($conf['db_engine']."://".$conf['db_user'].":".$conf['db_pass']."@".$conf['db_host']."/".$conf['db_name']);
//    	$db->debug = true;
		ADOdb_Active_Record::SetDatabaseAdapter($global['db']);
		ADODB_Active_Record::$_changeNames = false;



?>
