<?php

function form_customization_get_reset_fields() {
    $reset_fields = array(
        'reset_label' => 'field_label',
        'reset_visible_new' => 'visible_new',
        'reset_visible_view' => 'visible_view',
        'reset_visible_browse' => 'visible_browse',
        'reset_visible_search' => 'visible_search',
        'reset_visible_search_display' => 'visible_search_display',
        'reset_order' => 'label_number',
        'reset_clari' => 'clar_note',
        'reset_required' => 'required',
        'reset_validation' => 'validation'
    );
    return $reset_fields;
}

function form_customization_reset_all($entity) {
    global $global;
    $entity = $global['db']->qstr($entity);
    $sql = "UPDATE data_dict AS N JOIN data_dict_original AS O ON N.field_number = O.field_number
          SET
            N.field_label = O.field_label ,
            N.field_type = O.field_type,
            N.datatype = O.datatype ,
            N.label_number = O.label_number ,
            N.list_code = O.list_code ,
            N.is_repeat = O.is_repeat ,
            N.clar_note = O.clar_note ,
            N.field_name = O.field_name ,
            N.entity = O.entity ,
            N.find = O.find ,
            N.essential =O.essential ,
            N.visible_new = O.visible_new ,
            N.visible_view = O.visible_view,
            N.validation = O.validation ,
            N.required = O.required ,
            N.visible_browse = O.visible_browse  ,
            N.visible_search = O.visible_search ,
            N.visible_search_display = O.visible_search_display
          WHERE O.entity=$entity";

    $res = $global['db']->Execute($sql);
    if (!$res)
        shnMessageQueue::addError(_t('UNABLE_TO_RESET_FIELDS_'));
    else
        shnMessageQueue::addInformation(_t('ALL_COLUMNS_HAVE_BEEN_RESET_TO_THEIR_ORIGINAL_VALUES_'));
}

function form_customization_get_field_table($entity) {
    return Browse::getFields($entity);
}

function form_customization_reset_field($entity, $field) {
    global $global;
    //var_dump($entity);
    $field = addslashes_deep($field);
    $entity = $global['db']->qstr($entity);
    $sql = "Update data_dict as N join data_dict_original as O
                ON N.field_number = O.field_number
                SET N.$field = O.$field
                where O.entity=$entity";
    //$global['db']->debug = true;
    $res = $global['db']->Execute($sql);
    if (!$res)
        shnMessageQueue::addError(_t('UNABLE_TO_RESET_FIELD_'));
    else
        shnMessageQueue::addInformation(_t('COLUMN_RESET_BACK_TO_ORIGINAL_VALUES_'));
}

