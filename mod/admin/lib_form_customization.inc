<?php
function form_customization_get_reset_fields()
{            
            $reset_fields = array( 		
                                'reset_label'=>'field_label' ,
                                'reset_visible_new'=>'visible_new',
                                'reset_visible_view'=>'visible_view',

                                'reset_visible_browse'=>'visible_browse',
                                'reset_visible_search'=>'visible_search',
                                'reset_visible_search_display'=>'visible_search_display',  

                                'reset_order'=>'label_number',
                                'reset_clari'=>'clar_note',                							
                                'reset_required'=>'required',
                                'reset_validation'=>'validation'        
                                 );
    return $reset_fields;
}

function form_customization_reset_all($entity)
{
    global $global;
    $entity = $global['db']->qstr($entity);
    $sql="UPDATE data_dict AS N JOIN data_dict_original AS O ON N.field_number = O.field_number 
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



function form_customization_get_field_table($entity){
    switch($entity)
    {
        case 'person':
            $res = Browse::getFields('person');
            break;
        case 'event':
            $res = Browse::getFields('event');
            break;
        case 'act':
            $res = Browse::getFields('act');
            break;
        case 'arrest':
            $res = Browse::getFields('arrest');
            break;
        case 'killing':
            $res = Browse::getFields('killing');
            break;
        case 'destruction':
            $res = Browse::getFields('destruction');
            break;
        case 'torture':
            $res = Browse::getFields('torture');
            break;
        case 'chain_of_events':
            $res = Browse::getFields('chain_of_events');
            break;
        case 'involvement':
            $res = Browse::getFields('involvement');
            break;
        case 'information':
            $res = Browse::getFields('information');
            break;
        case 'intervention':
            $res = Browse::getFields('intervention');
            break;
        case 'biographic_details':
            $res = Browse::getFields('biographic_details');
            break;
        case 'supporting_docs_meta':
            $res = Browse::getFields('supporting_docs_meta');
            break;
        case 'address':
            $res = Browse::getFields('address');
            break;  
    }
    return $res;
}



function form_customization_reset_field($entity , $field){
    global $global;
    //var_dump($entity);
    $field  = addslashes_deep($field);
    $entity = $global['db']->qstr($entity);
    $sql="Update data_dict as N join data_dict_original as O 
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



function form_customization_process_entity_form($entity){
    global $global;
    global $conf;
    $records = Browse::getFieldsIds($entity);
    //var_dump($records);
    foreach($records as $record){
        $fieldValues = array();
        $l10nValues = array();
        $recordNumber = $record['field_number'];

        if(isset($_POST['label'])){
            //dont let the lable be empty
            if(isset($_POST['label_' . $recordNumber]) && $_POST['label_' . $recordNumber]!='')
                $fieldValues['field_label'] = $_POST['label_' . $recordNumber];
            else{
                shnMessageQueue::addError("\"{$record['field_name']}\" "._t('LABEL_CANNOT_BE_EMPTY__ENTER_A_VALUE_'));
                continue;
            }
            
            if(is_locale_changed() && isset($_POST['label_l10n_' . $recordNumber]) && $_POST['label_l10n_' . $recordNumber]!='')
            {
                $l10nValues['msgstr'] = "'{$_POST['label_l10n_' . $recordNumber]}'";
                $l10nValues['msgid']  = "'{$recordNumber}'";
                $l10nValues['locale'] = "'{$conf['locale']}'";
                $global['db']->Replace('data_dict_l10n',$l10nValues,array('msgid','locale')); 
            }
        }

        if( (strtolower( $record['essential'] ) == 'n') ){            	
            if(isset($_POST['visible_new']))        
                $fieldValues['visible_new'] =  strtolower($_POST['visible_new_' . $recordNumber])=='y'?'y':'n' ;	            
        }
        
        if(isset($_POST['visible_view'])){
            $fieldValues['visible_view'] = strtolower( $_POST['visible_view_' . $recordNumber] ) =='y'?'y':'n';
        
        }
        
        if(isset($_POST['searchable']))
            $fieldValues['visible_search'] = strtolower( $_POST['searchable_' . $recordNumber] ) =='y'?'y':'n';
        
        if(isset($_POST['search_results']))
            $fieldValues['visible_search_display'] = strtolower( $_POST[ 'search_results_' . $recordNumber] ) =='y'?'y':'n';
        
        if(isset($_POST['visible_browse']))
            $fieldValues['visible_browse'] = strtolower( $_POST['visible_browse_' . $recordNumber] ) =='y'?'y':'n';
        
        if(isset($_POST['order']))
            $fieldValues['label_number'] = $_POST['order_' . $recordNumber];
        
        if(isset($_POST['clari']))
            $fieldValues['clar_note'] = strtolower( $_POST['clari_' . $recordNumber] ) =='y'?'y':'n' ;
        
        if(isset($_POST['required']))
            $fieldValues['required'] = strtolower( $_POST['required_' . $recordNumber] ) =='y'?'y':'n';
        
        if(isset($_POST['validation']))
            $fieldValues['validation'] = $_POST['validation_' . $recordNumber];

        $global['db']->AutoExecute('data_dict',$fieldValues,'UPDATE' , "field_number = '$recordNumber'"); 
    }
}
    
function form_customization_add_field( $entity , $field_name,  $field_number , $order , $field_label , $field_type , $field_mysql_type = null  ){
    
	switch($field_type){
		case 'text':
		$field_mysql_type = "VARCHAR(100)";
		break;
		case 'textarea':
			$field_mysql_type = "TEXT";
			break;
		case 'date':
			$field_mysql_type = "DATE";
			break;		
	}
	

    //add field to Data Dict
    $dbQuery = new Browse();
    try{
    	$dbQuery->ExecuteNonQuery("INSERT INTO data_dict ( field_number , field_label , field_type, label_number, field_name , entity ) VALUES ('$field_number' , '$field_label' , '$field_type' , '$order' , '$field_name' , '$entity' ) ");
    
	    //alter table to add the new field
	    //ALTER TABLE `event` ADD `test_field` VARCHAR( 100 ) NOT NULL 
	    $dbQuery->ExecuteNonQuery("ALTER TABLE `$entity` ADD `$field_name` $field_mysql_type ");
    }catch(Exception $e){
    	return false;	
    }
    return true;
}

function form_customization_field_name_exists($field_name , $entity){
	$dbQuery = new Browse();
	$resultSet = $dbQuery->ExecuteQuery("SELECT * FROM data_dict where field_name='$field_name' AND entity='$entity'");
	//var_dump($resultSet,sizeof($resultSet));
	if( sizeof($resultSet) > 0 )
	return true;
	else	
    return false;
}

function form_customization_field_number_exists($field_number){
	$dbQuery = new Browse();
	$resultSet = $dbQuery->ExecuteQuery("SELECT * FROM data_dict where field_number='$field_number'");
	//var_dump($resultSet,sizeof($resultSet));
	if( sizeof($resultSet) > 0 )
	return true;
	else	
    return false;
    
}

function form_customization_update_help($post){
    global $global;global $conf;
    $dataArray['field_number'] = $global['db']->qstr($post['field_number']);
    $dataArray['definition'] =  $global['db']->qstr($post['definition']);
    $dataArray['guidelines'] =  $global['db']->qstr($post['guidelines']);
    $dataArray['entry'] =  $global['db']->qstr($post['entry']);
    $dataArray['examples'] =  $global['db']->qstr($post['examples']);
    $global['db']->Replace('help', $dataArray , 'field_number' , false);

    if(is_locale_changed()){
        $dataArray['field_number'] =  $global['db']->qstr($post['field_number']);
        $dataArray['definition'] =  $global['db']->qstr($post['l10n_definition']);
        $dataArray['guidelines'] =  $global['db']->qstr($post['l10n_guidelines']);
        $dataArray['entry'] =  $global['db']->qstr($post['l10n_entry']);
        $dataArray['examples'] =  $global['db']->qstr($post['l10n_examples']);
        $dataArray['locale'] =  $global['db']->qstr($conf['locale']);
        $global['db']->Replace('help_l10n', $dataArray , array('field_number','locale') , false);
    }
}
