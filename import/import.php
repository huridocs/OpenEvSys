<?php

error_reporting(E_ALL ^ E_NOTICE);
set_time_limit(10000000);
define('APPROOT', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR);
//define('APPROOT', "C:/server/huridocs/openevsys/project/");
if (!file_exists(APPROOT . 'conf/sysconf.php')) {
    exit(0);
}

/* * ****** If the system is installed start the bootstrap process ******* */

//Include the configuration file at the begining since
//rest of the handlers will require configuration values
require_once(APPROOT . 'conf/sysconf.php');

//load error and exception handlers
require_once(APPROOT . 'inc/handler_error.inc');
require_once(APPROOT . 'inc/handler_exception.inc');

//load db handler
require_once(APPROOT . 'inc/handler_db.inc');

//overide conf values from db
require_once(APPROOT . 'inc/handler_config.inc');

$conf['locale'] = "es";

//input ( $_GET , $_POST ) validation utf-8
require_once(APPROOT . 'inc/handler_filter.inc');

//utility function used by the system
//this contain autoload to string manipulation
require_once(APPROOT . 'inc/lib_util.inc');

//load session handler
require_once(APPROOT . 'inc/session/handler_session.inc');


//load l10n library
require_once(APPROOT . 'inc/i18n/handler_l10n.inc');

$_SESSION['username'] = 'admin';
//load authentication handler
require_once(APPROOT . 'inc/security/handler_auth.inc');
//load acl 
require_once(APPROOT . 'inc/security/handler_acl.inc');


require_once(APPROOT . 'mod/analysis/analysisModule.class.php');
require_once(APPROOT . 'mod/analysis/searchSql.php');
include_once APPROOT . 'inc/lib_entity_forms.inc';
include_once APPROOT . 'inc/lib_uuid.inc';
include_once APPROOT . 'inc/lib_form_util.inc';

$dataFile = "C:/server/huridocs/peru/project/import" . DIRECTORY_SEPARATOR . "data.txt";
$dataFile = APPROOT . "import" . DIRECTORY_SEPARATOR . "data.txt";

/* $dataCols = array(1=>"event_title",
  =>"event_description",
  =>"ubicaci�n",
  =>"tipoconflicto",
  =>"initial_date",
  =>"final_date",
  =>"event_location",
  =>"",=>"",
  =>"counting_unit",
  =>"person_name",
  =>"other_names",
  =>"SEX",
  =>"edadhechos",=>"menoredad",=>"ocup_peru",=>"identidad",=>"",=>"tipolesion",=>"tipodefuente",
  =>"victim_characteristics",=>"",=>"circunstancias",=>"ubicacto",=>"",=>"",=>"centropoblado",
  =>"act_location",=>"physical_consequences",=>"",=>"",=>"mediolesion",=>"fechafallecimiento",
  =>"centrosalud",=>"fuente",=>"usofuerza",=>"mandos",=>"procesojudicial",=>"counting_unit",
  =>"person_name",
  =>"title",=>"",=>"tipo_proceso",=>"num_expediente",=>"situacionprocesal",
  =>"instancia",=>"delitos_investigados",=>"imputados",=>"autoria",=>"estado_responsable",
  =>"pena_solicitada",=>"monto",=>"detalles_sentencia",=>"inst_sigue",=>"fuente",
  =>"title",=>"",=>"tipo_proceso",=>"num_expediente",=>"situacionprocesal",=>"instancia",=>"delitos_investigados",=>"imputados",=>"autoria",=>"estado_responsable",=>"pena_solicitada",=>"monto",=>"detalles_sentencia",=>"inst_sigue",=>"fuente",
  =>"title",=>"",=>"tipo_proceso",=>"num_expediente",=>"situacionprocesal",=>"instancia",=>"delitos_investigados",=>"imputados",=>"autoria",=>"estado_responsable",=>"pena_solicitada",=>"monto",=>"detalles_sentencia",=>"inst_sigue",=>"fuente",
  =>"title",=>"",=>"tipo_proceso",=>"num_expediente",=>"situacionprocesal",=>"instancia",=>"delitos_investigados",=>"imputados",=>"autoria",=>"estado_responsable",=>"pena_solicitada",=>"monto",=>"detalles_sentencia",=>"inst_sigue",=>"fuente",
  =>"title",=>"",=>"tipo_proceso",=>"num_expediente",=>"situacionprocesal",=>"instancia",=>"delitos_investigados",=>"imputados",=>"autoria",=>"estado_responsable",=>"pena_solicitada",=>"monto",=>"detalles_sentencia",=>"inst_sigue",=>"fuente",
  =>"title",=>"",=>"tipo_proceso",=>"num_expediente",=>"situacionprocesal",=>"instancia",=>"delitos_investigados",=>"imputados",=>"autoria",=>"estado_responsable",=>"pena_solicitada",=>"monto",=>"detalles_sentencia",=>"inst_sigue",=>"fuente",
  =>"title",=>"",=>"tipo_proceso",=>"num_expediente",=>"situacionprocesal",=>"instancia",=>"delitos_investigados",=>"imputados",=>"autoria",=>"estado_responsable",=>"pena_solicitada",=>"monto",=>"detalles_sentencia",=>"inst_sigue",=>"fuente",
  =>"BIO DETAILS",=>"PERSONAS",=>"PERSONAS",=>"PERSONAS",=>"",=>"ADDRESS",=>"",=>"",=>"",=>"",=>"",=>"ADDRESS",=>"BIO DETAILS",=>"PERSONAS",=>"PERSONAS",=>"PERSONAS",=>"",=>"ADDRESS",=>"BIO DETAILS",=>"PERSONAS",=>"PERSONAS",=>"PERSONAS",=>"",
  =>"ADDRESS"); */
