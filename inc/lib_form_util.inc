<?php
include_once APPROOT . 'inc/lib_form.inc';

function compartmentalize_view_form($entity_form) {

    $normalFields = array();
    $systemFields = array();
    $supportingDocFields = array();

    foreach ($entity_form as $fieldName => $field) {

        if ($entity_form[$fieldName]['type'] != 'submit') {
            $field_number = $entity_form[$fieldName]['field_number'] % 100;
            if ($field_number == 1 || $field_number == 61 || $field_number == 62 || $field_number == 70 || $field_number == 71) {
                $systemFields[$fieldName] = $field;
            } else if ($field_number == 66) {
                $supportingDocFields[$fieldName] = $field;
            } else if ($fieldName == 'picture' || $fieldName == 'file_size') {
                $systemFields[$fieldName] = $field;
            } else {
                $normalFields[$fieldName] = $field;
            }
            //echo $fields[$fieldName];
        }
    }

    $compFields = array();
    $compFields[] = $normalFields;
    $compFields[] = $systemFields;
    $compFields[] = $supportingDocFields;

    return $compFields;
}

function place_form_elements($entity_form, $fields) {
    $divide_index = divide_index_html_array($entity_form);

    $size = sizeof($fields) / 2;
    $counter = 0;
    foreach ($entity_form as $fieldName => $field) {
        if ($entity_form[$fieldName]['type'] != 'submit') {
            echo $fields[$fieldName];
        }

        if ($counter == $divide_index) {
            
        }
        $counter++;
    }
    ?>
    <br style="clear: both" />
    <?php
    return $fields;
}

function divide_index_html_array($form_array) {

    $htmlLengths = array();
    $totalLength = 0;
    foreach ($form_array as $fieldName => $formElement) {
        $totalLength += get_form_element_length($formElement['type']);
        $htmlLengths[$fieldName] = $totalLength;
    }
    $divider_length = (int) $totalLength / 2;
    $index = 0;
    foreach ($htmlLengths as $length) {

        if ($length > $divider_length) {
            return $index;
        } else {
            $index++;
        }
    }
}

function get_form_element_length($formType) {
    switch ($formType) {
        case 'text':
            return 1;
            break;
        case 'textarea' :
            return 4;
            break;
        case 'mt_select':
            return 1;
            break;
        case 'mt_tree':
            return 1;
            break;
        case 'date':
            return 1;
            break;
        default :
            return 0;
            break;
    }
}

