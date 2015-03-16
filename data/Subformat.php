<?php

class SubformatsModel extends ADODB_Active_Record{

  private $subformat_name;

  public function __construct($subformat_name) {
    global $global;
    $this->db = $global['db'];
    
    $this->subformat_name = $subformat_name;
  }
 
  public function fill_from_post(){
    $subformat_properties = Browse::getEntityFields($this->subformat_name);
    $subformat = new ADODB_Active_Record($this->subformat_name);
    foreach ($subformat_properties as $property) {
      $name = $property['field_name'];
      $subformat->$name = $_POST[$name];
    }
    
    return $subformat;
  }
  
  public function get($entity_id){
    $sql = "SELECT * FROM $this->subformat_name WHERE `record_number` = '$entity_id'";
    $browse = new Browse();
    return $browse->ExecuteQuery($sql);
  }
}

