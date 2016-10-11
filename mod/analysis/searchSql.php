<?php

class SearchResultGenerator {

    private $searchQuery;
    private $sqlArray;
    private $entityArray;

    public function __construct() {

        //create the form object for the entity so that the meta data of the fields are know
        //to create the SQL statement as required
    }

    public function sqlForJsonQuery($json) {
        global $global;
        include_once 'search_query.inc';
        $searchQueryO = new SearchQuery();
        $searchQueryO->initFromJson($json);
        $queryArray = $searchQueryO->getQueryArray();
        $this->searchQuery = $queryArray;

        //var_dump('searchQuery',$queryArray);

        $this->generateSqlArray();
        $sql = $this->getSql();
        return $sql;
    }

    public function generateSqlArray() {

        $this->sqlArray = array();
        //get the Query Array
        //iterate through the conditions.
        $lastEntity = null;
        $entityForm = null;

        foreach ($this->searchQuery['conditions'] as $condition) {


            $nowEntity = $condition['entity'];
            if ($lastEntity == null) {
                $this->sqlArray['from'] = $this->tableOfEntity($nowEntity);
                $this->addGroupBy($nowEntity);
            } else if ($lastEntity != $condition['entity']) {
                $this->entityJoin($lastEntity, $nowEntity);
                $this->addGroupBy($nowEntity);
            } else {

            }

            $entityForm = $this->getFormArrayForEntity($nowEntity);
            $this->generateCondition($condition, $entityForm);
            $lastEntity = $nowEntity;
        }

        //if the entity is same as before
        //  continue adding the condition for the field.
        //if the entity is different,
        //  add the needed join
        //  add the condition for the comparison.

        if (is_array($this->searchQuery['group_by']))
            if (count($this->searchQuery['group_by']) > 0) {
                $this->searchQuery['select'] = null;
            }

        //iterate through the view results
        foreach ($this->searchQuery['select'] as $viewValue) {
            $this->createResult($viewValue['entity'], $viewValue['field'], 'view');
        }


        //if the view entity field is not added, add it.
        //var_dump('sqlArray_Select',$this->sqlArray);
        //var_dump($this->searchQuery['group_by']);
        if (is_array($this->searchQuery['group_by']))
            if (count($this->searchQuery['group_by']) > 0) {
                $this->sqlArray['select'] = null;
                $this->sqlArray['groupby'] = null;
                $this->sqlArray['select'][] = ' COUNT(*) as count ';

                foreach ($this->searchQuery['group_by'] as $groupByValue) {
                    $this->generateGroupBy($groupByValue['entity'], $groupByValue['field'], $groupByValue['type']);
                }
            }

        //var_dump('sqlArray' , $this->sqlArray);
        //ORDER BY
        $this->generateOrderby();
    }

    public function getSql() {
        $sql = array();
        $sqlStatementResult = $this->sqlArrayToSql($this->sqlArray);

        $this->sqlArray['select'] = array();
        $this->sqlArray['select'][] = 'COUNT(*)';
        $sqlStatementCount = $this->sqlArrayToSql(($this->sqlArray));
        $sql['result'] = $sqlStatementResult;
        $sql['count'] = $sqlStatementCount;
        //print $sqlStatementResult;
        //print $sqlStatementCount;
        return $sql;
    }

