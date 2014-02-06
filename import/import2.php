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

$dataFile = "C:/server/huridocs/peru/project/import" . DIRECTORY_SEPARATOR . "data-docs.txt";
$dataFile = APPROOT . "import" . DIRECTORY_SEPARATOR . "data-docs.txt";

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
$docFields = array(2 => "9002",
	3=>"522341",
    4 => "4624342",
    5 => "3345",
    6 => "522342",
    7 => "4324556",
    9 => "555462",
    11 => "7654422",
    13 => "7654427",
    15 => "522343",
    16 => "4243",
    17 => "7654430",
    18 => "7654431",
    19 => "995243",
    20 => "7654432"
); //7 hat

$docsCount = 1;
for ($i = 1; $i <= $docsCount; $i++) {
    foreach ($docFields as $key => $val) {
        $k2 = $key + intval(($i - 1) * 15);
        $k2 = (int) $k2;
        $dataCols[$k2] = $fields[$val];
        $dataCols[$k2]["ent"] = $fields[$val]["entity"] . $i;
    }
}


$data = get_data_array();

$lists = array();


function get_data_array() {
    global $dataFile, $dataCols, $fields, $lists, $docsCount, $biosCount;
    if (($handle = fopen($dataFile, "r")) === FALSE) {
        return array();
    }
    $results = array();
    $i = 0;
    while (($cols = fgetcsv($handle, 0, "\t")) !== FALSE) {
        $glData = array();
        $row = array();
        if ($cols && $cols[1]) {

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
                            $val == "Hombre";
                        } elseif ($val == "F") {
                            $val == "Mujer";
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
                        if ($val == "NO") {
                            $val = "n";
                        } elseif ($val == "SÍ") {
                            $val = "y";
                        } else {
                            $val = null;
                        }
                    }

                    if ($val) {
                        //$row[$entityField['field_name']] = $val;
                        $glData[$ent][$entityField['field_name']] = $val;
                    }
                }
            }
           //var_dump($glData);exit;
            $browse = new Browse();
            $rows = $browse->ExecuteQuery("select event_record_number as id from event where event_title='" . $glData['event']['event_title'] . "'");
            if ($rows && $rows[0]['id']) {
                $event = new Event();
                $event->LoadFromRecordNumber($rows[0]['id']);
                $event->LoadRelationships();
            } else {
               continue;
            }
           if($event->supporting_documents){
               $supporting_documents = $event->supporting_documents;
           }else{
            $supporting_documents = array();
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
                $event->supporting_documents = $supporting_documents;
                $event->SaveDocs();
            }
         }
        $results[] = $row;
        $i++;
    }
    return $results;
}

?>