$browse = new Browse();
$sql = "SELECT 
                    field_number ,
                    field_name ,
                    is_repeat,
                    IFNULL(dl.msgstr , d.field_label) as 'field_label',                      
                    LOWER(entity) as entity ,
                    field_type ,
                    visible_adv_search_display as in_results,
                    list_code 	,
                    d.validation
                FROM data_dict as d 
                LEFT JOIN data_dict_l10n AS dl ON (d.field_number = dl.msgid AND dl.locale = '{$conf['locale']}' )
                WHERE  d.entity IS NOT NULL  ORDER BY entity, field_number";
$res = $browse->ExecuteQuery($sql);
$fields = array();
foreach ($res as $record) {
    $fields[(string) $record['field_number']] = $record;
}

$dataCols = array(1 => "0102",
    2 => "0115",
    3 => "564748",
    4 => "4274234892",
    5 => "0113",
    6 => "0114",
    7 => "0173",
	8 => "0165",
    10 => "0902", //person fields
    11 => "0903",
    12 => "0904",
    13 => "0915",
    14 => "462274628",
    15 => "342654",
    16 => "32328",
    17 => "764238",
	18 => "0965",
    19 => "2109", //act fields 
    20 => "66478", 
    21 => "2152",
    22 => "2111",
    23 => "234567890",
    26 => "378234",
    27 => "423899",
    28 => "2172",
    29 => "2116", 
    30 => "23423",
    31 => "09524432",
    32 => "7462384",
    33 => "726384762",
    34 => "09824092384",
    35 => "98797564",
    36 => "68734298",
    37 => "42342379",
    38 => "623746",
    39 => "47785", 
    40 => array("0902", "ent" => "perpetrator"),
    41 => array("0903", "ent" => "perpetrator"),
);



foreach ($dataCols as $key => $val) {
    if (is_array($val)) {
        $entityField = $val[0];
        $ent = $val["ent"];
    } else {
        $entityField = $val;
        $ent = $fields[$val]["entity"];
    }
    $dataCols[$key] = $fields[$entityField];
    $dataCols[$key]["ent"] = $ent;
}
$docFields = array(42 => "9002",
    43 => "522341",
    44 => "4624342",
    45 => "3345",
    46 => "4324556",
    47 => "77526",
    48 => "555462",
    49 => "7654422",
    50 => "7654427",
    51 => "7654428",
    52 => "4243",
    53 => "7654430",
    54 => "7654431",
    55 => "995243", 
    56 => "7654432"
); //7 hat

