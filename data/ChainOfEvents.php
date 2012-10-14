<?php

/**
 * DataObject For Chain of Events Entity of OpenEvSys.
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

class ChainOfEvents extends DomainEntity{

    public $chain_of_events_record_number;
    public $event;
    public $related_event;
    public $type_of_chain_of_events;
    public $remarks;

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
    
    //protected $mt =  array( );
    
    protected $managementFields = array( 'date_received', 'date_of_entry',
'entered_by' , 'project_title', 'comments', 'record_grouping',
'date_updated' , 'updated_by' , 'monitoring_status');
    

    private $pkey = array('chain_of_events_record_number');
    
    public function __construct($table = false, $pkeyarr=false, $db=false, $options=array()){
        parent::__construct('chain_of_events', $pkey ,$db , $options); 
        

        
        //$this->belongsTo( 'user_profile' , 'username' , 'username' ) ;
        //$this->hasMany( 'mlt_event_geographical_term' , 'record_number' ) ;  
        
    }
/*    
    public function SaveAll(){

        $saveType = $this->getSaveType() ;
        
        $this->Save();
        $this->SaveManagementData();
        foreach($this->mt as $mtField){
            if(sizeof( $this->$mtField) > 0 ){
                MtFieldWrapper::setMTTermsforEntity( $mtField , 'chain_of_events' , $this->chain_of_event_record_number, $this->$mtField);
            
            
            }
        }
        $this->saveClarifyingNotes();

        Log::saveLogDetails($this->_table , $this->chain_of_event_record_number , $saveType );
        
    }
    
    public function LoadfromRecordNumber($chain_of_event_record_number){
        $this->Load("chain_of_event_record_number='$chain_of_event_record_number'");
        $this->LoadManagementData();
    }
    
    public function LoadRelationships(){
        foreach($this->mt as $mtField){
            MtFieldWrapper::getMTTermsforEntity($mtField,'chain_of_events',$this,$this->chain_of_event_record_number);
        }        
    }
        
    public function getAll(){
        $events = $this->Find('');
        return $events;
    }
    
    public function LoadManagementData(){
        $this->Management = new Management();
        $ok = $this->Management->LoadfromEntityId($this->_table,  $this->chain_of_event_record_number);
        
            foreach($this->managementFields as $mngField){
                $this->$mngField = $this->Management->$mngField;            
            }
        
        

    }
    private function SetManagementData(){
        if($this->Management == null){
            $this->Management = new Management();
        }
        $this->Management->entity_type=$this->_table;
        $this->Management->entity_id=$this->chain_of_event_record_number;
        foreach($this->managementFields as $mngField){
             $this->Management->$mngField = $this->$mngField ;            
        }
    }
    
    public function SaveManagementData(){

        $this->SetManagementData();
        $this->Management->Save();
    }


    private function saveClarifyingNotes(){
            ClariNotes::deleteExistingNotes($this->_table , $this->chain_of_event_record_number);
        
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
