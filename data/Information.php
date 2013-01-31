<?php
/**
 * DataObject For Information Entity of OpenEvSys.
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

class Information extends DomainEntity{
    

    public $information_record_number;
    public $event;
    public $source;
    public $related_person;
    public $related_event;
    public $confidentiality;
    public $source_connection_to_information;
    public $date_of_source_material;
    public $remarks;
    public $reliability_of_information;
    
    
    public $Management;
    
    //Management
    
    public $date_received;
    public $date_of_entry;
    public $entered_by ;
    public $project_title;
    public $comments ;
    public $record_grouping;
    public $date_updated ;
    public $updated_by ;
    public $monitoring_status;
    
    
    //protected $mt =  array('language_of_source_material' , 'local_language_of_source_material' , 'type_of_source_material' );
    
    
    protected $managementFields = array( 'date_received', 'date_of_entry',
'entered_by' , 'project_title', 'comments', 'record_grouping',
'date_updated' , 'updated_by' , 'monitoring_status');
    
    protected $supporting_doc = true;
    

    private $pkey = array('information_record_number');
    
    public function __construct($table = false, $pkeyarr=false, $db=false, $options=array()){
        parent::__construct('information', $pkey ,$db , $options); 


    }
/*    
    public function SaveAll(){        
        $saveType = $this->getSaveType() ;
        
        $this->Save();
        $this->SaveManagementData();
        foreach($this->mt as $mtField){
            if(sizeof( $this->$mtField) > 0 ){
                MtFieldWrapper::setMTTermsforEntity( $mtField , 'information' , $this->information_record_number, $this->$mtField);
            
            
            }
        }

        $this->saveClarifyingNotes();

        Log::saveLogDetails($this->_table , $this->information_record_number , $saveType );
        
        
    }
    
    public function LoadfromRecordNumber($information_record_number){
        $this->Load("information_record_number='$information_record_number'");
        $this->LoadManagementData();
    }
    
    public function LoadRelationships(){
        foreach($this->mt as $mtField){
            MtFieldWrapper::getMTTermsforEntity($mtField,'information',$this,$this->information_record_number);
        }        
    }
        
    public function getAll(){
        $entities = $this->Find('');
        return $entities;
    }
    
    public function LoadManagementData(){
        $this->Management = new Management();
        $ok = $this->Management->LoadfromEntityId($this->_table,  $this->information_record_number);
        
            foreach($this->managementFields as $mngField){
                $this->$mngField = $this->Management->$mngField;            
            }
    }

    private function SetManagementData(){
        if($this->Management == null){
            $this->Management = new Management();
        }
        $this->Management->entity_type=$this->_table;
        $this->Management->entity_id=$this->information_record_number;
        foreach($this->managementFields as $mngField){
             $this->Management->$mngField = $this->$mngField ;            
        }
    }
    
    public function SaveManagementData(){

        $this->SetManagementData();
        $this->Management->Save();
    }
    
    private function saveClarifyingNotes(){
            ClariNotes::deleteExistingNotes($this->_table , $this->information_record_number);
        
            foreach($this->clari_notes as $clari_note){
                $clari_note->Save();            
            }
        
    }
    
    protected function getSaveType(){
        if($this->_saved)
        return 'update';
        else
        return 'save';
    }
*/

}
?>
