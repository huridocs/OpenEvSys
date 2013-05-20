<?php
global $xsd;
global $conf;
$xsd = new DOMDocument();
$xsd->load(APPROOT.'res/events.xsd');

header("Content-Description: File Transfer");
header('Content-disposition: attachment; filename=openevsys_export_'.date('Y-m-d').'.xml'); 
header("content-type:text/xml;charset=utf-8");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<events xmlns='http://www.huridocs.org/'>\n";
echo "\t<meta_data>\n";
echo "\t\t<instance>".$conf['base_uuid'] ."</instance>\n";
echo "\t\t<date>".date('Y-m-d')."</date>\n";
echo "\t\t<time>".date('H:i:s') ."</time>\n";
echo "\t</meta_data>\n";
//<!---   events ---->/*{{{*/
$events = Browse::getEvents();
if($events){
    $event = new Event();
    foreach($events as $event_record){
        $event->LoadFromRecordNumber($event_record['event_record_number']);
        $event->LoadRelationships();
        echo "\t<event>\n";
        entity_to_xml($event);

        //<!---   acts ---->
        $acts = Browse::getActs($event->event_record_number);
        if($acts){
            $act = new Act();
            foreach($acts as $act_record){
                echo "\t\t<act>\n";
                $act->LoadFromRecordNumber($act_record['act_record_number']);
                $act->LoadRelationships();
                entity_to_xml($act,"\t\t\t");


                //<!---   involvements ---->
                $involments = Browse::getInvolments($act->act_record_number);
                if($involments){
                    $involvement = new Involvement();
                         foreach($involments as $involvement_record){
                        echo "\t\t\t<involvement>\n";
                        $involvement->LoadFromRecordNumber($involvement_record['involvement_record_number']);                        
                        $involvement->LoadRelationships();
                        entity_to_xml($involvement,"\t\t\t\t");
                        echo "\t\t\t</involvement>\n";
                    }
                }
                
            //<!---   Arrests ---->
                $arrests = Browse::getAdditionalData("arrest",$act->act_record_number);
                if($arrests){
                    $arrest = new Arrest();
                        foreach($arrests as $arrest_record){
                        	echo "\t\t\t<arrest>\n";
                        	$arrest->LoadFromRecordNumber($arrest_record['arrest_record_number']);                        
                        	$arrest->LoadRelationships();
                        	entity_to_xml_ad($arrest,"\t\t\t\t");
                        	echo "\t\t\t</arrest>\n";
                    }
                }
                
            //<!---   Destruction ---->
                $destructions = Browse::getAdditionalData("destruction",$act->act_record_number);
                if($destructions){
                    $destruction = new Destruction();
                        foreach($destructions as $destruction_record){
                        	echo "\t\t\t<destruction>\n";
                        	$destruction->LoadFromRecordNumber($destruction_record['destruction_record_number']);                        
                        	$destruction->LoadRelationships();
                        	entity_to_xml_ad($destruction,"\t\t\t\t");
                        	echo "\t\t\t</destruction>\n";
                    }
                }

            //<!---   Torture ---->
                $tortures = Browse::getAdditionalData("torture",$act->act_record_number);
                if($tortures){
                    $torture = new Torture();
                        foreach($tortures as $torture_record){
                        	echo "\t\t\t<torture>\n";
                        	$torture->LoadFromRecordNumber($torture_record['torture_record_number']);                        
                        	$torture->LoadRelationships();
                        	entity_to_xml_ad($torture,"\t\t\t\t");
                        	echo "\t\t\t</torture>\n";
                    }
                }   
                
             //<!---   Killing ---->
                $killings = Browse::getAdditionalData("killing",$act->act_record_number);
                if($killings){
                    $killing = new Killing();
                        foreach($killings as $killing_record){
                        	echo "\t\t\t<killing>\n";
                        	$killing->LoadFromRecordNumber($killing_record['killing_record_number']);                        
                        	$killing->LoadRelationships();
                        	entity_to_xml_ad($killing,"\t\t\t\t");
                        	echo "\t\t\t</killing>\n";
                    }
                }    
                
            echo "\t\t</act>\n";
            }
        }

        //<!---  chane_of_events ---->
        $coes = Browse::getCOEs($event->event_record_number);
        if($coes){
            $coe = new ChainOfEvents();
            foreach($coes as $coe_record){
            	echo "\t\t<chain_of_events>\n";
                $coe->LoadFromRecordNumber($coe_record['chain_of_events_record_number']);
                $coe->LoadRelationships();
                entity_to_xml($coe,"\t\t\t");
                echo "\t\t</chain_of_events>\n";
            }
        }



        echo "\t</event>\n";
    }
}/*}}}*/