    private function generateCondition($condition, $entityForm) {
        $fieldName = $condition['field'];
        $fieldValue = $condition['value'];
        $fieldArray = $entityForm[$fieldName];

        if (is_management_field($fieldArray)) {
            //$fieldArray['map']['entity'] = 'management';
            //*** IMPLEMENT management join
        }

        //var_dump('fieldArray' , $fieldArray['type'] );

        switch ($fieldArray['type']) {
            case 'hidden' :
                $conditionString = $this->generateComparison('text', $condition, $fieldArray);
                break;
            case 'textarea':
                $conditionString = $this->generateComparison('text', $condition, $fieldArray);
                break;
            case 'text' :
                $conditionString = $this->generateComparison('text', $condition, $fieldArray);
                break;
            case 'mt_select' :
                $conditionString = $this->generateComparison('mt_select', $condition, $fieldArray);
                //return $condition;
                break;
            case 'mt_tree':
                $conditionString = $this->generateComparison('mt_tree', $condition, $fieldArray);
                //return $condition;
                break;
            case 'date':
                $conditionString = $this->generateComparison('date', $condition, $fieldArray);
                //return $condition;
                break;
            case 'document' :

                break;
            case 'upload':

                break;

            case 'checkbox':
                $conditionString = $this->generateComparison('checkbox', $condition, $fieldArray);
                break;
            case 'radio':
                $conditionString = $this->generateComparison('checkbox', $condition, $fieldArray);
                break;
            case 'address' :
                $conditionString = null;
                break;
            case 'user_select' :
                $conditionString = $this->generateComparison('text', $condition, $fieldArray);
                break;
        }
        //var_dump('$conditionString' , $conditionString);
        if (!($conditionString == null || trim($conditionString)) == '') {
            $condArray = array();
            $condArray['condition'] = $conditionString;
            $condArray['operator'] = $condition['link'];
            $this->sqlArray['where'][] = $condArray;
        }

        //return  $condition;
    }

