<?php

class Subformat extends ADODB_Active_Record{
  
  public function __construct($subformat, $entity) {
    $this->subformat = $subformat;
    $this->entity = $entity;
    
    parent::__construct($this->table());
  }
  
  public function get(){
    return $this->Load();
  }
  
  public function table(){
    return $this->entity . "_" . $this->subformat;
  }
}

