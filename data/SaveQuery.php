<?php
/**
 * Search Query saving table 
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

//require_once ( APPROOT . 'data/DataObject.php' );

class SaveQuery extends ADODB_Active_Record{
    
    public $save_query_record_number;
    public $name ;
    public $description ;
    public $created_date ;
    public $created_by ;
    public $query ;
    
    public function __construct($table = 'save_query', $pkeyarr=false, $db=false, $options=array()){
        parent::__construct($table, $pkey ,$db , $options); 
        $this->entity = $table;
        $this->keyName = $this->entity . '_record_number';
    }
    
    public function LoadfromRecordNumber($save_query_record_number){
        $this->Load("save_query_record_number='$save_query_record_number'");        
    }   

    public function Delete( $field , $value )
    {
        $db = $this->DB(); if (!$db) return false;
        $table = $this->TableInfo();
        
        $where = "$field='" . $value . "'";
        $sql = 'DELETE FROM '.$this->_table.' WHERE '.$where;
        $ok = $db->Execute($sql);
        
        if (!$ok){
            $err = $this->ErrorMsg();
            //throw new DbException($err);
            echo $err;
        }else
        return true;
    }
    
    public function DeleteFromRecordNumber($recordNumber){
            return $this->Delete($this->keyName , $recordNumber );
    }
 
}
?>