    private function generateComparison($type, $condition, $fieldArray) {
        global $global;
        //$type can be 'text' , date, mt ,
        //based on the operator of search and datatype, the where criteria has to be developed here
        $value = $condition['value'];
        $value = addslashes($value);
        $fieldAlias = $this->getFieldAlias($fieldArray, 'query', $condition['entity']);
        switch ($type) {
            case 'text':
                switch (trim($condition['operator'])) {
                    case 'like':
                        $conditionString = $fieldAlias . " LIKE  '$value' ";
                        break;
                    case 'not_like':
                        $conditionString = $fieldAlias . " LIKE  '$value' ";
                        break;
                    case '=':
                        $conditionString = $fieldAlias . " =  '$value' ";
                        break;
                    case '<':
                        $conditionString = $fieldAlias . " <  '$value' ";
                        break;
                    case '>':
                        $conditionString = $fieldAlias . " >  '$value' ";
                        break;
                    case '<=':
                        $conditionString = $fieldAlias . " <=  '$value' ";
                        break;
                    case '>=':
                        $conditionString = $fieldAlias . " >=  '$value' ";
                        break;

                    /* case 'not_=':
                      $conditionString =  'NOT '. $fieldAlias . " =  '$value' " ;
                      break; */
                    case 'soundex':
                        $conditionString = ' SOUNDEX(' . $fieldAlias . ")  LIKE concat( '%', SOUNDEX( '$value' ) , '%' ) ";
                        break;
                    case 'regex':
                        if (!($value == null || trim($value) == '' )) {
                            $conditionString = " $fieldAlias  REGEXP '$value' ";
                        }
                        break;
                    case 'contains':
                        $conditionString = $fieldAlias . " LIKE  '%$value%' ";
                        break;
                    /*    				case 'not_contains':
                      $conditionString = 'NOT '. $fieldAlias . " LIKE  '%$value%' " ;
                      break; */
                    default:
                        $conditionString = $fieldAlias . " LIKE  '%$value%' ";
                }
                break;
            case 'date':

                switch ($condition['operator']) {
                    case 'before':
                        $conditionString = " $fieldAlias  < '$value' ";
                        break;
                    case 'after':
                        $conditionString = " $fieldAlias  > '$value' ";
                        break;
                    case 'at_or_before':
                        $conditionString = " $fieldAlias  <= '$value' ";
                        break;
                    case 'at_or_after':
                        $conditionString = " $fieldAlias  >= '$value' ";
                        break;
                    case 'between':
                        $date1 = trim(strtok($value, ','));
                        $date2 = trim(strtok(','));
                        $conditionString = "  ('$date1' < $fieldAlias  AND $fieldAlias  < '$date2' )";
                        break;
                    case 'not_between':
                        $date1 = trim(strtok($value, ','));
                        $date2 = trim(strtok(','));
                        $conditionString = "  ('$date1' < $fieldAlias  AND $fieldAlias  < '$date2' ) ";
                        break;
                    case 'not_=':
                        $conditionString = $fieldAlias . " =  '$value' ";
                        break;
                    default:
                        $conditionString = $fieldAlias . " =  '$value' ";
                }
                if (trim($value) == '' || $value == null) {
                    $conditionString = null;
                }
                break;
            case 'mt_tree':
                switch ($condition['operator']) {
                    case 'sub':
                        //var_dump($value);exit;


                        $sql = "SELECT max(m.term_level) from mt_vocab m WHERE m.list_code = (select m2.list_code from mt_vocab m2 where vocab_number ='$value')";
                        $depth = (int) $global['db']->GetOne($sql);

                        $sql = array();
                        $sqllevel = "'$value'";
                        $vocList = array();

                        if ($depth) {
                            for ($i = 1; $i <= $depth; $i++) {
                                $sqllevel = "select vocab_number from  mt_vocab mt$i where  mt$i.parent_vocab_number in (" . $sqllevel . ")";
                                $sql[] = $sqllevel;
                            }
                            //$sql = "select GROUP_CONCAT(DISTINCT vocab_number SEPARATOR \"' , '\") from  (".implode(" union ", $sql).") a ";
                            $sql = "select vocab_number from  (" . implode(" union ", $sql) . ") a ";
                            //$vocList = $global['db']->GetOne($sql);
                            $res = $global['db']->Execute($sql);
                            foreach ($res as $v) {
                                $vocList[] = "'" . $v['vocab_number'] . "'";
                            }
                        }
                        $vocList[] = "'$value'";
                        $conditionString = " $fieldAlias  in (" . implode(",", $vocList) . ") ";

                        break;
                    case 'subin':
                        //var_dump($value);exit;
                        $values = explode(",", $value);
                        $vocList = array();
                        $sql = array();

                        foreach ($values as $value) {
                            $sqlq = "SELECT max(m.term_level) from mt_vocab m WHERE m.list_code = (select m2.list_code from mt_vocab m2 where vocab_number ='$value')";
                            $depth = (int) $global['db']->GetOne($sqlq);

                            $sqllevel = "'$value'";

                            if ($depth) {
                                for ($i = 1; $i <= $depth; $i++) {
                                    $sqllevel = "select vocab_number from  mt_vocab mt$i where  mt$i.parent_vocab_number in (" . $sqllevel . ")";
                                    $sql[] = $sqllevel;
                                }
                                //$sql = "select GROUP_CONCAT(DISTINCT vocab_number SEPARATOR \"' , '\") from  (".implode(" union ", $sql).") a ";
                            }
                            $vocList[] = "'$value'";
                        }
                        if ($sql) {
                            $sql = "select vocab_number from  (" . implode(" union ", $sql) . ") a ";
                            //$vocList = $global['db']->GetOne($sql);
                            $res = $global['db']->Execute($sql);
                            foreach ($res as $v) {
                                $vocList[] = "'" . $v['vocab_number'] . "'";
                            }
                        }
                        if ($vocList) {
                            $conditionString = " $fieldAlias  in (" . implode(",", $vocList) . ") ";
                        }
                        break;
                    case '=':
                        $conditionString = " $fieldAlias = '$value'";
                        break;
                    default :
                        $conditionString = " $fieldAlias = '$value'";
                }
                break;
            case 'mt_select':
                switch ($condition['operator']) {
                    case '=':
                        $conditionString = " $fieldAlias = '$value'";
                        break;
                    case 'in':
                        $conditionString = " $fieldAlias in($value)";
                        break;
                    default:
                        $conditionString = " $fieldAlias = '$value'";
                }
                break;
            case 'checkbox':

                $compValue = "'n' , '0'";
                if (strtolower($value) == 'y') {
                    $compValue = "'y' , '1' ";
                }
                //$condition = $fieldArray['map']['entity'] .'.'. $fieldArray['map']['field'] . " IN ( $compValue)" ;
                $conditionString = " $fieldAlias IN ( $compValue )";
                break;
        }
        if (substr($condition['operator'], 0, 4) == 'not_') {
            if (!($conditionString == null || trim($conditionString) == '')) {
                $conditionString = ' NOT ' . $conditionString;
            }
        }

        if ($condition['operator'] == 'empty') {
            $conditionString = "isnull( $fieldAlias )";
        }

        return $conditionString;
    }