function generate_formarray($entity, $viewType = 'new', $allVisible = false) {
    //$viewType can be 'new' , 'edit' or 'view'
    //var_dump($entity , $viewType);
    global $global,$conf;
    if ($entity != null) {

        if ($entity == 'victim' || $entity == 'perpetrator' || $entity == 'source' || $entity == 'intervening_party') {
            //var_dump('generate_form_array',$entity);
            $secEntity = new SecEntity();
            return $secEntity->getFormArray($entity, $viewType);
        }

        $formArray = array();
        $entityFields = Browse::getEntityFields($entity);

        $visible_field = null;

        $module = shnFrontController::getModule();
        if ($module instanceof eventsModule) {
            if ($entity == 'event') {
                $record_number = $_GET['eid'];
            }
        }

        switch ($viewType) {
            case 'new':
                $visible_field = 'visible_new';
                acl_is_entity_allowed($entity, 'create', $record_number);
                break;
            case 'edit' :
                $visible_field = 'visible_new';  // change to  visible_edit when edit has different forms
                acl_is_entity_allowed($entity, 'update', $record_number);
                break;
            case 'view':
                $visible_field = 'visible_view';
                //acl_is_entity_allowed($entity , 'read' , $record_number);
                break;
            case 'search':
                $visible_field = 'visible_browse';//'visible_search';
                break;
            case 'search_view':
                $visible_field = 'visible_browse';//'visible_search_display';
                break;
            case 'browse':
                $visible_field = 'visible_browse';
                break;
            case 'all':
                $visible_field = null;
                break;
            default :
                $visible_field = 'visible_new';
                break;
        }
        
        $isAdmin = acl_is_mod_allowed('admin');

        //var_dump($visible_field);
        foreach ($entityFields as $entityField) {
            if (!$isAdmin && isset($conf['fieldstohide']) && isset($conf['fieldstohide'][$entity]) && in_array($entityField['field_name'], $conf['fieldstohide'][$entity])) {
                continue;
            }
            $type = $entityField['field_type'];
            $clari = ( (trim($entityField['clar_note']) == 'Y' || trim($entityField['clar_note']) == 'y' ) ? true : false );
            $mlt = ( (trim($entityField['is_repeat']) == 'Y' || trim($entityField['is_repeat']) == 'y' ) ? true : false );

            $visible = ( $visible_field == null || trim($entityField[$visible_field]) == 'Y' || trim($entityField[$visible_field]) == 'y' ) ? true : false;
            if($entity == "involvement" && $entityField['field_name'] =="perpetrator"){
                $visible = true;
                $type = $entityField['field_type'] = "related_person";
            }
            $link_table = $entityField['link_table'];
            $link_field = $entityField['link_field'];

            $field_number = $entityField['field_number'];

            $requiredTerm = $entityField['required'] == 'y' ? 'notnull' : null;
            $required = $entityField['required'] == 'y' ? true : false;
            $validationString = $entityField['validation'];
            $validation = array();
            $validation = split(',', $validationString);
            if ($requiredTerm != null) {
                //var_dump($requiredTerm);
                $validation[] = $requiredTerm;
            }


            if ($visible == true || $allVisible == true) {
                $formArray[$entityField['field_name']] = array('type' => $entityField['field_type'], 'label' => $entityField['field_label_l10n'], 'field_number' => $field_number,
                    'map' => array('entity' => $entity, 'field' => $entityField['field_name'], 'mt' => $entityField['list_code'], 'mlt' => $mlt, 'link_table' => $link_table, 'link_field' => $link_field),
                    'extra_opts' => array('label' => $entityField['field_label_l10n'],'clari' => $clari, 'validation' => $validation, 'required' => $required, 'help' => $entityField['field_number'])
                );
              
                                if($type == "location" && ($viewType == "new" || $viewType == "edit" || $viewType == "view" || $viewType == "all")){
                                 $formArray[  $entityField['field_name']."_latitude" ]    = array('type'=>"hidden",
                                       'label'=>$entityField['field_label_l10n']." "._t("latitude"),
                                       'field_number' => $field_number ,
	                            'map'=>array('entity'=>$entity,
                                        'field'=>$entityField['field_name']."_latitude" ,
                                        'mt'=>$entityField['list_code'] ,
                                        'mlt'=>$mlt ,
                                        'link_table'=>$link_table ,
                                        'link_field'=>$link_field ),
	                            'extra_opts' => array(   )    
                                    ); 
                                   $formArray[  $entityField['field_name']."_longitude" ]    = array('type'=>"hidden",
                                       'label'=>$entityField['field_label_l10n']." "._t("longitude"),
                                       'field_number' => $field_number ,
	                            'map'=>array('entity'=>$entity,
                                        'field'=>$entityField['field_name']."_longitude" ,
                                        'mt'=>$entityField['list_code'] ,
                                        'mlt'=>$mlt ,
                            'link_table' => $link_table,
                            'link_field' => $link_field),
                        'extra_opts' => array()
                    );
                }
            }
        }
        //var_dump($formArray);
        return $formArray;
    }
}

function label_to_field($label) {
    $fieldName = strtr(strtolower(trim($label)), ' ', '_');
    return $fieldName;
}

function popuate_formArray(&$formArray, $entity) {

    if ($formArray != null && $entity != null) {  // The function cannot continue unless the variables have been passed
        foreach ($formArray as $key=>&$formField) {   // iterate throught the elements in the form array
            $recordkeyName = '' . $formField['map']['entity'] . '_record_number';
            $entity_key_value = $entity->$recordkeyName;

            if ($formField['map']['field'] != null && $formField['map']['entity'] == $entity->_table) {   // Field Name should be defined, passed entity should match the entity type in form array
                if ($formField['extra_opts'] == null) {
                    $formField['extra_opts'] = array();
                }
                //$formField['extra_opts'] = array();
                if ($formField['map']['mlt'] == 'true') {
                    $valueArray = array();
                    if (is_array($entity->$formField['map']['field'])) {
                        foreach ($entity->$formField['map']['field'] as $vals) {
                            $valueArray[] = $vals->vocab_number;
                        }
                    }
                    $formField['extra_opts']['value'] = $valueArray;
                } else {
                    $formField['extra_opts']['value'] = $entity->$formField['map']['field'];
                }

                $formField['extra_opts']['clari_value'] = getClariNotes($formField, $entity_key_value);
            } else if ($formField['map']['field'] == 'picture') {
                $formField['extra_opts']['value'] = $entity->$formField['map']['field'];
            }

                        if($formField['type'] == "location"){
                           
                        $fieldName = $formField['map']['field'];
                        $fieldNameLat = $fieldName."_latitude";
                        $fieldNameLon = $fieldName."_longitude";
                        
                            if($entity->$fieldNameLat && $entity->$fieldNameLon){
                                $geoval = array('latitude'=>$entity->$fieldNameLat,
                                    'longitude'=>$entity->$fieldNameLon);
                                if ( $entity->geometries[$fieldName]){
                                   $geoval['geometries'] = $entity->geometries[$fieldName];
                                }	
                                $formField['extra_opts']['value'] = $geoval;
                            }else{
                                unset($entity->$fieldNameLat);
                                unset($entity->$fieldNameLon);
                                unset($formArray[$fieldNameLat]);
                                unset($formArray[$fieldNameLon]);
                            }
                            
            }
            //var_dump($formField['map']['field']);
            //var_dump($formField['extra_opts']);
        }
    }

    //var_dump($formArray);
}

