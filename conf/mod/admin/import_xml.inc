<?php

global $xsd;
global $global;
global $errors;
$global['export_instance'];
$global['export_date'];
$global['$export_time'];

$errors = array();
$xsd = new DOMDocument();
$xsd->load(APPROOT.'res/events.xsd');

$dom = new DOMDocument();
$return = $dom->load($_FILES['xml']['tmp_name']);
if(!$return){
    shnMessageQueue::addError(_t('THE_DOCUMENT_UPLOADED_WAS_NOT_A_VALID_XML_DOCUMENT_'));
    return 1;   
}

$dom = $dom->getElementsByTagName('events')->item(0);

$meta_data = $dom->getElementsByTagName('meta_data')->item(0); 
if($meta_data){
	$global['export_instance']=$meta_data->getElementsByTagName('instance')->item(0)->nodeValue;
	$global['export_date']=$meta_data->getElementsByTagName('date')->item(0)->nodeValue;
	$global['$export_time']=$meta_data->getElementsByTagName('time')->item(0)->nodeValue;
}else {
	shnMessageQueue::addError(_t('THE_DOCUMENT_UPLOADED_WAS_NOT_A_VALID_XML_DOCUMENT_'));
    return 1; 
}
$persons = $dom->getElementsByTagName('person');
foreach($persons as $person){
    $p = new Person();
    import_object($person,$p);
    //var_dump($p->person_record_number);

    $addresses = $person->getElementsByTagName('address');
    foreach($addresses as $address){
        if($address->parentNode !== $person )
            continue;
        $a = new Address();
        $a->person = $p->person_record_number;
        import_object($address,$a);
        //var_dump($a->address_record_number);
    }
}

foreach($persons as $person){
    $key = $person->getElementsByTagName('person_record_number')->item(0)->nodeValue;
    $biographic_details = $person->getElementsByTagName('biographic_details');
    foreach($biographic_details as $biographic_detail){
        $bd = new BiographicDetail();
        $bd->person = $key;
        import_object($biographic_detail,$bd);
        //var_dump($bd);
    }
}


$events = $dom->getElementsByTagName('event');
foreach($events as $event){
    if($event->parentNode !== $dom )
        continue;
    $e = new Event();
    import_object($event,$e);
    //var_dump($e->event_record_number);

    $acts = $event->getElementsByTagName('act');
    foreach($acts as $act){
        $a = new Act();
        $a->event = $e->event_record_number;
        import_object($act,$a);
        //var_dump($a->act_record_number);

        $involvements = $act->getElementsByTagName('involvement');
        foreach($involvements as $involvement){
            $i = new Involvement();
            $i->act = $a->act_record_number;
            $i->event = $e->event_record_number;
            import_object($involvement,$i);
            //var_dump($i);
        }
    }
}


foreach($events as $event){
    $key  = $event->getElementsByTagName('event_record_number')->item(0)->nodeValue;
    $coes = $event->getElementsByTagName('chain_of_events');
    foreach($coes as $coe){
        $c = new ChainOfEvents();
        $c->event = $key;
        import_object($coe,$c);
    }
}


$interventions = $dom->getElementsByTagName('intervention');
foreach($interventions as $intervention){
    $in = new Intervention();
    import_object($intervention,$in);
    //var_dump($in);
}


$information = $dom->getElementsByTagName('information');
foreach($information as $info){
    $inf = new Information();
    import_object($info,$inf);
    //var_dump($inf);
}
return 0;


function import_object($xml,$object){
    global $xsd;
    $object_type = $object->getEntityType();
    $list_codes  = get_list_codes($object_type);

    $type = fetch_complex_type($object_type);

    if(!isset($type))return;
    $entity_key = $object_type.'_record_number';
    $elements = $type->getElementsByTagName('element');

    $values = $xml->getElementsByTagName($entity_key);
    $key = $object->$name = $values->item(0)->nodeValue;

    try{
        $object->LoadFromRecordNumber($key);
        $object->LoadRelationships();
    }catch(shnAccessDeny $e)
    {
        //skip
    }

    foreach($elements as $element){
        $name = $element->getAttribute('name');
        $map  = $element->getAttribute('map');
        $map = (isset($map) && $map!='')?$map:$name;
//        if(property_exists($object,$map)){
            $values = get_imidiate_child_nodes($xml,$name);
            $name = (isset($map) && $map!='')?$map:$name;
            if(count($values) == 0)
                continue;
            else if($element->getAttribute('type')=='huricode' && $element->getAttribute('maxOccurs')=="unbounded"){
                $multy_values = array();
                foreach($values as $node){
                    if(isset($node->nodeValue) && $node->nodeValue !=''){
                        $mtTerm = MtFieldWrapper::getMTObject($name , $object_type);        
                        $mtTerm->vocab_number = $node->nodeValue.$list_codes[$name];
                        $mtTerm->record_number = $key;
                        array_push($multy_values,$mtTerm);
                    }
                }
                $object->$name = $multy_values;
            }
            else if(count($values) == 1 && isset($values[0]->nodeValue) && trim($values[0]->nodeValue) !=''){
                if(isset($list_codes[$name]))
                    $object->$name = $values[0]->nodeValue.$list_codes[$name];
                else    
                    $object->$name = htmlspecialchars_decode($values[0]->nodeValue);
            }
            else
                echo "<!-- $name : nofunction found -->\n";
   //     }
  //      else{
    //        echo "<!-- $name : element not found object -->\n";
      //  }
    }

    //add management information
    $management_data = get_imidiate_child_nodes($xml,'management_data');
    $management_data = $management_data[0];
    $managementType = fetch_complex_type('management');
    $elements = $managementType->getElementsByTagName('element');

    foreach($elements as $element){
        $name = $element->getAttribute('name');
        $map  = $element->getAttribute('map');
        $values = get_imidiate_child_nodes($management_data,$name);
        $name = (isset($map)&&$map!='')?$map:$name;
        $object->$name = htmlspecialchars_decode($values[0]->nodeValue);
    }

    try{
        $object->SaveAll();
    }
    catch(ADODB_Exception $e){
        global $errors;
        array_push($errors,array('key'=>$key , 'msg'=>$e->getMessage()));        
    }
}


function get_imidiate_child_nodes($xml,$name)
{
    $values =array();
    $children = $xml->childNodes;
    foreach($children as $child){
        if($child->nodeName == $name && $child->nodeType != XML_COMMENT_NODE ){
            array_push($values,$child);
        }
    }
    return $values;
}

function get_list_codes($entity)
{
    global $global;
    $code_tables = array();
    if(isset($code_tables[$entity]))
        return $code_tables[$entity];
    else{
        $sql = "SELECT `field_name` , `list_code` FROM `data_dict` WHERE `entity` = '$entity' AND `list_code` !=0";
        $res = $global['db']->GetAll($sql);
        $code_table = array();
        foreach($res as $record)
            $code_table[$record['field_name']] = $record['list_code'];
        $code_tables[$entity] = $code_table;
        return $code_tables[$entity];
    }
}

function fetch_complex_type($object_type){
    global $xsd;
    $types = $xsd->getElementsByTagName('complexType');
    foreach($types as $type)
        if($type->getAttribute('name')==$object_type.'Type'){
            break;
        }
        else{
            $type = null;
        }
    return $type;
}