    private function generateGroupBy($entity, $fieldName, $type = null) {
        $entityForm = $this->getEntityArray($entity);
        //var_dump('fieldArray type ' , $fieldArray );
        //$fieldName = $condition['field'];
        $fieldArray = $entityForm[$fieldName];

        $fieldAlias = $this->getFieldAlias($fieldArray, 'query', $entity);
        $fieldAliasView = $this->getFieldAlias($fieldArray, 'view', $entity, true);
        //var_dump('entity' , $entity);
        //var_dump('Alias' , $fieldAlias);
        //var_dump('AliasView' , $fieldAliasView);
        //var_dump('fieldArray' , $fieldArray);
        switch ($fieldArray['type']) {
            case 'hidden' :
            case 'textarea':
            case 'text' :
                $conditionString = $fieldAlias;
                $this->sqlArray['select'][] = $conditionString . "  $fieldAliasView";
                break;
            case 'mt_select' :
                $conditionString = $fieldAlias;
                $fieldAliasView = $this->getFieldAlias($fieldArray, 'field', $entity, false);
                $this->sqlArray['select'][] = "  $fieldAliasView";
                //var_dump('AliasView_mtSelect' , $fieldAliasView);
                //return $condition;
                break;
            case 'mt_tree':

                switch (trim($type)) {
                    case '1':
                        $length = 2 * 1;
                        break;
                    case '2':
                        $length = 2 * 2;
                        break;
                    case '3':
                        $length = 2 * 3;
                        break;
                    case '4':
                        $length = 2 * 4;
                        break;
                    case '5':
                        $length = 2 * 5;
                        break;
                    case '6':
                        $length = 2 * 6;
                        break;
                }
                $fieldAliasView = $this->getFieldAlias($fieldArray, 'field', $entity);
                //$conditionString = "  SUBSTR( $fieldAlias , -14 , $length )  ";
                $conditionString = $fieldAlias;
                //var_dump('fieldAliasView' , $fieldAliasView);
                //var_dump('fieldAlias' , $fieldAlias);
                $this->sqlArray['select'][] = "  $fieldAliasView";
                //return $condition;
                break;
            case 'date':
                switch ($type) {
                    case 'monthly':
                        $conditionString = " (CONCAT( LPAD( MONTH($fieldAlias),2,'0' ) , '-' , YEAR($fieldAlias))) ";
                        $this->sqlArray['select'][] = $conditionString . "  $fieldAliasView";
                        //$this->sqlArray['select'][] = " COUNT( $conditionString )  {$fieldAliasView}_count ";
                        break;
                    case 'yearly':
                        $conditionString = "  YEAR($fieldAlias) ";
                        $this->sqlArray['select'][] = $conditionString . " $fieldAliasView";
                        //$this->sqlArray['select'][] = " COUNT( $conditionString )  {$fieldAliasView}_count ";
                        break;
                    default :
                        $conditionString = " (CONCAT( LPAD( DAY($fieldAlias),2,'0' ) , '-' ,  LPAD( MONTH($fieldAlias),2,'0' ) , '-' , YEAR($fieldAlias))) ";
                        $this->sqlArray['select'][] = $conditionString . "  $fieldAliasView";
                        //$this->sqlArray['select'][] = " COUNT( $conditionString )  {$fieldAliasView}_count ";
                        break;
                }
                //return $condition;
                break;
            case 'radio':
            case 'checkbox':
                $conditionString = $entity . '.' . $fieldArray['map']['field'];
                $this->sqlArray['select'][] = $conditionString . " $fieldAliasView";
                break;
        }
        //var_dump('sqlArray' , $this->sqlArray['select'] );
        //var_dump('conditionString' , $conditionString );

        $this->sqlArray['groupby'][] = $conditionString;
        //return  $condition;
    }

