<?php

/**
 * DataObject For Geometry fields of each Entity of OpenEvSys.
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

 * @author	Hmayak Tigranyan <hmayak.tigranyan@huridocs.org>
 * 
 * @package	OpenEvsys
 * @subpackage	DataModel
 *
 */
class Geometry extends ADODB_Active_Record {

    public $geometry_record_number;
    public $entity_type;
    public $entity_id;
    public $geometry;
    public $field_name;
    public $keyName;
    
    protected $pkey = array('event_record_number');

    public function __construct($entity_type = false, $pkeyarr = false, $db = false, $options = array()) {
        parent::__construct('mlt_geometry', $pkey, $db, $options);
        if($entity_type){
            $this->entity_type = $entity_type;
        }
        $this->keyName = 'entity_type';
    }

    public function getFromEntityId($entity_id,$entity_type = null) {
        global $global;
        if(!$entity_type){
            $entity_type = $this->entity_type;
        }
        
         global $global;
        global $conf;
        $sql = "SELECT AsText(`geometry`) as `geometry`,field_name
                    FROM `mlt_geometry` WHERE entity_type='$entity_type' AND entity_id='$entity_id'";
        $items = $global['db']->GetAll($sql);
        $results = array();
        foreach($items as $item){
            $results[$item["field_name"]][] = $item["geometry"];
        }
        
        return $results;
        
        
    }
    
    function DeleteByEntityId($entity_id)
	{
		$db = $this->DB(); if (!$db) return false;
		
		$sql = "DELETE FROM  `mlt_geometry` WHERE entity_type='$this->entity_type' AND entity_id='$entity_id' ";
		$ok = $db->Execute($sql);
		
		return $ok ? true : false;
	}

    public function Save() {
        parent::Save();
    }

    public function getEntityType() {
        return $this->entity_type;
    }
    function Insert()
	{
		$db = $this->DB(); if (!$db) return false;
		$cnt = 0;
		$table = $this->TableInfo();
		
		$valarr = array();
		$names = array();
		$valstr = array();
                

		foreach($table->flds as $name=>$fld) {
			$val = $this->$name;
                        
			if(!is_array($val) || !is_null($val) || !array_key_exists($name, $table->keys)) {
                                if($fld->type == "geometry"){
                                    
                                   $valstr[] = "GeomFromText('".$val."')";
                                }else{
                                    $valstr[] = $db->qstr($val);
                                }
				$names[] = $this->_QName($name,$db);
                                //$valstr[] = $db->Param($cnt);
                                
				$cnt += 1;
			}
		}
		
		if (empty($names)){
			foreach($table->flds as $name=>$fld) {
				$valarr[] = null;
                                
				$names[] = $name;
                                
				$valstr[] = null;
				$cnt += 1;
			}
		}
		$sql = 'INSERT INTO '.$this->_table."(".implode(',',$names).') VALUES ('.implode(',',$valstr).')';
		$ok = $db->Execute($sql);
		
		if ($ok) {
			$this->_saved = true;
			$autoinc = false;
			foreach($table->keys as $k) {
				if (is_null($this->$k)) {
					$autoinc = true;
					break;
				}
			}
			if ($autoinc && sizeof($table->keys) == 1) {
				$k = reset($table->keys);
				$this->$k = $this->LastInsertID($db,$k);
			}
		}
		
		$this->_original = $valarr;
		return !empty($ok);
	}
	

}

?>