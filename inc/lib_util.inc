<?php
/**
 * Main utility libraries which will provide various utility functions
 *
 * Copyright (C) 2009
 *   Respere Lanka (PVT) Ltd. http://respere.com, info@respere.com
 * Copyright (C) 2009
 *   Human Rights Information and Documentation Systems,
 *   HURIDOCS), http://www.huridocs.org/, info@huridocs.org
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @auther  H J P Fonseka <jo@respere.com>
 * @package Framework
 * 
 */

/**
 * __autoload function will be 
 * 
 * @param mixed $class_name 
 * @access protected
 * @return void
 */
class AutoLoadClass{
    public static function __autoload($class_name)
    {
        if (file_exists(APPROOT.'inc/'.$class_name.'.class.php') ){
            require_once(APPROOT.'inc/'.$class_name.'.class.php');
        }
        if(file_exists(APPROOT.'data/'.$class_name.'.php') ){
            require_once(APPROOT.'data/'.$class_name.'.php');
        }
        if(file_exists(APPROOT.'3rd/Zend/ '.$class_name.'.php') ){
            require_once(APPROOT.'3rd/Zend/ '.$class_name.'.php');
        }
        if(file_exists(APPROOT.'data/'.$class_name.'.php') ){
            require_once(APPROOT.'data/'.$class_name.'.php');
        }
        
    }
}
spl_autoload_register(array('AutoLoadClass', '__autoload'));


/**
 * get_uri 
 * 
 * @param string $module 
 * @param string $action 
 * @param mixed $args 
 * @param string $stream 
 * @param mixed $id 
 * @param mixed $return 
 * @access public
 * @return void
 */
function get_url($module='home',$action='default',$view = null,$args=null,$stream=null,$return = false)
{
    global $global;

    
    if(is_array($global['url_args'])&& is_array($args))
        $args = array_merge($global['url_args'],$args);

    if(!is_array($args))
        $args = $global['url_args'];

    $act = (isset($action))?"&amp;act=$action":null;
    $view = (isset($view))?"&amp;view=$view":null;
	$stream = (isset($stream))?"&amp;stream=$stream":null;
    if(is_array($args))
        foreach($args as $id=>$value){
            if(is_array($value))
                foreach($value as $val)
                    $al .= "&amp;{$id}[]=$val";
            else if(isset($value))   
                $al .= "&amp;$id=$value";
        }

    if($conf['mod_rewrite']=='true')
        $uri = "index.php/$module/$action/$id.$stream?";
    else
        $uri = "index.php?mod=$module$act$view$stream";

    if($return)
        return $uri.$al;
    else
        echo $uri.$al;
}


function set_url_args($key , $value)
{
    global $global;
    $global['url_args'][$key]=$value;
}

function clear_url_args()
{
    global $global;
    unset($global['url_args']);
}



/**
 * Since $_SERVER['REQUEST_URI'] is only available on Apache, we
 * generate an equivalent using other environment variables.
 */
function request_uri() {

  if (isset($_SERVER['REQUEST_URI'])) {
    $uri = $_SERVER['REQUEST_URI'];
  }
  else {
    if (isset($_SERVER['argv'])) {
      $uri = $_SERVER['SCRIPT_NAME'] .'?'. $_SERVER['argv'][0];
    }
    elseif (isset($_SERVER['QUERY_STRING'])) {
      $uri = $_SERVER['SCRIPT_NAME'] .'?'. $_SERVER['QUERY_STRING'];
    }
    else {
      $uri = $_SERVER['SCRIPT_NAME'];
    }
  }

  return $uri;
}


function change_tpl($template){
    $controller = shnFrontController::getController();
    $controller->view->setTemplate($template);
}



function get_mt_term($huricode)
{
    $mtterms= new MtTerms();
    $mtterms->LoadfromVocabNumber($huricode);
    return $mtterms->label;    
}

function set_redirect_header($module='home',$action='default',$view = null,$args=null,$stream='html',$return = false)
{
    global $global;

    
    if(is_array($global['url_args'])&& is_array($args))
        $args = array_merge($global['url_args'],$args);

    if(!is_array($args))
        $args = $global['url_args'];


    $view = (isset($view))?"&view=$view":null;

    if(is_array($args))
        foreach($args as $id=>$value)
            $al .= "&$id=$value"; 

    if($conf['mod_rewrite']=='true')
        $uri = "index.php/$module/$action/$id.$stream?";
    else
        $uri = "index.php?mod=$module&act=$action$view";

    $uri = $uri.$al;
    header("location: ".$uri);
    return $uri;
}


function array_to_list($array)
{
    if(is_array($array)){
        foreach($array as $item)
            echo "$item , ";
    }
    else
        echo $array;
}