function form_customization_process_entity_form($entity) {
    global $global;
    global $conf;
    $records = Browse::getFieldsIds($entity);
    //var_dump($records);
    include_once(APPROOT . 'inc/i18n/lib_l10n.inc');
    $locales = l10n_get_locals();

    foreach ($records as $record) {
        $fieldValues = array();
        $l10nValues = array();
        $recordNumber = $record['field_number'];
        if (isset($_POST['label_' . $recordNumber])) {
            //dont let the lable be empty
            if (isset($_POST['label_' . $recordNumber]) && is_array($_POST['label_' . $recordNumber])) {
                $labels = $_POST['label_' . $recordNumber];
                foreach ($locales as $code => $loc) {
                    if (!trim($labels[$code])) {
                        continue;
                    }

                    if ($code == "en" && isset($labels["en"])) {
                        $fieldValues['field_label'] = $labels["en"];
                    }

                    if (isset($labels[$code])) {
                        $l10nValues['msgstr'] = $global['db']->qstr($labels[$code]);
                        $l10nValues['msgid'] = "'{$recordNumber}'";
                        $l10nValues['locale'] = "'{$code}'";
                        $global['db']->Replace('data_dict_l10n', $l10nValues, array('msgid', 'locale'));
                    }
                }
            } else {
                shnMessageQueue::addError("\"{$record['field_name']}\" " . _t('LABEL_CANNOT_BE_EMPTY__ENTER_A_VALUE_'));
                continue;
            }
        }

        if ((strtolower($record['essential']) == 'n')) {
            if (isset($_POST['visible_new'])) {
                $fieldValues['visible_new'] = strtolower($_POST['visible_new_' . $recordNumber]) == 'y' ? 'y' : 'n';
            }
            if (isset($_POST['enabled'])) {
                $fieldValues['enabled'] = strtolower($_POST['enabled_' . $recordNumber]) == 'y' ? 'y' : 'n';
            }
            if (isset($_POST['required'])) {
                $fieldValues['required'] = strtolower($_POST['required_' . $recordNumber]) == 'y' ? 'y' : 'n';
            }
        }

        if (isset($_POST['visible_view'])) {
            $fieldValues['visible_view'] = strtolower($_POST['visible_view_' . $recordNumber]) == 'y' ? 'y' : 'n';
        }

        if (isset($_POST['visible_browse'])) {
            $fieldValues['visible_browse'] = strtolower($_POST['visible_browse_' . $recordNumber]) == 'y' ? 'y' : 'n';
        }
        if (isset($_POST['visible_adv_search'])) {
            $fieldValues['visible_adv_search'] = strtolower($_POST['visible_adv_search_' . $recordNumber]) == 'y' ? 'y' : 'n';
        }

        if (isset($_POST['visible_adv_search_display'])) {
            $fieldValues['visible_adv_search_display'] = strtolower($_POST['visible_adv_search_display_' . $recordNumber]) == 'y' ? 'y' : 'n';
        }

        if (isset($_POST['clari'])) {
            $fieldValues['clar_note'] = strtolower($_POST['clari_' . $recordNumber]) == 'y' ? 'y' : 'n';
        }
        /* if (isset($_POST['is_repeat'])) {
          $fieldValues['is_repeat'] = strtolower($_POST['is_repeat_' . $recordNumber]) == 'y' ? 'y' : 'n';
          } */

        if (isset($_POST['validation'])) {
            $fieldValues['validation'] = $_POST['validation_' . $recordNumber];
        }

        if(isset($_POST['visibility_field'])){
            $dbQuery = new Browse();
            try {
                $dbQuery->ExecuteNonQuery("delete from data_dict_visibility where field_number='$recordNumber' ");
            } catch (Exception $e) {
                //var_dump($e,$sql);
            }
        }

        if (isset($_POST['visibility_field_' . $recordNumber]) && isset($_POST['visibility_field_' . $recordNumber . '_vals'])) {

            $fieldToHide = $_POST['visibility_field_' . $recordNumber];
            if(is_array($fieldToHide)) {
                $hideValues = array();
                foreach($fieldToHide as $conditionIndex=>$field_number2){
                    if(!$_POST['visibility_field_' . $recordNumber.'_vals'][$conditionIndex]){
                        continue;
                    }
                    $options  = (array)$_POST['visibility_field_' . $recordNumber . '_vals'][$conditionIndex];
                    if(!$hideValues[$field_number2]) {
                        $hideValues[$field_number2] = $options;
                    }else{
                        $hideValues[$field_number2] = array_merge($hideValues[$field_number2],$options);
                    }
                }
                $sql2 = array();

                foreach($hideValues as $field_number2 => $vals){
                    $vals = array_unique($vals);
                    $field_number2 = $global['db']->qstr($field_number2);
                    foreach ($vals as $val) {

                        $val = $global['db']->qstr($val);
                        $sql2[] = " ('$recordNumber', $field_number2,$val)";
                    }
                }
                if($sql2) {
                    $sql = "INSERT INTO data_dict_visibility (`field_number`,`field_number2`,`value`) values ";
                    $sql .= implode(",", $sql2);

                    $dbQuery = new Browse();
                    try {
                        $dbQuery->ExecuteNonQuery($sql);
                    } catch (Exception $e) {
                        //var_dump($e,$sql);
                    }
                }
            }
        }
        if($fieldValues){
        $global['db']->AutoExecute('data_dict', $fieldValues, 'UPDATE', "field_number = '$recordNumber'");
        }
    }
}

