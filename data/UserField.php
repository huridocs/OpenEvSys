<?php

/**
 * DataObject User terms of OpenEvSys.
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

class UserField extends ADODB_Active_Record{
    
    public $username;
    public $record_number;    
    
    
    protected $pkey = array('username' , 'record_number');
    
    
   public function __construct($table = false, $pkeyarr=false, $db=false, $options=array() ){
        parent::__construct($table, $pkey ,$db , $options);

        $this->belongsTo( 'user' , 'username' , 'username' ) ;
        

        
   }
    

    public function getUsersforEntity($event_number){
        
        $records = $this->Find('record_number=' . "'" . $event_number . "'" );        

        return $records; 
    }
    
    
    public function DeleteUsersForEntity($entity_number){
        $db = $this->DB(); if (!$db) return false;
        $table = $this->TableInfo();
        
        $where = "record_number='$entity_number'";
        $sql = 'DELETE FROM '.$this->_table.' WHERE '.$where;
        $ok = $db->Execute($sql);
        
        return $ok ? true : false;
    }
}