function get_record_url($record_number , $entity){
    switch($entity){
        case 'event':
            return "index.php?mod=events&act=get_event&eid=$record_number";        
        case 'person':
            return "index.php?mod=person&act=person&pid=$record_number";
        case 'victim':
            return "index.php?mod=person&act=person&pid=$record_number";
        case 'perpetrator';
        	return "index.php?mod=person&act=person&pid=$record_number";
        case 'source':
        	return "index.php?mod=person&act=person&pid=$record_number";
        case 'intervening_party':
        	return "index.php?mod=person&act=person&pid=$record_number";
        case 'act':
            return "index.php?mod=events&act=vp_list&act_id=$record_number&type=act";
        case 'involvement':
            return "index.php?mod=events&act=vp_list&inv_id=$record_number&type=inv";
        case 'information':
            return "index.php?mod=events&act=src_list&type=information&information_id=$record_number";    
        case 'intervention':
        	return "index.php?mod=events&act=intv_list&type=intv&intervention_id=$record_number";
		case 'supporting_docs_meta':
            return "index.php?mod=docu&act=view_document&doc_id=$record_number";
        case 'biographic_details':
        	return "index.php?mod=person&act=biography_list&type=rp&biography_id=$record_number";            
        default:
            return;
    }
}

function get_table_for_entity( $entity){    
    
    switch($entity){
        case 'victim':
            return "person";  
        case 'perpetrator':
            return "person";  
        case 'source':
            return "person";  
        case 'intervening_party':
            return "person";  
        default:
            return $entity ;            
            
    }    
}

function get_primary_key( $entity){    
    
    switch($entity){
        case 'event':
            return "event_record_number";        
        case 'person':
            return "person_record_number";          
        case 'act':
            return "act_record_number";
        case 'victim':
            return "person_record_number";  
        case 'perpetrator':
            return "person_record_number";  
        case 'source':
            return "person_record_number";  	
        case 'intervening_party':
            return "person_record_number";  
        case 'supporting_docs_meta':
            return "doc_id";  
        default:
            return $entity ."_record_number";            
            
    }    
}

function get_primary_entity($entity){
        switch($entity){       
        case 'act':
            return "event";
        case 'victim':
            return "event";  
        case 'perpetrator':
            return "event";  
        case 'source':
            return "event";  	
        case 'intervening_party':
            return "event";   
        default:
            return null; 
        }
}

function set_links_in_recordset($recordset , $entity){
	
    $pk = get_primary_key($entity);
    
    $recordArray = array();

    $primary_entity = get_primary_entity($entity);
    
    foreach($recordset as $record){
        if($primary_entity != null){
            $primary_entity_record_number = $record[get_primary_key($primary_entity)] ;
        }else{
            $primary_entity_record_number = null;
        }
        
        $record['record_url']   = get_record_url(  $record[$pk] , $entity , $primary_entity_record_number );     
        $recordArray[] = $record;
        
        //var_dump( $entity , $record); 
    }
    

    
    return $recordArray;
}

function getMtFields( $form_array ){

    $mtTermArray = array();
    foreach($form_array as $name=>$field){
         
        if( $field['map']['mt'] != '0' ){
            $mtTermArray[] = $field['map']['field'];                 
        }           
        
    }       
    return $mtTermArray;
        	
}