$docsCount = 7;
for ($i = 1; $i <= $docsCount; $i++) {
    foreach ($docFields as $key => $val) {
        $k2 = $key + intval(($i - 1) * 15);
        $k2 = (int) $k2;
        $dataCols[$k2] = $fields[$val];
        $dataCols[$k2]["ent"] = $fields[$val]["entity"] . $i;
    }
}

$bioFields = array(147 => "2309",
    148 => "0903",
    149 => "0904",
    150 => "462274628",
    151 => "8005",
    152 => "8008"
); //7 hat

$biosCount = 4;
for ($i = 1; $i <= $biosCount; $i++) {
    foreach ($bioFields as $key => $val) {
        $k2 = $key + intval(($i - 1) * 6);
        $k2 = (int) $k2;
        $dataCols[$k2] = $fields[$val];
        $dataCols[$k2]["ent"] = "bio_details" . $i;
    }
}
try{
$data = get_data_array();
}catch(Exception $e){
var_dump($e);
}
$lists = array();


function get_data_array() {
    global $dataFile, $dataCols, $fields, $lists, $docsCount, $biosCount;
    if (($handle = fopen($dataFile, "r")) === FALSE) {
        return array();
    }
    $results = array();
    $j = 0;
    while (($cols = fgetcsv($handle, 0, "\t")) !== FALSE) {
        $glData = array();
        $row = array();
		if ($cols && $cols[1]) {
			//var_dump($cols);exit;
        
            foreach ($cols as $key => $val) {
                $val = trim($val, '"');
                $val = trim($val);
                $row[$key] = $val;
                $entityField = $dataCols[$key];

                if ($entityField) {
                    $ent = $entityField["ent"];

                    $list_code = $entityField['list_code'];
                    $type = $entityField['field_type'];
                    $mlt = ( (trim($entityField['is_repeat']) == 'Y' || trim($entityField['is_repeat']) == 'y' ) ? true : false );

                    if ($list_code == 39) {
                        if ($val == "M") {
                            $val = "Hombre";
                        } elseif ($val == "F") {
                            $val = "Mujer";
                        }
                    }
                    if ($list_code && !$lists[$list_code]) {
                        $options = array();
                        $data_array = MtFieldWrapper::getMTList($list_code);
                        $size = count($data_array);
                        for ($i = 0; $i < $size; $i++) {
                            $options[$data_array[$i]['vocab_number']] = strtolower($data_array[$i]['label']);
                        }
                        $lists[$list_code] = $options;
                    }
                    if ($list_code) {
                        if ($mlt) {
                            $val2 = explode(";", $val);
							
                            $val = array();
                            foreach ($val2 as $v) {
								$v = trim($v);
                                $vocab_number = array_search(strtolower($v), $lists[$list_code]);
                                if ($vocab_number) {
                                    $val[] = $vocab_number;
                                }
                            }
                        } else {
                            $vocab_number = array_search(strtolower($val), $lists[$list_code]);

                            if ($vocab_number) {
                                $val = $vocab_number;
                            } else {
                                $val = null;
                            }
                        }
                    }
                    if ($type == "date") {
                        if($val){
                            $d = date_create_from_format("m/d/Y", $val);
                            if ($d) {
                                $val = date_format($d, 'Y-m-d');
                            } else {
                                $val = null;
                            }
                        }else{
                            $val = null;                        
                        }
                    } elseif ($type == "location") {
                        $val2 = explode(",", $val);
                        //$row[$entityField['field_name'] . "_latitude"] = -floatval($val2[0]);
                        //$row[$entityField['field_name'] . "_longitude"] = -floatval($val2[1]);
                        $glData[$ent][$entityField['field_name'] . "_latitude"] = -floatval($val2[0]);
                        $glData[$ent][$entityField['field_name'] . "_longitude"] = -floatval($val2[1]);

                        $val = null;
                    } elseif ($type == "radio") {
                        if ($val == "NO" || $val == "no") {
                            $val = "n";
                        } elseif ($val == "SÍ" || $val == "sí") {
                            $val = "y";
                        } else {
                            $val = null;
                        }
                    }

                    if (!is_null($val) ){
                        //$row[$entityField['field_name']] = $val;
                        $glData[$ent][$entityField['field_name']] = $val;
                    }
                }
            }
			if(!$glData['act']['ubicacto']){
				$v = $row[25];
				$vocab_number = array_search(strtolower($v), $lists[71]);

				if ($vocab_number) {
					$glData['act']['ubicacto'] = $vocab_number;
				}
			}
			if(!$glData['act']['ubicacto']){
				$v = $row[24];
				$vocab_number = array_search(strtolower($v), $lists[71]);

				if ($vocab_number) {
					$glData['act']['ubicacto'] = $vocab_number;
				}
			}
			
			$browse = new Browse();
            $rows = $browse->ExecuteQuery("select event_record_number as id from event where event_title='" . $glData['event']['event_title'] . "'");
            if ($rows && $rows[0]['id']) {
                $event = new Event();
                $event->LoadFromRecordNumber($rows[0]['id']);
            } else {
                $form = event_form('new');
                $event = new Event();
                $event->event_record_number = shn_create_uuid('event');
                form_objects($form, $event, $glData['event']);

                $event->SaveAll();
            }

            $browse = new Browse();
			$person = null;
			if($glData['person']['person_name'] != "NN"){
				$rows = $browse->ExecuteQuery("select person_record_number as id from person where person_name='" . $glData['person']['person_name'] . "' 
				and other_names='" . $glData['person']['other_names'] . "' ");

				if ($rows && $rows[0]['id']) {
					$person = new Person();
					$person->LoadFromRecordNumber($rows[0]['id']);
				} 
			
			}
			if(!$person) {
                $person_form = person_form('new');
                $person = new Person();
                form_objects($person_form, $person, $glData['person']);
                $person->deceased = ($person->deceased == 'on') ? 'y' : 'n';
                if (isset($person->number_of_persons_in_group) && !$person->number_of_persons_in_group) {
                    $person->number_of_persons_in_group = Null;
                }
                if (isset($person->dependants) && !$person->dependants) {
                    $person->dependants = Null;
                }
                $person->SaveAll();
            }
            $victim = $person;
            $act_form = act_form('new');
            $act = new Act();
            $act->act_record_number = shn_create_uuid('act');
            $glData['act']['victim'] = $victim->person_record_number;
            form_objects($act_form, $act, $glData['act']);
           
		    $act->event = $event->event_record_number;
			$act->SaveAll();
			 
            if ($glData['perpetrator']['person_name']) {
                $browse = new Browse();
                $rows = $browse->ExecuteQuery("select person_record_number as id from person where person_name='" . $glData['perpetrator']['person_name'] . "' ");
                if ($rows && $rows[0]['id']) {
                    $person = new Person();
                    $person->LoadFromRecordNumber($rows[0]['id']);
                } else {
                    $person_form = person_form('new');
                    $person = new Person();
                    form_objects($person_form, $person, $glData['perpetrator']);
                    $person->deceased = ($person->deceased == 'on') ? 'y' : 'n';
                    if (isset($person->number_of_persons_in_group) && !$person->number_of_persons_in_group) {
                        $person->number_of_persons_in_group = Null;
                    }
                    if (isset($person->dependants) && !$person->dependants) {
                        $person->dependants = Null;
                    }
                    $person->SaveAll();
                }
                $perpetrator = $person;
                $inv = new Involvement();
                $inv->involvement_record_number = shn_create_uuid('inv');
                $inv->degree_of_involvement = "54010101001921";//placeholder
                $inv->event = $event->event_record_number;
                $inv->act = $act->act_record_number;
                $inv->perpetrator = $perpetrator->person_record_number;
                $inv->SaveAll();
            }
            $supporting_documents = array();
			if(trim($row[9])){
				$document_form = document_form('new');
				unset($document_form['doc_id']);
				$supporting_docs = new SupportingDocs();
				$supporting_docs_meta = new SupportingDocsMeta();
				$type = null;

				$doc_uuid = shn_create_uuid('doc');
				$supporting_docs->doc_id = $doc_uuid;
				$supporting_docs_meta->doc_id = $doc_uuid;
				$supporting_docs->uri = '';
				form_objects($document_form, $supporting_docs, array('title'=>$row[9]));
				form_objects($document_form, $supporting_docs_meta, array('title'=>$row[9]));

				$supporting_docs_meta->format = $type;
				$supporting_docs->Save();
				$supporting_docs_meta->Save();
				$supporting_documents[] = $doc_uuid;
			}
            for ($j = 1; $j <= $docsCount; $j++) {
                if ($glData["supporting_docs_meta" . $j]) {
                    if(!$glData["supporting_docs_meta" . $j]['title']){
                        continue;
                    }
                    $document_form = document_form('new');
                    unset($document_form['doc_id']);
                    $supporting_docs = new SupportingDocs();
                    $supporting_docs_meta = new SupportingDocsMeta();
                    $type = null;

                    $doc_uuid = shn_create_uuid('doc');
                    $supporting_docs->doc_id = $doc_uuid;
                    $supporting_docs_meta->doc_id = $doc_uuid;
                    $supporting_docs->uri = '';
                    form_objects($document_form, $supporting_docs, $glData["supporting_docs_meta" . $j]);
                    form_objects($document_form, $supporting_docs_meta, $glData["supporting_docs_meta" . $j]);
			
                    $supporting_docs_meta->format = $type;
                    $supporting_docs->Save();
                    $supporting_docs_meta->SaveAll();
                    $supporting_documents[] = $doc_uuid;
				}
            }
			

			if ($supporting_documents) {
                $act->supporting_documents = $supporting_documents;
                $act->SaveDocs();
            }
			
            $bio_details = array();
            for ($j = 1; $j <= $biosCount; $j++) {
                if ($glData["bio_details" . $j] && $glData["bio_details" . $j]["type_of_relationship"]) {
                    $browse = new Browse();
                    $rows = $browse->ExecuteQuery("select person_record_number as id from person where person_name='" . $glData["bio_details" . $j]['person_name'] . "' 
					and other_names='" . $glData['bio_details']['other_names'] . "' ");

                    if ($rows && $rows[0]['id']) {
                        $person = new Person();
                        $person->LoadFromRecordNumber($rows[0]['id']);
                    } else {
                        $person_form = person_form('new');
                        $person = new Person();
                        form_objects($person_form, $person, $glData["bio_details" . $j]);
                        $person->deceased = ($person->deceased == 'on') ? 'y' : 'n';
                        if (isset($person->number_of_persons_in_group) && !$person->number_of_persons_in_group) {
                            $person->number_of_persons_in_group = Null;
                        }
                        if (isset($person->dependants) && !$person->dependants) {
                            $person->dependants = Null;
                        }
                        $person->SaveAll();
                    }

                    if ($glData["bio_details" . $j]["phone"] || $glData["bio_details" . $j]["email"]) {
                        $address = new Address();
                        $address_form = address_form('new');

                        form_objects($address_form, $address, $glData["bio_details" . $j]);
                        $address->person = $person->person_record_number;
                        $address->Save();
                    }
                    
                    $biography_form = biographic_form('new');

                    $biography = new BiographicDetail();
                    //$biography->LoadfromRecordNumber();
                    $biography->biographic_details_record_number = shn_create_uuid('biography');
                    $glData["bio_details" . $j]['person_id'] = $person->person_record_number;
                    form_objects($biography_form, $biography,$glData["bio_details" . $j]);
                    $biography->person = $victim->person_record_number;
                    $biography->related_person = $person->person_record_number;
                    if ($biography->related_person == ''){
                        $biography->related_person = null;
                    }
                    $biography->SaveAll();
                }
            }
        }
		
       
        $results[] = $row;
        $j++;
		
    }
    return $results;
}

?>