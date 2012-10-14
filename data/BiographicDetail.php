<?php
/**
 * DataObject For Biographical Detail Entity of OpenEvSys.
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

class BiographicDetail extends DomainEntity{
    
    public $biographic_details_record_number ;
    public $person;
    public $related_person;
   
    public $confidentiality;
    public $type_of_relationship;
    
    public $initial_date;
    public $initial_date_type;
    public $final_date;
    public $final_date_type;
    public $education_and_training;
    public $employment;
    public $affiliation;
    public $position_in_organisation;
    

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
    
    protected $managementFields = array( 'date_received',  'project_title', 'comments', 'record_grouping',
 'monitoring_status' , 'date_of_entry', 'entered_by' , 'date_updated' , 'updated_by' );
    

    private $pkey = array('biographic_details_record_number');
    
    public function __construct($table = false, $pkeyarr=false, $db=false, $options=array()){
        parent::__construct('biographic_details', $pkey ,$db , $options); 
        $table = 'biographic_details';

        
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
                MtFieldWrapper::setMTTermsforEntity( $mtField , 'biographic_details' , $this->biographic_details_record_number, $this->$mtField);
            
            
            }
        }
        $this->saveClarifyingNotes();
        Log::saveLogDetails($this->_table , $this->biographic_details_record_number , $saveType );
    }
    
    public function LoadfromRecordNumber($biographic_details_record_number){
        $this->Load("biographic_details_record_number='$biographic_details_record_number'");
        $this->LoadManagementData();
    }
    
    public function LoadfromPerson($person_record_number){
        $this->Load("person='$person_record_number'");
        $this->LoadManagementData();
    }
    
    public function LoadRelationships(){
        foreach($this->mt as $mtField){
            MtFieldWrapper::getMTTermsforEntity($mtField,'biographic_details',$this,$this->biographic_details_record_number);
        }        
    }
        
    public function getAll(){
        $biographic_details = $this->Find('');
        return $biographic_details;
    }
    public function LoadManagementData(){
        $this->Management = new Management();
        $ok = $this->Management->LoadfromEntityId($this->_table,  $this->biographic_details_record_number);
        
            foreach($this->managementFields as $mngField){
                $this->$mngField = $this->Management->$mngField;            
            }
        
        

    }
    private function SetManagementData(){
        if($this->Management == null){
            $this->Management = new Management();
        }
        $this->Management->LoadfromEntityId($this->_table , $this->biographic_details_record_number);
        if($this->Management->entity_type == null){
            $new = true;        
        }
        $this->Management->entity_type=$this->_table;
        $this->Management->entity_id= $this->biographic_details_record_number;    
        foreach($this->managementFields as $mngField){          
             $this->Management->$mngField = $this->$mngField ;            
        }
        if($new==true){
            //var_dump('new');
            $this->Management->entered_by = $_SESSION['username'];
            $this->Management->date_of_entry = date( 'Y-m-d H:i:s', time() );
        }else{
            //var_dump('old');
            $this->Management->updated_by = $_SESSION['username'];
            $this->Management->date_updated = date( 'Y-m-d H:i:s', time() );
        }
        
    }
    
    public function SaveManagementData(){

        $this->SetManagementData();
        $this->Management->Save();
    }


    private function saveClarifyingNotes(){
            ClariNotes::deleteExistingNotes($this->_table , $this->biographic_details_record_number);
        
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

    public function LoadfromPerson($person_record_number){
        $this->Load("person='$person_record_number'");
        $this->LoadManagementData();
    }



   
    
    
}
?>