function form_customization_update_fields_order($entity) {
    global $global;
    global $conf;

    if (!isset($_POST['itemsorder'])) {
        return;
    }

    $records = Browse::getFieldsIds($entity);
    $recordNumbers = array();
    foreach ($records as $record) {
        $recordNumbers[] = $record['field_number'];
    }


    $itemorders = @json_decode(stripslashes($_POST['itemsorder']), true);
    if (!is_array($itemorders)) {
        return;
    }
    $i = 1;
    foreach ($itemorders as $itemorder) {
        if (!$itemorder['id'] || !in_array($itemorder['id'], $recordNumbers)) {
            continue;
        }
        $fieldValues = array();
        $fieldValues['label_number'] = (int) $i;
        $global['db']->AutoExecute('data_dict', $fieldValues, 'UPDATE', "field_number = '" . $itemorder['id'] . "'");
        $i++;
    }
}

function form_customization_delete_field($field_number) {
  $db = new Browse();

  $sql = "SELECT * FROM `data_dict` WHERE `field_number` LIKE '$field_number'";

  $result = $db->ExecuteQuery($sql);

  $field_type = $result[0]['field_type'];
  $field_name = $result[0]['field_name'];
  $entity = strtolower($result[0]['entity']);

  switch ($field_type) {
    case 'subformat':
      delete_subformat($field_number, $entity, $field_name);
      break;
    case 'location':
      delete_location($field_number, $entity, $field_name);
      break;
    case 'mt_tree':
      delete_mt_field($field_number, $entity, $field_name);
      break;
    case 'mt_select':
      delete_mt_field($field_number, $entity, $field_name);
      break;
    case 'user_select':
      delete_mt_field($field_number, $entity, $field_name);
      break;
    case 'line':
      delete_from_data_dict($field_number);
      break;
    default:
      delete_normal_field($field_number, $entity, $field_name);
      break;
  }
}

function delete_subformat($field_number, $entity, $field_name){
  $db = new Browse();

  $db->ExecuteNonQuery("DELETE FROM `gacl_axo` WHERE `value` = '$field_name'");
  $db->ExecuteNonQuery("DELETE FROM `gacl_axo_map` WHERE `value` = '$field_name'");

  $get_subformat_fields = "SELECT * FROM `data_dict` WHERE `entity` = '$field_name'";
  $subformat_fields = $db->ExecuteQuery($get_subformat_fields);

  foreach ($subformat_fields as $field) {
    form_customization_delete_field($field['field_number']);
  }

  delete_normal_field($field_number, $entity, $field_name);

  $delete_subformat_table = "DROP TABLE IF EXISTS `$field_name`";
  $db->ExecuteNonQuery($delete_subformat_table);
}

function delete_location($field_number, $entity, $field_name){
  $db = new Browse();

  $delete_latitude = "ALTER TABLE `$entity` DROP `".$field_name."_latitude`";
  $delete_longitude = "ALTER TABLE `$entity` DROP `".$field_name."_longitude`";
  $db->ExecuteNonQuery($delete_latitude);
  $db->ExecuteNonQuery($delete_longitude);

  delete_from_data_dict($field_number);
}

function delete_mt_field($field_number, $entity, $field_name){
  $db = new Browse();
  $delete_mt_table = "DROP TABLE IF EXISTS mlt_".$entity."_".$field_name;
  $db->ExecuteNonQuery($delete_mt_table);
  delete_normal_field($field_number, $entity, $field_name);
}

function delete_normal_field($field_number, $entity, $field_name){
  delete_from_entity($entity, $field_name);
  delete_from_data_dict($field_number);
}

function delete_from_data_dict($field_number){
  $db = new Browse();

  $db->ExecuteNonQuery("DELETE FROM `data_dict_l10n` WHERE `msgid` = '$field_number'");
  $db->ExecuteNonQuery("DELETE FROM `data_dict` WHERE `field_number` = '$field_number'");
}

function delete_from_entity($entity, $field_name){
  $db = new Browse();

  $entity = strtolower($entity);

  $delete_from_entity = "ALTER TABLE `$entity` DROP `$field_name`";
  $db->ExecuteNonQuery($delete_from_entity);
}