function getClariNotes($formField, $recordNumber) {  // pass a form array element and retrive the clarifying notes for the field
    $notesArray = array();

    if ($formField['extra_opts']['clari'] == 'true') {
        if ($formField['map']['mlt'] == 'true') {
            $fieldName = $formField['map']['field'];
            $clariNote = new ClariNotes();
            $clariNotes = $clariNote->Find($formField['map']['entity'], $recordNumber, $formField['map']['field']);
            foreach ($clariNotes as $note) {
                //var_dump( $note->vocab_number, $note->value );
                $notesArray["$note->vocab_number"] = $note->value;
            }
        } else {
            $clariNote = new ClariNotes();
            $clariNotes = $clariNote->Find($formField['map']['entity'], $recordNumber, $formField['map']['field']);
            if (sizeof($clariNotes) > 0) {
                $note = $clariNotes[0];

                //var_dump( $note->vocab_number, $note->value );

                $notesArray["$note->vocab_number"] = $note->value;
            }
        }
    }
    //var_dump($notesArray);
    return $notesArray;
}

function form_objects($formArray, &$entity, $valuesArray = null) {
    if ($valuesArray == null) {                // testing
        $valuesArray = $_POST;
    }
    //var_dump($valuesArray);
    //$formArrayEntry = current($formArray);
    //reset($formArray);
    $recordkeyName = '' . $entity->_table . '_record_number';
    //$entity_key_value = $entity->$recordkeyName;

    if ($entity->$recordkeyName == null && ($valuesArray[$recordkeyName] == null || $valuesArray[$recordkeyName] == '' )) {
        $entity_key_value = $entity->$recordkeyName = shn_create_uuid($entity->_table);
    } else if ($entity->$recordkeyName != null) {
        $entity_key_value = $entity->$recordkeyName;
    } else {
        $entity_key_value = $entity->$recordkeyName = $valuesArray[$recordkeyName];
    }
    if ($entity_key_value == null) {
        throw new Invalide_Parameter_Exception("WTF!!! add a value for $recordkeyName ");
    }

    $entity->clari_notes = array();

    foreach ($formArray as $fieldName => $formField) {
        if ($formField['map']['entity'] == $entity->_table) {

            $recordkeyName = '' . $formField['map']['entity'] . '_record_number';            //have to change to handle act's child tables which have the act_record_number
            if ($formField['map']['field'] == $recordkeyName) {
                if ($valuesArray[$fieldName] != '') {
                    $entity->LoadFromRecordNumber($valuesArray[$fieldName]);
                    $entity->LoadManagementData();
                    $entity_key_value = $valuesArray[$fieldName];
                    //var_dump('values array field name ' , $valuesArray[ $fieldName]);
                }
            }


            //var_dump($formField['map']);
            if ($formField['map']['mlt'] != 'true') {  // non multivalue terms
                //$name = $formField['map']['field'];
                if ($formField['map']['mt'] != '0') {   // micro thesauri terms
                    if (trim($valuesArray[$fieldName]) != '') {       // blank selections should be ignored
                        if (!($valuesArray[$fieldName] == null || trim($valuesArray[$fieldName]) == '')) {
                            $entity->$formField['map']['field'] = $valuesArray[$fieldName];
                        } else {
                            $entity->$formField['map']['field'] = null;
                        }
                    }
                } else {
                    if ($fieldName != $recordkeyName) {

                        if (!($valuesArray[$fieldName] == null && $fieldName == 'supporting_documents')) {
                            $entity->$formField['map']['field'] = $valuesArray[$fieldName];
                        } else if (!($valuesArray[$fieldName] == null || trim($valuesArray[$fieldName]) == '')) {
                            $entity->$formField['map']['field'] = $valuesArray[$fieldName];
                        } else {
                            $entity->$formField['map']['field'] = null;
                        }
                    }
                }
            } else {                                  // multivalue Terms
                $termArray = array();
                if ($valuesArray[$fieldName] != null) {
                    if (is_array($valuesArray[$fieldName])) {
                        $valuesArray[$fieldName] = array_unique($valuesArray[$fieldName]); //remove duplicate MT values
                        foreach ($valuesArray[$fieldName] as $mtvalue) {
                            if (trim($mtvalue) != '') {
                                $mtTerm = MtFieldWrapper::getMTObject($formField['map']['field'], $formField['map']['entity']);
                                $mtTerm->vocab_number = $mtvalue;
                                $mtTerm->record_number = $entity->$recordkeyName;
                                $termArray[] = $mtTerm;
                            }
                        }
                    } else {
                        if (trim($valuesArray[$fieldName]) != '') {
                            $mtTerm = MtFieldWrapper::getMTObject($formField['map']['field'], $formField['map']['entity']);
                            $mtTerm->vocab_number = $valuesArray[$fieldName];
                            $mtTerm->record_number = $entity->$recordkeyName;
                            $termArray[] = $mtTerm;
                        }
                    }
                }
                //var_dump($termArray);
                if (sizeof($termArray) > 0) {
                    $entity->$formField['map']['field'] = $termArray;
                }
            }

            if ($formField['extra_opts']['clari'] == 'true') {  // Clarifying Notes
                if ($formField['map']['mlt'] != 'true') {              //NON multi value
                    /*
                      $clari_note_object = new ClariNotes();
                      $clari_note_object->entity = $entity->_table ;
                      $clari_note_object->record_number = $entity_key_value;
                      $clari_note_object->field_name = $formField['map']['field'];
                      $clari_note_object->value = $valuesArray[$fieldName.'_clarify']; */
                    if(is_array($valuesArray[$fieldName])){
                        $v = $valuesArray[$fieldName];
                        $va = array_shift($v);
                        $clariNote = ClariNotes::getClariNoteObject($entity->_table, $entity_key_value, $formField['map']['field'], $va, $valuesArray[$fieldName . '_clarify']);
                    }else{
                        if($fieldName == "deceased"){
                            $valuesArray[$fieldName] = ($valuesArray[$fieldName] == 'on') ? 'y' : 'n';
                        }
                        $clariNote = ClariNotes::getClariNoteObject($entity->_table, $entity_key_value, $formField['map']['field'], $valuesArray[$fieldName], $valuesArray[$fieldName . '_clarify']);
                    
                    }
                    if ($clariNote->value != null) {
                        $entity->clari_notes[] = $clariNote;
                    }
                } else {
                    if (is_array($valuesArray[$fieldName]))
                        
                        foreach ($valuesArray[$fieldName] as $mtvalue) {
                            /*
                              $clari_note_object = new ClariNotes();
                              $clari_note_object->entity = $entity->_table ;
                              $clari_note_object->record_number = $entity_key_value;
                              $clari_note_object->field_name = $formField['map']['field'];
                              $clari_note_object->vocab_number = $mtvalue; //iterate this array to get clari notes for each multi value
                              $clari_note_object->value = $valuesArray[$fieldName.'_clarify_' . $mtvalue ]; */

                            //$clariNote = ClariNotes::getClariNoteObject($entity->_table, $entity_key_value, $formField['map']['field'], $mtvalue, $valuesArray[$fieldName . '_' . $mtvalue . '_clarify']);
                            $clariNote = ClariNotes::getClariNoteObject($entity->_table, $entity_key_value, $formField['map']['field'], $mtvalue, $valuesArray[$fieldName . '_clarify']);


                            if ($clariNote->value != null) {
                                $entity->clari_notes[] = $clariNote;
                            }
                            break;
                        }
                }
            }
        }
    }
    //var_dump($entity);

    return $entity;
}