function set_huriterms_in_record_array($formArray , &$valuesArray){
    
    $mtTerms = getMtFields($formArray);
   
    if($mtTerms != null && count($mtTerms) > 0){
        foreach($mtTerms as $term){
        	
            foreach($valuesArray as &$record){
            	$termsArray = split(' , ' , $record[$term]);
            	//var_dump($termsArray);
            	if( is_array($termsArray) && sizeof($termsArray) > 1  ){
            		
	            		$termList = null;
	            		$termList .= '<ul>';
	            		foreach($termsArray as $recordElement){
	            			$termList .= '<li>';
	            			$mtName = get_mt_term(trim( $recordElement));
	                        if($mtName != null)
	                        //var_dump($mtName);
	                            $termList .= $mtName ;
	                            
	                        $termList .= '</li>';    
	            		}
	            		$termList .= '</ul>';
	            		if($termList!=null){
	                        $record[$term] = $termList; 
	            		}
            		
            	}else{
                    $mtName = get_mt_term( $record[$term] );
                    if($mtName != null)
                        $record[$term] = $mtName;
            	}
            }
        }
    }
}

	function formArrayRefine(&$formArray){
		
	    foreach($formArray as &$field){
	        if($field['type'] == 'hidden'){
	            $field['type'] = 'text';
	        }
	        $field['extra_opts']['clari'] = null;
	        $field['extra_opts']['help'] = null;		
	        $field['extra_opts']['required'] = null;        
	    }
	}

	function mergeAddressFields(&$valuesArray){
		
		foreach( $valuesArray as &$valuesField){
		$addressList = null;
		$addressList .= "<ul>";
		if($valuesField['address_type']!=null)
		$addressList = $addressList . "<li>" . _t('ADDRESS_TYPE') . ' : ' . get_mt_term($valuesField['address_type']) . "</li>" ;
		if($valuesField['address']!=null)
		$addressList = $addressList . "<li>" . _t('ADDRESS') . ' : ' . $valuesField['address'] . "</li>";
		if($valuesField['country']!=null)
		$addressList = $addressList . "<li>" . _t('COUNTRY') . ' : ' . get_mt_term($valuesField['country']) . "</li>";
		if($valuesField['phone']!=null || $valuesField['cellular']!=null)			
		$addressList = $addressList . "<li>" . _t('PHONE') . ' : ' . $valuesField['phone'] . ' | ' .  _t('CELLULAR') . ' : ' . $valuesField['cellular'] . ' | ' .  _t('FAX') . ' : ' . $valuesField['fax'] .   "</li>";
		if($valuesField['email']!=null || $valuesField['web']!=null)
		$addressList = $addressList . "<li>" . _t('EMAIL') . ' : ' . $valuesField['email'] . ' | ' .  _t('WEBSITE') . ' : ' . $valuesField['web'] . "</li>";
		if($valuesField['start_date']!=null || $valuesField['end_date']!=null)
		$addressList = $addressList . "<li>" . _t('START_DATE') . ' : ' . $valuesField['start_date'] . ' | ' .  _t('END_DATE') . ' : ' . $valuesField['end_date'] . "</li>";
		
		$addressList .= "</ul>";
		
		$valuesField['person_addresses'] = $addressList;
		}
		
		
		
	}
	



function is_locale_changed()
{
    global $conf;
    if($conf['fb_locale']!=$conf['locale'])
        return true;
    return false;
}

function is_management_field($formField){

    if($formField['field_number'] == "0172"){
        return true;
    }
    if($formField['type'] == 'text' || $formField['type'] == 'date'){
		$fld_name = $formField['map']['field'];
		if(
				$fld_name=='entity_type' ||
				$fld_name=='entity_id' ||
				$fld_name=='date_received' ||
				$fld_name=='date_of_entry' ||
				$fld_name=='entered_by' ||
				$fld_name=='project_title' ||
				/*$fld_name=='comments' ||*/
				$fld_name=='record_grouping' ||
				$fld_name=='date_updated' ||
				$fld_name=='updated_by' ||
				$fld_name=='monitoring_status'
				
				){
			return true;
		}
	}

	
	return false;
	/*if($formField['type'] == 'document' || $formField['type'] == 'mt_select' || $formField['type'] =='mt_tree' || $formField['type'] =='checkbox' || $formField['type'] =='radio'){
            return false;
    }
    $field_number = $formField['field_number'] %100;
    if( $field_number >=  60 && $field_number< 80 && !($field_number == 67 || $field_number == 66 )){
            return true;
    }else{
            return false;
    }*/
}


function is_mt_field($entity, $field){
    global $global;
    $entity = $global['db']->qstr($entity);
    $field = $global['db']->qstr($field);
    $sql = "SELECT list_code FROM data_dict WHERE field_name = $field AND entity = $entity";
    $list_code = $global['db']->GetOne($sql);
    if($list_code == 0)
        return false;
    else
        return true;
}


function data_uri($file, $mime) {
    $contents = file_get_contents($file);
    $base64 = base64_encode($contents);
    return "data:$mime;base64,$base64";
}

function get_chaintype_reverse($type){
    $types = array("01000000000022"=>"22000000000022",
        "02000000000022"=>"21000000000022",
        "03000000000022"=>"23000000000022",
        "11000000000022"=>"11000000000022",
        "21000000000022"=>"02000000000022",
        "22000000000022"=>"01000000000022",
        "23000000000022"=>"03000000000022");
    if($types[$type]){
        return $types[$type];
    }
    return $type;
}
function get_biography_reverse($type){
    $types = array("01010300000021"=>"01010400000021",
        "01010400000021"=>"01010300000021",
        "01020300000021"=>"01020400000021",
        "01020400000021"=>"01020300000021",
        "01040200000021"=>"01040300000021",
        "01040300000021"=>"01040200000021",
        "01060100000021"=>"01060200000021",
        "01060200000021"=>"01060100000021",
        "01060201000021"=>"01060100000021",
        "21010000000021"=>"21020000000021",
        "21020000000021"=>"21010000000021");
    if($types[$type]){
        return $types[$type];
    }
    return $type;
}