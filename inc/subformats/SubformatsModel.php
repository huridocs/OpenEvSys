<?php

include_once APPROOT . 'inc/lib_form_util.inc';

class SubformatsModel {

  private $subformat_name;

  public function __construct($subformat_name) {
    $this->subformat_name = $subformat_name;
  }

  public function fill_from_post($view){
    $subformat = new DomainEntity($this->subformat_name);
    $subformat->vocab_number = $_GET['subid'];

    $form = generate_formarray($this->subformat_name, $view);
    form_objects($form, $subformat);

    return $subformat;
  }

  public function get_by_entity($entity_id){
     $browse = new Browse();
     $sql = "SELECT vocab_number FROM `$this->subformat_name` WHERE `record_number` = '$entity_id'";
     $ids = $browse->ExecuteQuery($sql);
     $subformats = array();

     $fields = generate_formarray($this->subformat_name, 'all', false, true);

     foreach ($ids as $id) {
       $subformat =  $this->get_one($id["vocab_number"]);
       $subformat = $this->fill_relations($subformat, $fields);
       array_push($subformats, $subformat);
     }

     return $subformats;
  }

  public function fill_relations($subformat, $fields) {
    foreach ($fields as $field) {
      if(!empty($field['map']['mt'])){

        $value = $subformat->$field['map']['field'];
        $subformat->$field['map']['field'] = $this->get_mt_value($value, $field['map']['mt']);
      }
    }

    return $subformat;
  }

  protected function get_mt_value($values, $mt_index){
    global $conf;
    $locale = $conf['locale'];

    if(!is_array($values)){
      $object = new stdClass();
      $object->vocab_number = $values;
      $values = array($object);
    }

    $browse = new Browse();
    $results = [];

    $table = 'mt_vocab';
    $property = 'english';
    $index_name = 'vocab_number';
    $has_locale = false;

    if($locale !== 'en'){
      $has_locale = true;

      $table_locale = 'mt_vocab_l10n';
      $property_locale = $locale;
      $index_name_locale = 'msgid';
    }

    foreach ($values as $mtObject) {
      if($has_locale) {
          $sql = "SELECT * FROM `$table_locale` WHERE `$index_name_locale` LIKE '".$mtObject->vocab_number."' AND `locale` LIKE '$property_locale'";
          $result = $browse->ExecuteQuery($sql);

          if(!is_null($result)){
            array_push($results, $result[0]['msgstr']);
            continue;
          }
      }

      $sql = "SELECT * FROM `$table` WHERE `$index_name` LIKE '".$mtObject->vocab_number . "'";
      $result = $browse->ExecuteQuery($sql);

      array_push($results, $result[0][$property]);
    }

    return $results;
  }

  public function get_by_id($ids) {
    if(!is_array($ids)) {
      $ids = array($ids);
    }

    $results = array();

    foreach ($ids as $id) {
      array_push($results, $this->get_one($id));
    }

    return $results;
  }

  public function get_one($id){
    $subformat = new DomainEntity($this->subformat_name);
    $subformat->subformat_name = $this->subformat_name;
    $subformat->LoadfromRecordNumber($id);
    $subformat->LoadRelationships();

    return $subformat;
  }

  public function delete($ids) {
    if(!is_array($ids)) {
      $ids = array($ids);
    }

    $browse = new Browse();
    $sql = "DELETE FROM `$this->subformat_name` WHERE `vocab_number` in ('".implode("', '", $ids)."')";
    $browse->ExecuteNonQuery($sql);
  }
}
