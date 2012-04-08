<?php

/**
 * DataObject for Handling Clarifying notes of OpenEvSys.
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

class ClariNotes extends ADODB_Active_Record{
    public $clari_notes_record_number;
    public $entity;
    public $record_number;
    public $field_name;
    public $vocab_number;
    public $value;
    
    private $pkey = array('clari_notes_record_number');
    
    public function __construct($table = false, $pkeyarr=false, $db=false, $options=array()){
        parent::__construct('clari_notes', $pkey ,$db , $options); 
        $table = 'clari_notes';      
    }
    
    public function Find($entity , $record_number, $field_name ){
        //var_dump("entity='$entity' AND record_number='$record_number' AND field_name ='$field_name' ORDER BY vocab_number");
        $notes = parent::Find("entity='$entity' AND record_number='$record_number' AND field_name ='$field_name' ORDER BY vocab_number" );
        return $notes;
    }
    
    public function Load($entity , $record_number, $field_name , $vocab_number ){
        //parent::Load("entity='$entity' AND record_number='$record_number' AND field_name ='$field_name' AND vocab_number='$vocab_number' " );
    }
    
    public function Save(){
        if($this->clari_notes_record_number != null){
            $this->Load( "clari_notes_record_number = '$this->clari_notes_record_number'" );
        }else{
            $this->clari_notes_record_number = shn_create_uuid('clari_notes');
            parent::Save();
        }
    }
    
    public static function deleteExistingNotes($entity , $record_number){
        $clariNotes = new ClariNotes();
        $clariNotes->deleteNotes($entity , $record_number);

    }
    
    public function deleteNotes($entity , $record_number){
        $db = $this->DB(); if (!$db) return false;
        $table = $this->TableInfo();
        
        $where = "entity='$entity' AND record_number='$record_number'";
        $sql = 'DELETE FROM '.$this->_table.' WHERE '.$where;

        $ok = $db->Execute($sql);

        return $ok ? true : false;
    }
    
    public static function getClariNoteObject($entity , $entity_key_value , $fieldName , $vocab_number , $value){
        $clari_note_object = new ClariNotes();
        $clari_note_object->Load($entity , $entity_key_value ,$fieldName,$vocab_number  );
        $clari_note_object->entity = $entity ; 
        $clari_note_object->record_number = $entity_key_value;
        $clari_note_object->field_name = $fieldName ;                            
        $clari_note_object->vocab_number = $vocab_number; //iterate this array to get clari notes for each multi value
        $clari_note_object->value = $value;
        return  $clari_note_object;
    }
}
?>
