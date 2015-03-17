<?php

class SubformatsModel {

  private $subformat_name;

  public function __construct($subformat_name) {
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
    $browse = new Browse();
    $sql = "SELECT * FROM $this->subformat_name WHERE `record_number` = '$entity_id'";
    return $browse->ExecuteQuery($sql);
  }
  
  public function get_by_id($ids) {
    if(!is_array($ids)) {
      $ids = array($ids);
    }
    
    $browse = new Browse();
    $sql = "SELECT * FROM $this->subformat_name WHERE `vocab_number` in (".implode(", ", $ids).")";
    return $browse->ExecuteQuery($sql);
  }


  public function delete($id) {
    $browse = new Browse();
    $sql = "DELETE FROM $this->subformat_name WHERE `vocab_number` = '$id'";
    $browse->ExecuteNonQuery($sql);
  }
}