    public function generateOrderby() {
        if (isset($_GET['iSortCol_0']) && isset($_GET['iSortCol_0']) && isset($this->searchQuery['select'][$_GET['iSortCol_0']])) {
            $orderfield = $this->searchQuery['select'][$_GET['iSortCol_0']];
            $orderDesc = "asc";
            if (isset($_GET['sSortDir_0'])) {
                $orderDesc = $_GET['sSortDir_0'];
            }

            $entity = $orderfield['entity'];
            $fieldName = $orderfield['field'];
            $entityForm = $this->getEntityArray($entity);
            $fieldArray = $entityForm[$fieldName];
            $fieldAlias = $this->getFieldAlias($fieldArray, 'query', $entity);

            switch ($orderDesc) {
                case 'desc':
                    $sd = ' DESC ';
                    break;
                case 'asc':
                    $sd = ' ASC ';
                    break;
                default:
                    $sd = ' ASC ';
            }

            $this->sqlArray['orderby'][] = "$fieldAlias $sd";
        }
    }

    public function getFieldAlias($formField, $type = 'query', $entityType = null, $onlyAlias = false) {
        //$type can be 'query' or 'view'
        //if there are multiple instances of the same entity , each different entity should have different aliases.

        $entityField = null; // field name used for entity

        if ($this->isPersonExtention($entityType) == true) {
            $formField['map']['entity'] = $entityType;
        }
        $entityField = $formField['map']['entity'];

        if ($formField['map']['mlt']) {
            $fieldSqlArray = $this->mtJoin($formField, $type);
            $entityField = $fieldSqlArray['field'];
            $entityFieldAs = $fieldSqlArray['as'];

            if ($type == 'view' || $type == 'field') {
                $as = $entityFieldAs;
            }

            if ($onlyAlias == false) {
                return $entityField . $as;
            } else {
                //var_dump('onlyAlias' , $as );
                return $entityFieldAs;
            }


            //return $fieldName;
        }
        if (is_management_field($formField)) {
            //*** IMPLEMENT if the management field has not being joined, join it.
            $this->managementJoin($formField['map']['entity'], $formField['map']['field']);
            //if($type == 'view'){
            //	$as = ' AS ' . $formField['map']['entity'].'_'. $formField['map']['field'];
            //}
            //$formField['map']['entity'] = $formField['map']['entity'] . '_management' ;
            $entityField = $formField['map']['entity'] . '_management';


            //var_dump( $formField['map']['entity'].'_management'.'.'. $formField['map']['field'] . $as );
            //return $formField['map']['entity'].'_management'.'.'. $formField['map']['field'] . $as ;
        }

        if ($this->isPersonExtention($entityType) == true) {
            //var_dump('came to personExtension');
            $entityField = $this->setPersonSecondaryEntity($entityType);
            //var_dump('personAs' , $asReturn);
//    		if($type == 'view' ){
//    			$as = ' AS ' . $asReturn.'_'. $formField['map']['field'];
//    		}
//    		return $asReturn . '.' . $formField['map']['field'] . $as;
        }

        if ($formField['map']['entity'] == 'person' && $formField['map']['field'] == 'person_addresses') {
            //$sqlArray['select'][] = 'address.*';
            //$sqlArray['join'][] = array('table'=>'address' , 'jointype'=>'LEFT' , 'field1'=>'person_record_number', 'field2' =>'person'  , 'as'=>null );
            return null;
        }

        if ($this->tableOfEntity($formField['map']['entity']) == 'person' && $this->tableOfEntity($formField['map']['field']) == 'addresses') {

        }

        if ($formField['type'] == 'document') {
            return null;
        }
        if ($formField['type'] == 'upload') {
            return null;
        }

        $entity = $formField["map"]["entity"];
        $field = $formField["map"]["field"];


        if (is_location_field($entity,$field)){
            return "concat($entity.{$field}_longitude,',',$entity.{$field}_latitude) AS $entity"."_"."$field";
        }


        if ($type == 'view') {
            $as = ' AS ' . $formField['map']['entity'] . '_' . $formField['map']['field'];
        } else if ($type == 'field') {  // called only when the field is a mt_tree.
            $as = ' AS ' . $formField['map']['entity'] . '_' . $formField['map']['field'];
        }
        if ($onlyAlias == false && !is_null($entityField)) {
            return $entityField . '.' . $formField['map']['field'] . $as;
        } else {
            //var_dump('onlyAlias' , $type , $as );
            return $as;
        }
    }