function form_customization_add_field($field_type_options) {

    $fieldValues = array();
    $fieldValues['entity'] = strtolower($_POST['entity_select']);
    $fieldValues['field_name'] = str_replace(" ", "", strtolower($_POST['field_name']));
    $fieldValues['field_number'] = $_POST['field_number'];
    $fieldValues['field_type'] = $_POST['field_type'];

    $fieldValues['required'] = strtolower($_POST['required']) == 'y' ? 'y' : 'n';
    $fieldValues['visible_new'] = strtolower($_POST['visible_new']) == 'y' ? 'y' : 'n';
    $fieldValues['visible_view'] = strtolower($_POST['visible_view']) == 'y' ? 'y' : 'n';
    $fieldValues['visible_browse'] = strtolower($_POST['visible_browse']) == 'y' ? 'y' : 'n';
    $fieldValues['visible_adv_search'] = strtolower($_POST['visible_adv_search']) == 'y' ? 'y' : 'n';
    $fieldValues['visible_adv_search_display'] = strtolower($_POST['visible_adv_search_display']) == 'y' ? 'y' : 'n';
    $fieldValues['clar_note'] = strtolower($_POST['clari']) == 'y' ? 'y' : 'n';
    $fieldValues['is_repeat'] = 'n';
    $fieldValues['validation'] = ""; //$_POST['validation'];
    $fieldValues['essential'] = 'n';
    $fieldValues['enabled'] = 'y';
    $fieldValues['label_number'] = 0;
    $fieldValues['list_code'] = 0;

    foreach ($fieldValues as $k => $v) {
        $v = preg_replace('/[^A-Za-z0-9\-_]/', '', $v); // Removes special chars.
        $v = trim($v);
        $fieldValues[$k] = $v;
    }
    $fieldValues['field_label'] = addslashes($_POST['field_label']);

    $fieldValues['field_name'] = str_replace(" ", "", $fieldValues['field_name']);
    $fieldValues['field_name'] = str_replace("-", "_", $fieldValues['field_name']);

    switch ($fieldValues['field_type']) {
        case 'text':
            $field_mysql_type = "VARCHAR(250)";
            break;
        case 'textarea':
            $field_mysql_type = "TEXT";
            break;
        case 'date':
            $field_mysql_type = "DATE";
            break;
        case 'radio':
            $field_mysql_type = "VARCHAR(1)";
            break;
        case 'location':
            $fieldValues['visible_browse'] = 'n';
            $fieldValues['visible_adv_search'] = 'n';
            $fieldValues['visible_adv_search_display'] = 'n';
            break;
        case 'mt_tree':
            $field_mysql_type = "VARCHAR(14)";
            $fieldValues['list_code'] = (int) $_POST['list_code'];
            break;
        case 'mt_tree_multi':
            $field_mysql_type = "VARCHAR(14)";
            $fieldValues['is_repeat'] = 'y';
            $fieldValues['field_type'] = 'mt_tree';
            $fieldValues['list_code'] = (int) $_POST['list_code'];
            break;
        case 'mt_select' :
            $field_mysql_type = "VARCHAR(14)";
            $fieldValues['list_code'] = (int) $_POST['list_code'];
            break;
        case 'mt_select_multi':
            $field_mysql_type = "VARCHAR(14)";
            $fieldValues['is_repeat'] = 'y';
            $fieldValues['field_type'] = 'mt_select';
            $fieldValues['list_code'] = (int) $_POST['list_code'];
            break;
        case 'user_select' :
            $field_mysql_type = "VARCHAR(14)";
            break;
        case 'user_select_multi':
            $field_mysql_type = "VARCHAR(14)";
            $fieldValues['is_repeat'] = 'y';
            $fieldValues['field_type'] = 'user_select';
            break;
        case 'subformat':
            $field_mysql_type = "VARCHAR(14)";
            $fieldValues['visible_browse'] = 'n';
            $fieldValues['visible_adv_search'] = 'n';
            $fieldValues['visible_adv_search_display'] = 'n';
            $fieldvalues['visible_edit'] = 'n';
            $fieldvalues['visible_new'] = 'n';
            $fieldValues['is_repeat'] = 'n';
            $fieldValues['required'] = 'n';
            $fieldValues['field_type'] = 'subformat';
            break;
        case 'line' :
            $fieldValues['visible_browse'] = 'n';
            $fieldValues['visible_adv_search'] = 'n';
            $fieldValues['visible_adv_search_display'] = 'n';
            $fieldValues['required'] = 'n';
            $fieldValues['clar_note'] = 'n';

            break;
    }
    $fieldValues['visible_edit'] = $fieldValues['visible_new'];
    $fieldValues['visible_search'] = $fieldValues['visible_browse'];
    $fieldValues['visible_search_display'] = $fieldValues['visible_browse'];


    $validated = true;
    if (!$fieldValues['entity'] || !$fieldValues['field_name'] || !$fieldValues['field_number']
            || !$fieldValues['field_label'] ||
            (($fieldValues['field_type'] == "mt_tree" || $fieldValues['field_type'] == "mt_select") && !$fieldValues['list_code'])) {
        shnMessageQueue::addError(_t('FIELD_NAME___FIELD_NUMBER_AND_FIELD_LABEL_CANNOT_BE_EMPTY_PLEASE_ENTER_SOME_VALUES'));
        $validated = false;
    } else if (form_customization_field_name_exists($fieldValues['field_name'], $fieldValues['entity'])) {
        shnMessageQueue::addError(_t('FIELD_NAME_ALREADY_EXISTS__PLEASE_GIVE_IT_ANOTHER_NAME'));
        $validated = false;
    } else if (form_customization_field_number_exists($fieldValues['field_number'])) {
        shnMessageQueue::addError(_t('FIELD_NUMBER_ALREADY_EXISTS__PLEASE_CHOOSE_A_DIFFERENT_NUMBER'));
        $validated = false;
    } else if (is_management_field(array('field_number' => $fieldValues['field_number']))) {
        shnMessageQueue::addError(_t('FIELD_NUMBERS_ENDING_WITH_60_TO_80_ARE_RESERVED_FOR_MANAGEMENT_FIELDS__PLEASE_USE_A_NUMBER_WHICH_DO_NOT_FALLS_IN_THIS_RANGE_'));
        $validated = false;
    } else if (!array_key_exists($fieldValues['field_type'], $field_type_options)) {
        shnMessageQueue::addError(_t('NOT_A_VALID_FIELD_TYPE__TRY_AGAIN'));
        $validated = false;
    }

    if (!$validated) {
        return false;
    }



    $dbQuery = new Browse();
    try {

        if ($fieldValues['field_type'] == "location") {
            $latitude_field = "ALTER TABLE `" . $fieldValues['entity'] . "` ADD `" . $fieldValues['field_name'] . "_latitude` double NULL ";
            $longitude_field = "ALTER TABLE `" . $fieldValues['entity'] . "` ADD `" . $fieldValues['field_name'] . "_longitude` double NULL ";
            $dbQuery->ExecuteNonQuery($latitude_field);
            $dbQuery->ExecuteNonQuery($longitude_field);
        }
        if($fieldValues['field_type'] == "subformat"){
          include_once(APPROOT . 'inc/subformats/subformats.php');
          $subformats = new Subformats($dbQuery);
          $subformats->create($fieldValues['entity'], $fieldValues['field_name'], $fieldValues['field_label']);
        }
        if (($fieldValues['field_type'] == "mt_tree" || $fieldValues['field_type'] == "mt_select") && $fieldValues['is_repeat'] == 'y') {
            $mtIndex = new MtIndex();
            $mt_term = $mtIndex->getTermforCode($fieldValues['list_code']);

            $sql = "CREATE  TABLE IF NOT EXISTS  `mlt_" . $fieldValues['entity'] . "_" . $fieldValues['field_name'] . "` (
                `vocab_number` VARCHAR(14) NOT NULL ,
                `record_number` VARCHAR(45) NOT NULL ,
                PRIMARY KEY (`vocab_number`, `record_number`) ,
                  FOREIGN KEY (`vocab_number` )
                  REFERENCES  `mt_" . $fieldValues['list_code'] . "_" . $mt_term . "` (`vocab_number` )
                  ON DELETE RESTRICT
                  ON UPDATE CASCADE,
                  FOREIGN KEY (`record_number` )
                  REFERENCES  `" . $fieldValues['entity'] . "` (`" . get_primary_key($fieldValues['entity']) . "` )
                  ON DELETE CASCADE
                  ON UPDATE CASCADE)ENGINE = InnoDB DEFAULT CHARSET=utf8;";
            $dbQuery->ExecuteNonQuery($sql);
        }if ($fieldValues['field_type'] == "user_select" && $fieldValues['is_repeat'] == 'y') {

            $sql = "CREATE  TABLE IF NOT EXISTS  `mlt_" . $fieldValues['entity'] . "_" . $fieldValues['field_name'] . "` (
                `username` VARCHAR(255) NOT NULL ,
                `record_number` VARCHAR(45) NOT NULL ,
                PRIMARY KEY (`username`, `record_number`) ,
                  FOREIGN KEY (`username` )
                  REFERENCES  `user` (`username` )
                  ON DELETE RESTRICT
                  ON UPDATE CASCADE,
                  FOREIGN KEY (`record_number` )
                  REFERENCES  `" . $fieldValues['entity'] . "` (`" . get_primary_key($fieldValues['entity']) . "` )
                  ON DELETE CASCADE
                  ON UPDATE CASCADE)ENGINE = InnoDB DEFAULT CHARSET=utf8;";
           $dbQuery->ExecuteNonQuery($sql);

        } elseif(!is_null($field_mysql_type)) {
          add_field_to_entity($fieldValues['entity'], $fieldValues['field_name'], $field_mysql_type);
        }

        add_field_to_data_dict($fieldValues);
    } catch (Exception $e) {
        var_dump($e,$sql);
        exit;
        return false;
    }
    return true;
}

