<?php

/**
 * DataObject for Event Entity of OpenEvSys.
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

class Event extends DomainEntity {

    public $event_record_number;
    public $event_title;
    public $confidentiality;
    public $initial_date;
    public $initial_date_type;
    public $final_date;
    public $final_date_type;
    public $event_description;
    public $impact_of_event;
    public $remarks;
    public $violation_status;
    //Relasionships
    public $geographical_term;
    public $local_geographical_area;
    public $violation_index;
    public $rights_affected;
    public $huridocs_index;
    public $local_index;
    public $other_thesaurus;
    //protected $Other_thesaurus;
    public $Management;
    //Management

    public $date_received;
    public $date_of_entry;
    public $entered_by;
    public $project_title;
    public $comments;
    public $record_grouping;
    public $date_updated;
    public $updated_by;
    public $monitoring_status;
    public $latitude;
    public $longitude;
    public $supporting_documents;
    //protected $mt =  array('geographical_term' , 'local_geographical_area' , 'violation_index','rights_affected','huridocs_index_terms' , 'local_index' , 'other_thesaurus' );

    protected $managementFields = array('date_received', 'project_title', 'comments', 'record_grouping',
        'monitoring_status', 'entered_by', 'date_of_entry', 'updated_by', 'date_updated');
    protected $supporting_doc = true;
    protected $supporting_geometry = true;
    private $pkey = array('event_record_number');

    public function __construct($table = false, $pkeyarr = false, $db = false, $options = array()) {
        parent::__construct('event', $pkey, $db, $options);
        $table = 'event';


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
      MtFieldWrapper::setMTTermsforEntity( $mtField , 'event' , $this->event_record_number, $this->$mtField);


      }
      }
      $this->saveClarifyingNotes();
      Log::saveLogDetails($this->_table , $this->event_record_number ,  $saveType );

      }



      public function LoadfromRecordNumber($event_record_number){
      $this->Load("event_record_number='$event_record_number'");
      $this->LoadManagementData();
      }

      public function LoadRelationships(){
      foreach($this->mt as $mtField){
      MtFieldWrapper::getMTTermsforEntity($mtField,'event',$this,$this->event_record_number);
      }
      }

      public function getAll(){
      $events = $this->Find('');
      return $events;
      }

      public function LoadManagementData(){
      $this->Management = new Management();
      $ok = $this->Management->LoadfromEntityId($this->_table,  $this->event_record_number);

      foreach($this->managementFields as $mngField){
      $this->$mngField = $this->Management->$mngField;
      }



      }
      private function SetManagementData(){
      if($this->Management == null){
      $this->Management = new Management();
      }
      $this->Management->LoadfromEntityId($this->_table , $this->event_record_number);
      if($this->Management->entity_type == null){
      $new = true;
      }
      $this->Management->entity_type=$this->_table;
      $this->Management->entity_id= $this->event_record_number;
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

      public function LoadSupportingDocs(){

      }

      public function getMtObject( $field , $vocab_number ){


      $entity = $this->_table;
      $keyName = $entity . '_record_number';

      $mtTerm = MtFieldWrapper::getMTObject( $field , $this->_table  );
      $mtTerm->vocab_number = $vocab_number;
      $mtTerm->record_number = $this->$keyName;
      return $mtTerm;
      }

      private function saveClarifyingNotes(){
      ClariNotes::deleteExistingNotes($this->_table , $this->event_record_number);

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

    public function SaveAll() {
        global $global;
        $saveType = $this->getSaveType();

        //var_dump('saving event type' ,  $saveType);
        if ($saveType == 'create') {
            acl_add_event($this->event_record_number);
            //if event is marked as confidential limit it to this user and admin.
            if ($this->confidentiality == 'y')
                acl_set_event_permissions($this->event_record_number);
        }

        if ($saveType == 'update') {
            //if event is marked as confidential limit it to this user and admin.
            if ($this->confidentiality == 'y')
                acl_set_event_permissions($this->event_record_number);
            if ($this->confidentiality == 'n') {
                acl_unset_event_permissions($this->event_record_number);
            }
        }

        parent::SaveAll();
    }

    public function DeleteFromRecordNumber($recordNumber) {
        $return = parent::DeleteFromRecordNumber($recordNumber);

        if ($return) {
            //remove gacl objects for this perticuler event
            acl_delete_event($recordNumber);
        }
    }

}

?>