    public function createResult($entity, $field, $type) {
        $entityArray = $this->getEntityArray($entity);

        $selectField = $this->getFieldAlias($entityArray[$field], $type, $entity);
        //var_dump('selectField' , $entityArray[$field]);
        if (($selectField == null || $selectField == '' )) {
             error_log(var_export($field,true));
        }

        if (!($selectField == null || $selectField == '' )) {
            $this->sqlArray['select'][$selectField] = $selectField;
        }
    }

    private function addGroupBy($entity) {
        $entityArray = $this->getEntityArray($entity);
        $pkField = get_primary_key($this->tableOfEntity($entity));
        $groupBy = $this->getFieldAlias($entityArray[$pkField], 'query', $entity);

        if(!is_null($groupBy)){
          $this->sqlArray['groupby'][] = $groupBy;
        }
    }

    private function mtJoin($formField, $type) {
        $entity_type = $formField['map']['entity'];
        $fieldName = $formField['map']['field'];

        $mltTable = 'mlt_' . $this->tableOfEntity($entity_type) . '_' . $fieldName;
        $as = trim($entity_type) . '_' . $mltTable;

        $sameMltPresent = false;
        foreach ($this->sqlArray['join'] as $joinSqlElement) {
            if ($joinSqlElement['as'] == $as) {
                $sameMltPresent = true;
                break;
            }
        }

        $columnName = 'vocab_number';
        if($formField['type'] == 'user_select'){
            $columnName = 'username';
        }

        if ($sameMltPresent == false) {
            $this->sqlArray['join'][] = array('table' => $mltTable, 'jointype' => 'LEFT', 'field1' => 'record_number', 'field2' => $entity_type . '.' . get_primary_key($entity_type), 'entity1' => $as, 'as' => $as);
        }
        $fieldSqlName = array();

        if ($type == 'view') {
            $fieldSqlName = array('field' => "GROUP_CONCAT(DISTINCT $as.$columnName ORDER BY $as.$columnName ASC SEPARATOR ' , ') ", 'as' => " AS {$entity_type}_{$fieldName}");
            return $fieldSqlName;
        } else {
            $fieldSqlName = array('field' => $as .'.'. $columnName, 'as' => " AS {$entity_type}_{$fieldName}");
            return $fieldSqlName;
        }
    }

    public function managementJoin($entity) {
        $as = $entity . '_management';
        $join = true;
        foreach ($this->sqlArray['join'] as $joinField) {
            if ($joinField['as'] == $as) {
                $join = false;
                break;
            }
        }
        if ($join) {
            $this->sqlArray['join'][] = array('table' => 'management', 'jointype' => 'LEFT', 'field1' => $as.'.entity_id', 'field2' => get_primary_key($entity), 'as' => $as, 'condition' => " AND $as.entity_type ='$entity' ");
        }
    }

    public function entityJoin($entity1, $entity2) {
        include_once 'EntityRelations.php';

        $eRelations = new EntityRelations();
        $joinArray = $eRelations->getJoins($entity1, $entity2);
        //var_dump('joinEntities' , $entity1 , $entity2);
        //var_dump('joinArray' , $joinArray);
        foreach ($joinArray as $join) {
            if ($join != null)
            //if($join[1] != null && $join[2] != null && $join[3] != null && $join[4] != null )
                $this->sqlArray['join'][] = array('table' => $join[2], 'jointype' => 'INNER', 'field1' => $join[1], 'field2' => $join[3], 'entity1' => $join[0], 'entity2' => $join[2], 'as' => $join['as']);
        }
    }

