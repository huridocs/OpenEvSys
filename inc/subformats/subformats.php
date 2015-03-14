<?php

// Helper class to create and manage subformats

class Subformats {
  public function __construct() {
    global $global;
    $this->db = $global['db'];
  }

  public function create($entity_name, $subformat_name, $subformat_label){

    $create_entity_table = "CREATE  TABLE IF NOT EXISTS  `" . $subformat_name . "` (
                            `vocab_number` MEDIUMINT NOT NULL AUTO_INCREMENT,
                            PRIMARY KEY (`vocab_number`)
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    $this->db->Execute($create_entity_table);

    $create_relation_table = "CREATE  TABLE IF NOT EXISTS  `mlt_" . $entity_name . "_" . $subformat_name . "` (
                    `vocab_number` MEDIUMINT NOT NULL ,
                    `record_number` VARCHAR(45) NOT NULL ,
                    PRIMARY KEY (`vocab_number`, `record_number`) ,
                    FOREIGN KEY (`vocab_number` )
                    REFERENCES  `" . $subformat_name . "` (`vocab_number` )
                    ON DELETE RESTRICT
                    ON UPDATE CASCADE,
                    FOREIGN KEY (`record_number` )
                    REFERENCES  `" . $entity_name . "` (`" . get_primary_key($entity_name) . "` )
                    ON DELETE CASCADE
                    ON UPDATE CASCADE)ENGINE = InnoDB;";
    $this->db->Execute($create_relation_table);

    $id = $this->get_last_subformat_id();

    $create_entity = "INSERT INTO `gacl_axo` (`id`, `section_value`, `value`, `order_value`, `name`, `hidden`)
                                                                            VALUES('". $id ."', 'subformat', '". $subformat_name . "', 15, '". $subformat_label . "', 0)";
    $this->db->Execute($create_entity);

    $permisions = "INSERT INTO `gacl_axo_map` (`acl_id` ,`section_value` ,`value`) VALUES ('19',  'subformat', '". $subformat_name . "')";
    $this->db->Execute($permisions);
  }


  protected function get_last_subformat_id(){
    $id = 0;
    $sql = "SELECT `id` FROM `gacl_axo` WHERE `section_value` = 'subformat' ORDER BY `id` DESC LIMIT 1";
    $result = $this->db->Execute($sql);

    if(!empty($result->fields)){
      $id = intval($result->fields["id"]) + 1;
    }

    return $id;
  }
}

?>