//<!---   persons ---->/*{{{*/
$persons = Browse::getPersons();
if($persons){
    $person = new Person();
    foreach($persons as $person_record){
        echo "\t<person>\n";
        $person->LoadFromRecordNumber($person_record['person_record_number']);
        $person->LoadRelationships();
        entity_to_xml($person);

        //<!---   biographic_details ---->
        $biographic_details = Browse::getBiographicDetails($person->person_record_number);
        if($biographic_details){
            $bio = new BiographicDetail();
            foreach($biographic_details as $bio_record){
                echo "\t\t<biographic_details>\n";
                $bio->LoadFromRecordNumber($bio_record['biographic_details_record_number']);
                $bio->LoadRelationships();
                entity_to_xml($bio,"\t\t\t");
                echo "\t\t</biographic_details>\n";
            }
        }

        //<!---   addresses ---->
        $addresses = Browse::getAddress($person->person_record_number);
        if($addresses){
            $address = new Address();
            foreach($addresses as $address_record){
                echo "\t\t<address>\n";
                $address->LoadFromRecordNumber($address_record['address_record_number']);
                $address->LoadRelationships();
                entity_to_xml($address,"\t\t\t",false);
                echo "\t\t</address>\n";
            }
        }

        echo "\t</person>\n";
    }
}
/*}}}*/

//<!---   interventions ---->/*{{{*/
$interventions = Browse::getInterventions();
if($interventions){
    $intervention = new Intervention();
    foreach($interventions as $intervention_record){
        echo "\t<intervention>\n";
        $intervention->LoadFromRecordNumber($intervention_record['intervention_record_number']);
        $intervention->LoadRelationships();
        entity_to_xml($intervention);
        echo "\t</intervention>\n";
    }
}
/*}}}*/

//<!---   informations ---->/*{{{*/
$information = Browse::getInformation();
if($information){
    $info = new Information();
    foreach($information as $info_record){
        echo "\t<information>\n";
        $info->LoadFromRecordNumber($info_record['information_record_number']);
        $info->LoadRelationships();
        entity_to_xml($info);
        echo "\t</information>\n";
    }
}
/*}}}*/
echo "</events>\n";




function entity_to_xml($object,$tabs = "\t\t",$management_enable= true)/*{{{*/
{
    global $xsd;
    $types = $xsd->getElementsByTagName('complexType');
    $object_type = $object->getEntityType();
     foreach($types as $type)
        if($type->getAttribute('name')==$object_type.'Type'){
            break;
        }
        else{
            $type = null;
        }
    if(!isset($type))return;
    $elements = $type->getElementsByTagName('element');
    foreach($elements as $element){
        $name = $element->getAttribute('name');
        $value = $object->$name;
        if(isset($value))
            if(is_array($value))
                foreach($value as $sub_val){
                	if($element->getAttribute('type') == "huricode"){
                    	echo "$tabs<$name>".htmlspecialchars(trim_vocab_number($sub_val->vocab_number))."</$name>\n";
                	}
                	else{
                		echo "$tabs<$name>".htmlspecialchars($sub_val->vocab_number)."</$name>\n";
                	}
            }
            else{
            	if($element->getAttribute('type') == "huricode"){
                	echo "$tabs<$name>".htmlspecialchars(trim_vocab_number($value))."</$name>\n";
            	}
            	else{
            		echo "$tabs<$name>".htmlspecialchars($value)."</$name>\n";
            	}
            }
        else{
            echo "$tabs<!-- $name : element not found -->\n";
        }
    }
    if($management_enable){
    	
    	$object->LoadManagementData();
    	$management_data = $object->Management;
    	$types = $xsd->getElementsByTagName('complexType');
    	foreach($types as $type)
        if($type->getAttribute('name')=="managementType"){
            break;
        }
        else{
            $type = null;
        }
    	if(!isset($type))return;
    	$elements = $type->getElementsByTagName('element');
    	echo "$tabs<management_data>\n";
    	foreach($elements as $element){
    		$name = $element->getAttribute('name');
        	$value = $object->$name;
        	if($value){
              	if($element->getAttribute('type') == "huricode"){
                	echo "\t$tabs<$name>".htmlspecialchars(trim_vocab_number($value))."</$name>\n";
            	}
            	else{
            		echo "\t$tabs<$name>".htmlspecialchars($value)."</$name>\n";
            	}
    		}
        	else{
            echo "$tabs<!-- $name : element not found -->\n";
        	}
   		}
        echo "$tabs</management_data>\n";
    }
}

function entity_to_xml_ad($object,$tabs = "\t\t")/*{{{*/
{
    global $xsd;
    $types = $xsd->getElementsByTagName('complexType');
    $object_type = $object->getEntityType();
     foreach($types as $type)
        if($type->getAttribute('name')=="ad_".$object_type.'Type'){
            break;
        }
        else{
            $type = null;
        }        
    if(!isset($type))return;
    $elements = $type->getElementsByTagName('element');
   foreach($elements as $element){
        $name = $element->getAttribute('name');
        $value = $object->$name;
        if(isset($value)){            
        	if($element->getAttribute('type') == "huricode"){
                echo "$tabs<$name>".htmlspecialchars(trim_vocab_number($value))."</$name>\n";
            }
            else{
            	echo "$tabs<$name>".htmlspecialchars($value)."</$name>\n";
            }
        }
        else{
            echo "$tabs<!-- $name : element not found -->\n";
        }
    }
    
}

function trim_vocab_number($vocab_number){
	return substr_replace($vocab_number,"",12);
}/*}}}*/