function shn_form_validate(&$form) {

    //set_include_path( get_include_path() . ':'. APPROOT .'3rd/');
    //require_once "Zend/Loader.php";
    //Zend_Loader::registerAutoload();

    include_once APPROOT . 'inc/lib_validate.inc';
    $valide = true;



    foreach ($form as $field_id => &$field) {
        $field['extra_opts']['error'] = array();

        $pkField = get_primary_key($field['map']['entity']);
        if ($pkField != $field['map']['field']) {

            $vals = $_POST[$field_id];
            $valFunctions = $field['extra_opts']['validation'];
            if (is_array($valFunctions) && sizeof($valFunctions) > 0) {
                foreach ($valFunctions as $function) {
                    if ($function != '') {
                        //var_dump('validating ' , $vals , $function);
                        //$ok = Zend_Validate::is($vals, $function);
                        //var_dump($ok);
                        $valMsg = null;
                        $ok = validate_field($function, $vals, $valMsg);
                        //var_dump($function  , $ok);

                        if (!($ok == true )) {
                            $valide = false;
                            $field['extra_opts']['error'][] = $valMsg;
                            //var_dump($ok);
                        }
                    }
                }
            }
            /*
              $validate = 'shn_valid_'.$field['valid'];
              $error = true;
              if(isset($vals)&& function_exists($validate)){
              if(is_array($vals)){
              //validate all elements
              foreach($vals as $value)
              $error = $validate($_POST[$field_id]) && $error;
              }
              else
              $error = $validate($_POST[$field_id]);
              if(!$error){
              $eo = $form[$field_id]['extra_opts'];
              $eo=(is_array($eo))?$eo:array();
              $eo['error']=true;
              $form[$field_id]['extra_opts'] = $eo;
              $gerror = false;
              }
              }
             */
        }
    }

    //spl_autoload_register(array('AutoLoadClass', '__autoload'));
    //var_dump($form);
    return $valide;
}

