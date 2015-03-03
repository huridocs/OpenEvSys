<?php

class Entities extends ADODB_Active_Record {
	function __construct(){
		parent::__construct('gacl_axo');
	}

	public function get(){
		$sql = "SELECT * FROM `gacl_axo` WHERE `section_value` = 'entities' AND `value` != 'document'";
		return $this->Find("`section_value` = 'entities' AND `value` != 'document'");
	}

	public function select_options(){
		$entity_select_options = array(
			'' => ''
		);

		foreach($this->get() as $entity) {
			$entity_name = $entity->value;
			$entity_select_options[$entity_name] = _t(strtoupper($entity_name));
		}

		return $entity_select_options;
	}
}