    public function setPersonSecondaryEntity($entityType, $start = false) {

        // join SecEntity if already not joined.
        // join person if already not joined


        $joinArray = array();
        $joinArrayPerson = array();

        $as = $entityType;


        switch ($entityType) {
            case 'victim':
                $joinArray = array('table' => 'act', 'jointype' => null, 'field1' => 'act.victim', 'field2' => 'victim.person_record_number', 'as' => 'act');
                $joinArrayPerson = array('table' => 'person', 'jointype' => null, 'field1' => 'victim.person_record_number', 'field2' => 'act.victim', 'as' => 'victim');
                break;
            case 'perpetrator':
                $joinArray = array('table' => 'involvement', 'jointype' => null, 'field1' => 'involvement.perpetrator', 'field2' => 'perpetrator.person_record_number', 'as' => 'involvement');
                $joinArrayPerson = array('table' => 'person', 'jointype' => null, 'field1' => 'involvement.perpetrator', 'field2' => 'perpetrator.person_record_number', 'as' => 'perpetrator');
                break;
            case 'source':
                $joinArray = array('table' => 'information', 'jointype' => null, 'field1' => 'information.source', 'field2' => 'source.person_record_number', 'as' => 'information');
                $joinArrayPerson = array('table' => 'person', 'jointype' => null, 'field1' => 'information.source', 'field2' => 'source.person_record_number', 'as' => 'source');
                break;
            case 'intervening_party':
                $joinArray = array('table' => 'intervention', 'jointype' => null, 'field1' => 'intervention.intervening_party', 'field2' => 'intervening_party.person_record_number', 'as' => 'intervention');
                $joinArrayPerson = array('table' => 'person', 'jointype' => null, 'field1' => 'intervention.intervening_party', 'field2' => 'intervening_party.person_record_number', 'as' => 'intervening_party');
                break;
        }

        $shouldJoinArray = true;
        $shouldJoinArrayPerson = true;
        foreach ($this->sqlArray['join'] as $joinField) {
            if ($joinField['as'] == $joinArray['as']) {
                $shouldJoinArray = false;
            }
            if ($joinField['as'] == $joinArrayPerson['as']) {
                $shouldJoinArrayPerson = false;
            }
            if ($shouldJoinArray == false && $shouldJoinArrayPerson == false) {
                break;
            }
        }
        if ($shouldJoinArray == true && $this->sqlArray['from'] != $joinArray['as']) {
            $this->sqlArray['join'][] = $joinArray;
        }
        if ($shouldJoinArrayPerson == true) {
            if (substr($this->sqlArray['from'], 0, 6) != 'person') {
                $this->sqlArray['join'][] = $joinArrayPerson;
            }
        }
        if ($this->sqlArray['from'] == 'person') {
            $this->sqlArray['from'] = "person AS $entityType";
        }
        $as = $entityType;
        return $as;
    }