function validate_field($validationType, $vals, &$errorMsg) {
    $validate = 'shn_valid_' . $validationType;
    $valid = true;
    //hack to array values
    if ($validationType == 'notnull' && !isset($vals)) {
        $valid = $validate($vals) && $valid;
        if (!$valid)
            $errorMsg = shn_valid_get_error();
    }

    if (isset($vals) && function_exists($validate)) {
        if (is_array($vals)) {
            //validate all elements
            foreach ($vals as $value)
                $valid = $validate($value) && $valid;
        }
        else
            $valid = $validate($vals) && $valid;
        if (!$valid) {
            $errorMsg = shn_valid_get_error();
            //var_dump($errorMsg);
        }
    }

    return $valid;
}

function shn_form_get_html_fields($form, $field_div = true) {
    $i = 0;
    $fields = array();
    foreach ($form as $field_id => $options) {
        $controler = $options['type'];
        $controler = 'shn_form_' . $controler;
        $controler .= (isset($options['map']['mlt']) && $options['map']['mlt'] == true) ? '_mlt' : '';
        if (function_exists($controler)) {
            $options['extra_opts']['mt'] = $options['map']['mt'];
            ob_start();
            $controler($options['label'], $field_id, $options['extra_opts']);
            $html = ob_get_clean();
            
            if ($options['type'] != 'submit' && $options['type'] != 'hidden' && $field_div){
                $html2 = "<div class='control-group";
                if(isset($options['extra_opts']['error']) && count($options['extra_opts']['error'])){
                    $html2.= " error ";
                }
    
                $html2.= "'>$html</div>";
                $html = $html2;
            }

            $fields[$field_id] = $html;
            $fields[$i++] = $html;
        }
        else
            ;
        // echo $controler._t('_CONTROLER_NOT_FOUND_');
    }

    return $fields;
}

