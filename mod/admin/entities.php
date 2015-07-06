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

  public function select_options(){
    $entities = $this->get();
    $entities = array_merge($entities, $this->get_subformats());

    foreach($entities as $entity) {
      $entity_name = $entity->value;
      $entity_select_options[$entity_name] = _t($entity_name);
    }

    return $entity_select_options;
  }
}
