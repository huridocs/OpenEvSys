<?php
/**
 * DataObject For Loging.
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
include_once APPROOT.'inc/lib_uuid.inc';


class Log extends ADODB_Active_Record{
    

    public $log_record_number;
    public $entity;
    public $record_number;
    public $action;
    public $description;
    public $query;
    public $username;
    public $module;
    public $module_record_number;

    private $pkey = array('entity' , 'record_number');
    
    public function __construct($table = false, $pkeyarr=false, $db=false, $options=array()){
        parent::__construct('audit_log', $pkey ,$db , $options);   
    }
    
    public static function saveLogDetails($entity,  $record_number , $action , $description = null , $query=null ){
        $log = new Log();
        $log->log_record_number = shn_create_uuid('log');
        $log->entity = $entity;
        $log->module = $_GET['mod'];
        //var_dump($_GET);
        if(  isset($_GET['eid']) ){
            $mrn = $_GET['eid'];
        }else if (isset($_GET['pid']) ){
            $mrn = $_GET['pid'];
        }else if ( isset($_GET['person_id']) ) {
            $mrn = $_GET['person_id'] ;
        }else if ( isset($_GET['doc_id']) ) {
            $mrn = $_GET['doc_id'] ;
        }else{
            $mrn = $record_number;
        }
        
        $log->module_record_number = $mrn;
        $log->record_number = $record_number;
        $log->action = $action;
        $log->description = $description;
        $log->query = $query;
        $log->username = $_SESSION['username'];

        $log->Save();
    }
    
    
}
    
?>