function shn_form_get_html_table($columnNames = null, $columnValues = null,$sortable = true) {
    echo "<table class='table table-bordered table-striped table-hover'>";
    echo "<thead>";
    echo "<tr>";
    foreach ($columnNames as $field => $value) {
        $url = get_sort_heading($field);

        $columnId = get_column_sort_id($field);

        
        if ($columnId == "column_desc") {
            echo '<th class=" header headerSortDown">';
            //echo ' <i class="icon-arrow-down"></i>';
        } elseif ($columnId == "column_asc") {
            echo '<th class="header headerSortUp">';
            //echo ' <i class="icon-arrow-up"></i>';
        }else{
            echo '<th >';
        }
        if($sortable){
            echo '<a href="' . $url . '">' . $value;
            echo "</a></th>";
        }else{
             echo $value."</th>";
        
        }
    }
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    $i = 0;
    foreach ($columnValues as $columnvalue) {
        ?>
        <tr <?php echo ($i++ % 2 == 1) ? 'class="odd"' : ''; ?>>
            <?php
            $tdIndex = 1;
            foreach ($columnNames as $field => $value) {
                if ($tdIndex == 1) {
                    if ($columnvalue[$field] == null || trim($columnvalue[$field]) == '') {
                        $columnvalue[$field] = " . . . ";
                    }
                    $displayValue = '<a href="' . $columnvalue['record_url'] . '">' . $columnvalue[$field] . '</a>';
                } else {
                    $displayValue = $columnvalue[$field];
                }

                echo "<td>" . $displayValue . "</td>";
                $tdIndex++;
            }
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
    }

    function shn_form_get_html_filter_table($columnNames = null, $columnValues = null, $htmlFilterFields = null) {
        $requestObj = new shnRequest();
        $mod = $requestObj->getRequestModule();
        $action = $requestObj->getRequestAction();
        
        $cols = count($columnNames);
        ?>

    <form   action="<?php get_url($mod, $action) ?>" method="get">
        <input type="hidden" name="mod" value="<?php echo $mod ?>" />
        <input type="hidden" name="act" value="<?php echo $action ?>" />
       
                   <table class='table table-bordered table-striped table-hover'>
                        <thead>


                            <tr> 
                                <?php
                                foreach ($columnNames as $field => $value) {
                                    $url = get_sort_heading($field);

                                    $columnId = get_column_sort_id($field);

                                    if ($columnId == "column_desc") {
                                        echo '<th class=" header headerSortDown">';
                                        //echo ' <i class="icon-arrow-down"></i>';
                                    } elseif ($columnId == "column_asc") {
                                        echo '<th class="header headerSortUp">';
                                        //echo ' <i class="icon-arrow-up"></i>';
                                    }else{
                                        echo '<th >';
                                    }
                                    echo '<a href="' . $url . '">' . $value;
        
                                   
                                    echo "</a></th>";
                                }
                                ?>
                                <th><?php echo _t('Actions') ?></th>
                                
                            </tr>
                            <tr class="filter" id="filter_text">
                                <?php
                                foreach ($columnNames as $field => $value) {
                                    $label = ucwords(str_replace('_', ' ', $field));
                                    echo "<td id='td_" . $field . "'>" . $htmlFilterFields[$field] . "</td>";
                                    //echo "<th id='td_" . $field . "'><span id='span_" . $field . "' class='inactive'>" . $value . "</span>" . $htmlFilterFields[$field] . "</th>";
                                }
                                ?>
                                <td class="table_actions" >
                                        <button type="submit" name="filter" class="btn btn-mini">
  <i class="icon-filter"></i><?php echo _t('FILTER') ?>
</button>
                                       <a class="btn btn-mini" href="<?php get_url($mod, $action, null, array('reset' => 'true')); ?>">
                                        <i class="icon-remove"></i>  
                                            <?php echo _t('RESET') ?></a>
                                </td>

                            </tr>
                        </thead>
                        <?php
                        if ($columnValues != null && count($columnValues) != 0) {
                            echo "<tbody>";

                            $i = 0;
                            foreach ($columnValues as $columnvalue) {
                                ?>
                                <tr <?php echo ($i++ % 2 == 1) ? 'class="odd"' : ''; ?>>
                                   
                                    <?php
                                    $tdIndex = 1;

                                    foreach ($columnNames as $field => $value) {
                                        
                                        if ($tdIndex == 1) {
                                            if ($columnvalue[$field] == null || trim($columnvalue[$field]) == '') {
                                                $columnvalue[$field] = " . . . ";
                                            }
                                            $displayValue = '<a href="' . $columnvalue['record_url'] . '">' . $columnvalue[$field] . '</a>';
                                        } else if ($tdIndex == 2 && $columnvalue['entity_record_url'] != null) {
                                            if ($columnvalue[$field] == null || trim($columnvalue[$field]) == '') {
                                                $columnvalue[$field] = " . . . ";
                                            }
                                            $displayValue = '<a href="' . $columnvalue['entity_record_url'] . '" >' . $columnvalue[$field] . '</a>';
                                        }else if(isset($columnvalue[$field.'_record_url'])){
                                                $displayValue = '<a href="' . $columnvalue[$field.'_record_url'] . '" title="' . $columnvalue['record_number'] . '">' . $columnvalue[$field] . '</a>';
                                        
                                        }else {
                                            $displayValue = $columnvalue[$field];
                                        }

                                        echo "<td>" . $displayValue . "</td>";
                                        $tdIndex++;
                                    }
                                    ?>
                                     <td>
                                        <a class="btn btn-success btn-mini" href="<?php echo $columnvalue['record_url']?>" rel="tooltip" title="View">
                                            <i class="icon-zoom-in icon-white"></i>  
                                            <?php echo _t('View') ?>                       
                                        </a>
                                        <a class="btn btn-info btn-mini" href="<?php echo $columnvalue['record_edit_url']?>" rel="tooltip" title="Edit">
                                            <i class="icon-edit icon-white"></i>  
                                            <?php echo _t('Edit') ?>                                          
                                        </a>
                                        <a class="btn btn-grey btn-mini" href="<?php echo $columnvalue['record_delete_url']?>" rel="tooltip" title="Delete">
                                            <i class="icon-trash"></i> 
                                            <?php echo _t('Delete') ?>
                                        </a>
                                    </td>
                                </tr>
                                <?php
                                
                            }

                            echo "</tbody>";
                        } else {
                            shnMessageQueue::addInformation(_t('NO_RECORDS_WERE_FOUND_'));
                            echo shnMessageQueue::renderMessages();
                        }
                        ?>

                    </table>
                </form>
    <?php
}

function get_sort_heading($field) {
    $newGet = $_GET;

    if ($newGet['sort'] != $field) {
        unset($newGet['sort']);
    }

    if (!isset($newGet['sort'])) {
        $newGet['sortorder'] = 'asc';
    } else {
        if ($newGet['sortorder'] == 'asc')
            $newGet['sortorder'] = 'desc';
        else
            $newGet['sortorder'] = 'asc';
    }


    $newGet['sort'] = $field;
    $url = get_url($_GET['mod'], $_GET['act'], $_GET['view'], $newGet, null, true);


    return $url;
}

function get_column_sort_id($field) {
    if (isset($_GET['sort'])) {
        if ($_GET['sort'] == $field) {
            if ($_GET['sortorder'] == 'asc') {
                return 'column_asc';
            } else if ($_GET['sortorder'] == 'desc') {
                return 'column_desc';
            }
        }
    }
    return null;
}

function shn_form_get_html_labels($form, $return = true) {
    include_once(APPROOT . 'inc/lib_view.inc');
    $i = 0;
    $fields = array();
    ob_start();

    $compForm = compartmentalize_view_form($form);
    $classNumber = 2;
    foreach ($compForm as $form) {

        echo "<table class=' table table-bordered table-hover view$classNumber'>";
        $classNumber++;
        foreach ($form as $field_id => $options) {

            if ($options['map']['link_table'] != null) {
                $options['extra_opts']['value'] .= '<b>' . '  :  ' . loadLinkName($options['map']['link_table'], $options['map']['link_field'], $options['extra_opts']['value']) . '</b>';
            }

            $controler = $options['type'];
            $controler = 'shn_view_' . $controler;
            $controler .= (isset($options['map']['mlt']) && $options['map']['mlt'] == true) ? '_mlt' : '';
            if (function_exists($controler)) {
                $options['extra_opts']['mt'] = $options['map']['mt'];
                $options['extra_opts']['class'] = ($i++ % 2 == 1) ? 'odd' : 'even';
                $controler($options['label'], $options['extra_opts']['value'], $options['extra_opts']);
            }
        }
        echo "</table>";
    }
    echo "<div style='clear:both' ></div>";

    $html = ob_get_clean();
    if ($return) {
        $key = array_keys($form);
        return array($key[0] => $html);
    }
    else
        echo $html;
}

function loadLinkName($entity, $link_field, $record_number) {
    switch ($entity) {
        case 'event':
            $entity = new Event();
            break;
        case 'person' :
            $entity = new Person();
            break;
    }

    $entity->LoadfromRecordNumber($record_number);
    return $entity->$link_field;
}

function shn_form_get_html_doc_search_ctrl($columnNames = null, $columnValues = null, $htmlFilterFields = null) {
    ?>
    <table class="table table-bordered table-striped table-hover">
        <thead>
            <?php
            $count = 1;
            $no_of_column = 1;
            foreach ($columnValues as $columnvalue) {
                if ($count == 1) {
                    foreach ($columnNames as $field => $value) {
                        echo "<th>$value</th>";
                        $no_of_column++;
                    }
                    echo "<th>" . _t('ACTION') . "</th>";
                    echo "</tr>";
                }
                $count++;
            }
            ?>
        </thead>
        <tbody>
            <?php
            $i = 0;

            foreach ($columnValues as $columnvalue) {
                ?>
                <tr>
                    <?php
                    $tdIndex = 1;
                    foreach ($columnNames as $field => $value) {
                        if ($tdIndex == 1) {
                            $displayValue = '<a target="_blank" href="' . $columnvalue['record_url'] . '">' . $columnvalue[$field] . '</a>';
                        } else {
                            $displayValue = $columnvalue[$field];
                        }
                        echo "<td>" . $displayValue . "</td>";
                        $tdIndex++;
                    }
                    ?>
            <td><input type="checkbox" name="doc_id_list[]"
                       id="<?php echo $columnvalue['doc_id'] ?>"
                       value="<?php echo $columnvalue['doc_id'] . '_' . $columnvalue['title']; ?>" />
            </td>
        </tr>
        <?php
    }
    ?>
    </tbody>
    <tr>
        <td colspan="<?php echo $no_of_column; ?>" align="right"><input type="submit" class="btn " value="select" id="selected_doc" name="selected_doc"
                onclick="selected_doclist(this.form,'document_field'); return false;" />
        </td>
    </tr>
    </table>
    <?php
}

function shn_form_get_html_person_search_ctrl($columnNames = null, $columnValues = null, $htmlFilterFields = null, $mod = null, $act = null) {
    ?>
    <table class="table table-bordered table-striped table-hover">
        <thead>
            <?php
            $count = 1;
            foreach ($columnValues as $columnvalue) {
                if ($count == 1) {
                    echo "<tr>";
                    foreach ($columnNames as $field => $value) {
                        echo "<th>$value</th>";
                    }
                    echo "<th>" . _t('ACTION') . "</th>";
                    echo "</tr>";
                }
                $count++;
            }
            ?>
        </thead>
        <tbody>
            <?php
            $i = 0;

            foreach ($columnValues as $columnvalue) {
                ?>
                <tr <?php echo ($i++ % 2 == 1) ? 'class="odd"' : ''; ?>>
                    <?php
                    $tdIndex = 1;
                    foreach ($columnNames as $field => $value) {
                        if ($tdIndex == 1) {
                            $displayValue = '<a target="_blank" target="_blank" href="' . $columnvalue['record_url'] . '">' . $columnvalue[$field] . '</a>';
                        } else {
                            $displayValue = $columnvalue[$field];
                        }
                        echo "<td>" . $displayValue . "</td>";
                        $tdIndex++;
                    }
                    ?>
                    <td><a
                            href="<?php echo get_url($mod, $act, null, array('inv_id' => $_GET['inv_id'],'information_id' => $_GET['information_id'], 'intervention_id' => $_GET['intervention_id'], 'person_id' => $columnvalue['person_record_number'])); ?>"><?php echo _t('SELECT'); ?>
                        </a></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
    <?php
}

function shn_form_get_html_event_search_ctrl($columnNames = null, $columnValues = null, $htmlFilterFields = null, $mod = null, $act = null) {
    ?>
    <table class="table table-bordered table-striped table-hover">
        <thead>
            <?php
            $count = 1;
            foreach ($columnValues as $columnvalue) {
                if ($count == 1) {
                    echo "<tr>";
                    foreach ($columnNames as $field => $value) {
                        echo "<th>$value</th>";
                    }
                    echo "<th>" . _t('ACTION') . "</th>";
                    echo "</tr>";
                }
                $count++;
            }
            ?>
        </thead>
        <tbody>
            <?php
            $i = 0;

            foreach ($columnValues as $columnvalue) {
                ?>
                <tr <?php echo ($i++ % 2 == 1) ? 'class="odd"' : ''; ?>>
                    <?php
                    $tdIndex = 1;
                    foreach ($columnNames as $field => $value) {
                        if ($tdIndex == 1) {
                            $displayValue = '<a target="_blank" href="' . $columnvalue['record_url'] . '">' . $columnvalue[$field] . '</a>';
                        } else {
                            $displayValue = $columnvalue[$field];
                        }
                        echo "<td>" . $displayValue . "</td>";
                        $tdIndex++;
                    }
                    ?>
                    <td><a
                            href="<?php echo get_url($mod, $act, null, array('event_id' => $columnvalue['event_record_number'], 'coeid' => $_GET['coeid'])); ?>"><?php echo _t('SELECT'); ?>
                        </a></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
    <?php
}