function add_field_to_entity($entity, $name, $type) {
  $dbQuery = new Browse();
  $sql = "ALTER TABLE `$entity` ADD `$name` $type ";
  $dbQuery->ExecuteNonQuery($sql);
}

function add_field_to_data_dict($fieldValues){
    $dbQuery = new Browse();
    $sql = "INSERT INTO data_dict (`" . implode("`,`", array_keys($fieldValues)) . "`) values('" . implode("','", $fieldValues) . "')";
    $dbQuery->ExecuteNonQuery($sql);
}

function form_customization_field_name_exists($field_name, $entity) {
    $dbQuery = new Browse();
    $resultSet = $dbQuery->ExecuteQuery("SELECT * FROM data_dict where field_name='$field_name' AND entity='$entity'");
    //var_dump($resultSet,sizeof($resultSet));
    if (sizeof($resultSet) > 0)
        return true;
    else
        return false;
}

function form_customization_field_number_exists($field_number) {
    $dbQuery = new Browse();
    $resultSet = $dbQuery->ExecuteQuery("SELECT * FROM data_dict where field_number='$field_number'");
    //var_dump($resultSet,sizeof($resultSet));
    if (sizeof($resultSet) > 0)
        return true;
    else
        return false;
}

function form_customization_update_help($post, $entity) {
    global $global;
    global $conf;

    $records = Browse::getFieldsIds($entity);
    //var_dump($records);
    include_once(APPROOT . 'inc/i18n/lib_l10n.inc');
    $locales = l10n_get_locals();

    $helpsubs = array("definition", "guidelines", "entry", "examples");
    foreach ($records as $record) {
        $recordNumber = $record['field_number'];
        foreach ($locales as $code => $loc) {
            $dataArray = array();

            foreach ($helpsubs as $sub) {
                if (isset($_POST[$sub . '_' . $recordNumber]) && is_array($_POST[$sub . '_' . $recordNumber])) {
                    $labels = $_POST[$sub . '_' . $recordNumber];
                    if (isset($labels[$code])) {
                        $dataArray[$sub] = $global['db']->qstr($labels[$code]);
                    }
                }
            }
            if(!$dataArray){
                continue;
            }
            $dataArray['field_number'] = $global['db']->qstr($recordNumber);


            if ($code == "en") {
                $global['db']->Replace('help', $dataArray, 'field_number', false);
            } else {
                $dataArray['locale'] = $global['db']->qstr($code);

                $global['db']->Replace('help_l10n', $dataArray, array('field_number', 'locale'), false);
            }
        }
    }
}
