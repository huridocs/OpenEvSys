<?php

class SecEntity{
	
	private $browse;
	public function __construct(){
		$this->browse = new Browse();
		
		
	}

	//
    /**
     * Returns the list of secondary entities 
     * @param $sec_entity
     * @return ResultSet
     */
    public function getSecondaryEntities(){
        global $global;
        $sql = "SELECT entity FROM sec_entity";
        //echo $entity_list_sql;
        $entities = $global['db']->GetCol($sql);
        return $entities;
    }


	//
    /**
     * Returns the list of entities building up the secondary entity $sec_entity
     * @param $sec_entity
     * @return ResultSet
     */
    public function getEntityList($sec_entity){
        $entity_list_sql = "SELECT entity FROM sec_entity_entities  
        WHERE sec_entity='$sec_entity' ORDER BY entity_key ";
        //echo $entity_list_sql;
        $sec_entity_composition = $this->browse->ExecuteQuery($entity_list_sql);
        return $sec_entity_composition;
    }
	
    
	// sec_entity - source, victim, perpetrator , intervening_party
	// $option    - browse, visible_search , visible_search_display 
	
    
	/**
	 * Returns the field list for each entity (entity , field) of the secondary entity $sec_entity that should be available when the view type is $option
	 * @param $sec_entity - Secondary entity name
	 * @param $option - view type
	 * @return ResultSet
	 */
	public function getFieldList($sec_entity , $option ){
		$entity_list_sql = "SELECT field_name , entity FROM sec_entity_fields AS F 
		JOIN sec_entity_entities AS E ON F.entity_key=E.entity_key 
		WHERE sec_entity='$sec_entity' AND field_option='$option' ";
		//echo $entity_list_sql;
        $sec_entity_composition = $this->browse->ExecuteQuery($entity_list_sql);
        return $sec_entity_composition;
	}
	
	
   /**
    * Returns the field list for the required entity ( $entity) of the secondary entity $sec_entity that should be available when the view type is $option
    * @param $sec_entity
    * @param $option
    * @param $entity
    * @return unknown_type
    */
   public function getFieldListForEntity($sec_entity ,  $option , $entity ){
        $entity_list_sql = "SELECT field_name FROM sec_entity_fields AS F 
        JOIN sec_entity_entities AS E ON F.entity_key=E.entity_key 
        WHERE sec_entity='$sec_entity' AND field_option='$option' AND entity='$entity' ";
        //echo $entity_list_sql;
        $sec_entity_composition = $this->browse->ExecuteQuery($entity_list_sql);
        return $sec_entity_composition;
    }
    
	
	/**
	 * Add the field ($field_name) to be displayed for view option ($option) for entity ($entity) of the secondary entity($sec_entity)
	 * @param $sec_entity - Secondary entity to which the field is added
	 * @param $entity  - the entity of the field being added
	 * @param $field_name - the field being added
	 * @param $option - view type 
	 * @return 
	 */
	public function addSecEntityField($sec_entity, $entity ,$field_name, $option ){
		
		$existing_sec_entity_sql = "SELECT * FROM sec_entity_entities WHERE sec_entity='$sec_entity' AND entity='$entity' ";
		$existing_sec_entity_rows =  $this->browse->ExecuteQuery($existing_sec_entity_sql);
		
		//var_dump('existing_sec_entity' , $existing_sec_entity_rows);
		
		if( count( $existing_sec_entity_rows ) ==  0){
			$sec_entity_array = array('sec_entity'=>$sec_entity , 'entity'=>$entity );
			$this->browse->AutoExecute('sec_entity_entities' , $sec_entity_array , 'INSERT' );
			
		    $entity_id = $this->browse->GetInsertID();
			
		}else{
			$entity_id = $existing_sec_entity_rows[0]['entity_key'];
		}
		//var_dump('sec entity id' , $entity_id);
		
		//insert field
		
		$existing_sec_fields_sql = "SELECT * FROM sec_entity_fields WHERE field_name='$field_name' AND field_option='$option' AND entity_key='$entity_id'  ";
		$existing_sec_fields_rows = $this->browse->ExecuteQuery($existing_sec_fields_sql); 
		
		if(count($existing_sec_fields_rows  ) ==0 ){
            $sec_entity_field_array = array('entity_key'=>$entity_id , 'field_name'=>$field_name , 'field_option'=>$option  );
            $this->browse->AutoExecute('sec_entity_fields' , $sec_entity_field_array , 'INSERT');
		}    
	}
	
   public function removeSecEntityField($sec_entity, $entity ,$field_name, $option ){
   	
   	    $entityKeySelectSql = "SELECT entity_key FROM sec_entity_entities WHERE sec_entity='$sec_entity' AND entity = '$entity'";
   	    $entityKeyRS = $this->browse->ExecuteQuery($entityKeySelectSql);
   	    //var_dump('entityKeyRs',$entityKeyRS);
   	    $entity_key = $entityKeyRS[0]['entity_key'];
        $deleteSql = "DELETE FROM sec_entity_fields WHERE entity_key='$entity_key' AND field_name='$field_name' AND field_option='$option' ";
        $deleteSql;
        $this->browse->ExecuteNonQuery($deleteSql);
            
    }
	
	
	public function getFormArray($secEntity , $option){
		include_once APPROOT.'inc/lib_form_util.inc';
		
		$entities = $this->getEntityList($secEntity );
		$formArrays = array();
		//var_dump('entities',$entities);
		foreach($entities as $entity){ // get form array for each entity in secondary entities
			$formArray = null;
			$formArray = generate_formarray($entity['entity'], null, true);			
			//var_dump('formArray Ori' , $formArray);
			$entityFieldList = array();
			$entityFieldListRS = $this->getFieldListForEntity($secEntity,$option,$entity['entity']);
			foreach($entityFieldListRS  as $entityField){
				$entityFieldList[] = $entityField['field_name'];
			}
			
			//var_dump('entity field list', $entityFieldList);
			$unsetKeys = array();
			foreach($formArray as $key => $field){
				if(  in_array( $field['map']['field'] , $entityFieldList) == false ){					
				    $unsetKeys[] = $key;	
				}
			}
			
			foreach($unsetKeys as $unsetKey){
				unset( $formArray[$unsetKey] );  
			}
			//var_dump('formArray',$formArray);
			foreach($formArray as $fieldArray ){
                $formArrays[] = $fieldArray;
			}
			
			
		}
		
		//var_dump($formArrays);
		return $formArrays;
		
		
	}
	
	
	
	
}