    private function sqlArrayToSql($sqlArray) {

        //var_dump($this->sqlArray);
        $selectTableArray = array();

        //JOIN
        foreach ($sqlArray['join'] as $joinElement) {
            $on1 = ($joinElement['entity1'] != null) ? "{$joinElement['entity1']}.{$joinElement['field1']}" : "{$joinElement['field1']}";
            $on2 = ($joinElement['entity2'] != null) ? "{$joinElement['as']}.{$joinElement['field2']}" : "{$joinElement['field2']}";
            if ($joinElement['as'] != null || trim($joinElement['as']) != '') {
                $as = 'AS ' . $joinElement['as'];
            }
            //join condition
            $joinCondition = null;
            if ($joinElement['condition'] != null) {
                $joinCondition = $joinElement['condition'];
            }

            $joinSql .= " {$joinElement['jointype']} JOIN {$joinElement['table']} $as ON $on1 = $on2 $joinCondition";

            //select the tables that come for SELECT xxxx fields ignore mlt tables and choose all other tables
            /* if( strncmp( trim($joinElement['table']) , 'mlt_' , 4) != 0 ){
              $selectTableArray[] = $joinElement['table'];
              } */

//var_dump('JOIN' , $joinSql );
        }
        //JOIN END
//var_dump('selectTableArray' , $selectTableArray);
        //SELECT
        //$selectTableSql = $entityType.'.* ';
        foreach ($selectTableArray as $selectTableElement) {
            $selectTableSql .= ($selectTableSql == null ? '' : ' , ') . $selectTableElement . ".* ";
        }
        $selectSql = $selectTableSql;
        if ($sqlArray['select'] == null || count($sqlArray['select']) == 0) {
            $selectSql = '*';
        } else {
            foreach ($sqlArray['select'] as $selectElement) {
                $selectSql .= ($selectSql == null ? '' : ' , ') . $selectElement;
            }
        }

//var_dump('SELECT' , $selectSql );
        //SELECT END
        //WHERE
        //var_dump('where',$sqlArray['where'] );
        foreach ($sqlArray['where'] as $whereElement) {
            //$whereLink = ( ( $whereLastLink == null || trim($whereLastLink)== '') ? ' AND ' : " $whereLastLink " );
            $whereSql .= ($whereSql == null ? '' : $whereLastLink) . $whereElement['condition'];
            //$whereLastLink = $whereElement['operator'];

            switch (trim($whereElement['operator'])) {
                case 'or':
                case 'OR':
                    $whereLastLink = ' OR ';
                    break;

                case null:
                case '':
                case 'and':
                case 'AND':
                    $whereLastLink = ' AND ';
                    break;
                default:
                    $whereLastLink = ' AND ';
                    break;
            }
        }
//var_dump('WHERE' , $whereSql );
        //WHERE END
        //GROUP BY
        foreach ($sqlArray['groupby'] as $groupbyElement) {
            $groupbySql .= ($groupbySql == null ? '' : ' , ') . $groupbyElement;
        }
        //GROUP BY END
        //ORDER BY
        foreach ($sqlArray['orderby'] as $orderbyElement) {
            $orderbySql .= ($orderbySql == null ? '' : ' , ') . $orderbyElement;
        }
        //GROUP BY END


        $whereSql = $whereSql != null ? ' WHERE ' . $whereSql : '';
        $groupbySql = $groupbySql != null ? ' GROUP BY ' . $groupbySql : '';
        $orderbySql = $orderbySql != null ? ' ORDER BY ' . $orderbySql : '';

        $sqlStatement = "SELECT $selectSql FROM {$sqlArray['from']} $joinSql $whereSql $groupbySql  $orderbySql ";
        //echo $sqlStatement;
        return $sqlStatement;
    }

    public function getEntityArray($entity) {
        //var_dump('get_entity_array' , $entity);

        $entity = $this->tableOfEntity($entity);
        if ($this->entityArray[$entity] == null) {
            $this->entityArray[$entity] = $this->getFormArrayForEntity($entity);
        }

        return $this->entityArray[$entity];
    }

    public function getFormArrayForEntity($entityOri) {
        include_once APPROOT . 'inc/lib_entity_forms.inc';
        // HAVE TO EXIT GRACEFULLY IF A INVALIED ENTITY IS SUPPLIED
        //var_dump('formarrayforentity' , $entity);
        switch (trim($entityOri)) {
            case 'biographic_details':
                $entity = 'biographic';
                break;
            case 'supporting_docs_meta':
                $entity = 'document';
                break;
            default:
                $entity = $entityOri;
        }

        $entityForm = $this->tableOfEntity($entity) . '_form';
        if(function_exists($entityForm)){
          return $entityForm('all');
        }

        return default_form($entity, 'all');
    }

    public function tableOfEntity($entity_type) {
        if ($entity_type == 'victim' || $entity_type == 'perpetrator' || $entity_type == 'source' || $entity_type == 'intervening_party') {
            $entity_table = 'person';
        } else {
            $entity_table = $entity_type;
        }
        return trim($entity_table);
    }

    public function isPersonExtention($entity_type) {
        if ($entity_type == 'victim' || $entity_type == 'perpetrator' || $entity_type == 'source' || $entity_type == 'intervening_party') {
            return true;
        } else {
            return false;
        }
    }

}
