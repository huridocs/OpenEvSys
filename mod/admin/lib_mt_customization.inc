<?php

 
$customization_form = array(
    'mt_select'=>array(
        'type'=>'select',
        'label'=>_t('SELECT_A_MICRO_THESAURUS') , 
        'extra_opts'=>array(  
            'options' => $options ,
        	'value' => $_REQUEST['mt_select']
        )
    ),
    'change_mt' => array(
        'type'=>'submit',
        'label'=>_t('SELECT')
    ) 
);



function mt_customization_update($msgid , $msgstr , $locale)
{
    global $conf;
    global $global;
    //sanitize strings
    if($locale == $conf['fb_locale'])
    {
        $msgid = $global['db']->qstr($msgid);
        $msgstr =  $global['db']->qstr($msgstr);
        $sql = "UPDATE mt_vocab SET english=$msgstr WHERE vocab_number = $msgid";
        $res = $global['db']->Execute($sql);
        if($res)
            return true;
        else
            return false;
    }
    else if($locale == $conf['locale'])
    {
        $l10nValues['msgid'] = $global['db']->qstr($msgid);
        $l10nValues['msgstr'] =  $global['db']->qstr($msgstr);
        $l10nValues['locale'] =  $global['db']->qstr($locale);
        $res = $global['db']->Replace('mt_vocab_l10n',$l10nValues,array('msgid','locale')); 
        if($res)
            return true;
        else
            return false;
    }
}

function mt_customization_delete($mt_select,$vocab_number_list)
{	
    $add = true;
    if(is_array($vocab_number_list)){
        foreach($vocab_number_list as $vocab_number){ 
            try{
                $mtTerms = new MtTerms();                  
                //if the mt is a tree delete children as well 
                if(mt_customization_is_tree($mt_select)){   
                    $vocab_number = mt_customization_get_branch_select($vocab_number); 
                }
                $mtTerms->DeleteFromRecordNumber($mt_select,$vocab_number);
	        }catch(Exception $e)
            {
                if($add){
                    shnMessageQueue::addInformation(_t('UNABLE_TO_REMOVE_SOME_MICRO_THESAURUS_TERMS_BECAUSE_THEY_ARE_ALREADY_USED_IN_RECORDS_'));
                    $add = false;
                }
            }
        }
    }
}



function mt_customization_add_terms($mt_term){
    global $global;

    $fieldValues = array();
    
    $mtIndex = new MtIndex();
    $mt_table = 'mt_'.$mt_term . '_' . $mtIndex->getTermforCode($mt_term);
    $index = 0 ;
    $mt_term = (strlen($mt_term)== 1)? '0'.$mt_term : $mt_term;
    if(is_array($_POST['new_huricode'])){
        foreach($_POST['new_huricode'] as $huri_code){
            //$fieldValues['vocab_number'] = $_POST['vocab_number'][$index];
            $recordNumber = $huri_code.$mt_term ;
            $huri_code = trim($huri_code);
            $huri_term = trim($_POST['new_term'][$index]);
            
            if($huri_code == '')continue;

            $fieldValues['vocab_number'] = $recordNumber;
            $fieldValues['list_code'] = $mt_term;        	
            $fieldValues['huri_code'] = $huri_code;
            $fieldValues['english'] = $huri_term;                 
            if(!mt_customization_validate_mtterm($mt_term, $huri_code , $huri_term))
                continue;
            try{        	            
                //svar_dump($fieldValues);
                $global['db']->AutoExecute('mt_vocab',$fieldValues,'INSERT');
                $global['db']->AutoExecute($mt_table,$fieldValues,'INSERT');

                if(is_locale_changed() && isset($_POST['new_term_l10n'][$index]) && $_POST['new_term_l10n'][$index] != '')
                {
                    $l10nValues['msgid'] = "'$recordNumber'";
                    $l10nValues['msgstr'] =  "'{$_POST['new_term_l10n'][$index]}'";
                    $l10nValues['locale'] = "'{$conf['locale']}'";
                    $res = $global['db']->Replace('mt_vocab_l10n',$l10nValues,array('msgid','locale')); 
                }
            }catch(Exception $e)
            {
                shnMessageQueue::addError(_t('ERROR_OCCURRED_WHILE_CREATING_HURITERM_')."  [ $huri_code : $huri_term ]");
            }
            $index++;
        }
    }
}


function mt_customization_is_tree($mt_code)
{
    global $global;
    $mt_code = (strlen($mt_code)==1)? "0$mt_code" : $mt_code;
    $sql = "SELECT field_type FROM data_dict WHERE list_code = '$mt_code'";
    $res = $global['db']->GetOne($sql);
    if($res == 'mt_tree')
        return true;
    else
        return false;
}

function mt_customization_has_children(){
    global $global;
    return true;
}



function mt_customization_get_branch_select($vocab_number){
	$huri_code = substr($vocab_number,0,12); 
	$left = rtrim($huri_code,'0');
    $left = (strlen($left) % 2 == 1 )? $left.'0' : $left;
	$huri_code = str_pad($left, 12 , "_");
	$huri_code = $huri_code . substr($vocab_number,12, 14);
    return $huri_code;
}

function mt_customization_get_full_huri_code($vocab_number){
	$vocab_number=substr($vocab_number,0,12); 
	$left = rtrim($vocab_number,'0');
	$huri_code = str_pad($left, 12 , "_");
	return $huri_code;
}

function mt_customization_get_filter_string($parent='')
{
    $left = rtrim($parent,'0');
    if(strlen($left)%2 == 1)
        $left = $left . '0';

    $filter = $left.'__';
    $filter = str_pad($filter, 12 , "0"); 
    return $filter;
}


function mt_customization_validate_mtterm($list_code,$huri_code,$huri_term, $level= null)
{
    global $global;
    //check if right length and if it is a number
    if(!ereg("[0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]",$huri_code)){
        shnMessageQueue::addError(_t('INVALID_HURICODE__IT_SHOULD_BE_A_12_DIGIT_NUMBER_')."  [ $huri_code : $huri_term ]");
        return false;
    }
    //check if the huri term is empty
    if(isset($huri_term) && $huri_term == ''){
        shnMessageQueue::addError(_t('HURITERM_CAN_NOT_BE_EMPTY_')."  [ $huri_code : $huri_term ]");
        return false;
    }
    //check if already exist
    /*$sql = "SELECT vocab_number FROM mt_vocab WHERE (huri_code='$huri_code' AND list_code=$list_code) OR (english like '$huri_term' AND  list_code=$list_code)";
    $vocab_number = $global['db']->GetOne($sql);

    if(isset($vocab_number) && $vocab_number!=''){
        shnMessageQueue::addError(_t('FOLLOWING_HURITERM_ALREADY_EXIST_')."  [ $huri_code : $huri_term ]");
        return false;
    }*/

    //check tree level
    return true;
}

//Visibility
function mt_visibility_update($msgid , $msgstr )
{
    global $conf;
    global $global;
    
    $list_code = substr( $msgid , 12,2);
    $huri_code = rtrim(substr( $msgid , 0,12) , '0');    
    //var_dump($msgid,$huri_code,$list_code);
    $sql = "UPDATE mt_vocab SET visible='$msgstr' WHERE vocab_number LIKE '$huri_code%$list_code'";
    //var_dump( $sql);
    $res = $global['db']->Execute($sql);
    
    if($res){
    //var_dump('res true');
        return true;
    }else
        return false;
    
}
