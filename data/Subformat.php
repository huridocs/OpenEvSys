<?php

class Subformat extends ADODB_Active_Record{

  private $subformat_name;

  public function __construct($subformat_name, $entity) {
    parent::__construct($subformat_name);
    
    $this->entity = $entity;
    $this->subformat_name = $subformat_name;
  }
  
  public function save_to_entity($entity_id) {
    $this->record_number = $entity_id;
    $this->Save();
  }
  
  public function fill_from_post(){
    $subformat_properties = Browse::getEntityFields($this->subformat_name);
    foreach ($subformat_properties as $property) {
      $name = $property['field_name'];
      $this->$name = $_POST[$name];
    }
  }
  
  public function get(){
    return $this->Load();
  }
}

