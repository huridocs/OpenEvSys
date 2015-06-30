<?php

class Entities extends ADODB_Active_Record {
  function __construct(){
    parent::__construct('gacl_axo');
  }

  public function get(){
    $sql = "SELECT * FROM `gacl_axo` WHERE `section_value` = 'entities' AND `value` != 'document'";
    return $this->Find("`section_value` = 'entities' AND `value` != 'document'");
  }

  public function get_subformats(){
    $sql = "SELECT * FROM `gacl_axo` WHERE `section_value` = 'subformat' AND `value` != 'document'";
    return $this->Find("`section_value` = 'subformat' AND `value` != 'document'");
  }

  public function select_options($subformats = false){
    $entities = $this->get();

    if($subformats){
      $entities = array_merge($entities, $this->get_subformats());
    }

    $entity_select_options = array(
      '' => ''
    );

    foreach($entities as $entity) {
      $entity_name = $entity->value;
      $entity_select_options[$entity_name] = $entity_name;
    }

    return $entity_select_options;
  }
}
