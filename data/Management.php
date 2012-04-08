<?php

/**
 * DataObject For Management fields of each Entity of OpenEvSys.
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
class Management extends ADODB_Active_Record{
    
    public $entity_type;
    public $entity_id ;
    public $date_received;
    public $date_of_entry;
    public $entered_by ;
    public $project_title;
    public $comments ;
    public $record_grouping;
    public $date_updated ;
    public $updated_by ;
    public $monitoring_status;
    public $entity;
    public $keyName;
    
    public $supporting_documents = array();  
    protected $pkey = array('entity_type' , 'entity_id');
    
    public function __construct($table = false, $pkeyarr=false, $db=false, $options=array() ){
        parent::__construct('management', $pkey ,$db , $options);
        $this->entity = $table;  
        $this->keyName = 'entity_type';     
    }
   
    
    public function LoadfromEntityId($entity_type,  $entity_id){
        $this->Load("entity_type ='$entity_type' AND entity_id='$entity_id'");
    }
    
    
    public function Save(){
        if($this->entity_name != null && $this->entity_id != null ){
            echo 'management:Save - no entity_name or entity_id defined';
            return false;
        }
        else{
            parent::Save();
        }
    }
    
	public function getEntityType()
    {
        return $this->entity;
    }
    
    
}
?>