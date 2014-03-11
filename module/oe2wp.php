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

$dataFile = APPROOT . "module" . DIRECTORY_SEPARATOR . "oe2wp-data.json";

$sync = array();
$syncData = array();
$syncData['taxonomies'] = array();
$syncData['entities'] = array();

$sync['taxonomies'] = array(array('list_code' => 70, 'wp_taxonomy' => 'tipoconflicto'),
    array('list_code' => 71, 'wp_taxonomy' => 'ubicacion'),
    array('list_code' => 4, 'wp_taxonomy' => 'type_of_act'),
    array('list_code' => 82, 'wp_taxonomy' => 'area_corporal'),
    array('list_code' => 76, 'wp_taxonomy' => 'mediolesion'),
    array('list_code' => 432343, 'wp_taxonomy' => 'proceso_contra_perp'),
    array('list_code' => 432342, 'wp_taxonomy' => 'perpetrador'),
);
$sync['entities'] = array(
    array('entity' => 'event', 'wp_post_type' => 'event',
        'fields' => array(
            array('field_name' => 'event_record_number', 'wp_field' => 'oe_id', 'wptype' => 'meta'),
            array('field_name' => 'event_title', 'wp_field' => 'post_title', 'wptype' => 'core'),
            array('field_name' => 'event_description', 'wp_field' => 'post_content', 'wptype' => 'core'),
            array('field_name' => 'tipoconflicto', 'wp_field' => 'tipoconflicto', 'type' => 'taxonomy', 'multiple' => true, 'wptype' => 'taxonomy'),
            array('field_name' => 'ubicacion', 'wp_field' => 'ubicacion', 'type' => 'taxonomy', 'wptype' => 'taxonomy'),
            array('field_name' => 'initial_date', 'wp_field' => 'initial_date', 'wptype' => 'meta'),
            array('field_name' => 'final_date', 'wp_field' => 'final_date', 'wptype' => 'meta'),
            array('field_name' => 'event_location_latitude', 'wp_field' => 'location_latitude', 'wptype' => 'meta'),
            array('field_name' => 'event_location_longitude', 'wp_field' => 'location_longitude', 'wptype' => 'meta'),
            array('type' => 'count_muerte', 'wp_field' => 'count_muerte', 'wptype' => 'meta'),
            array('type' => 'count_herida', 'wp_field' => 'count_herida', 'wptype' => 'meta'),
            array('type' => 'radio', 'field_name' => 'judicializacion', 'wp_field' => 'judicializacion', 'wptype' => 'meta'),
        )
    ),
    array('entity' => 'act', 'wp_post_type' => 'act',
        'fields' => array(
            array('field_name' => 'act_record_number', 'wp_field' => 'oe_id', 'wptype' => 'meta'),
            array('type' => 'victimname', 'wp_field' => 'post_title', 'wptype' => 'core'),
            array('field_name' => 'descripacto', 'wp_field' => 'post_content', 'wptype' => 'core'),
            array('field_name' => 'initial_date', 'wp_field' => 'initial_date', 'wptype' => 'meta'),
            array('field_name' => 'final_date', 'wp_field' => 'final_date', 'wptype' => 'meta'),
            array('field_name' => 'type_of_act', 'wp_field' => 'type_of_act', 'type' => 'taxonomy', 'wptype' => 'taxonomy'),
            array('field_name' => 'area_corporal', 'wp_field' => 'area_corporal', 'type' => 'taxonomy', 'multiple' => true, 'wptype' => 'taxonomy'),
            array('field_name' => 'mediolesion', 'wp_field' => 'mediolesion', 'type' => 'taxonomy', 'multiple' => true, 'wptype' => 'taxonomy'),
            array('field_name' => 'act_location_latitude', 'wp_field' => 'location_latitude', 'wptype' => 'meta'),
            array('field_name' => 'act_location_longitude', 'wp_field' => 'location_longitude', 'wptype' => 'meta'),
            array('type' => 'victimedadhechos', 'wp_field' => 'edadhechos', 'wptype' => 'meta'),
            array('type' => 'eventtitle', 'wp_field' => 'event_title', 'wptype' => 'meta'),
            array('field_name' => 'circunstancias', 'wp_field' => 'circunstancias', 'wptype' => 'meta'),
            array('field_name' => 'ubicacto', 'wp_field' => 'ubicacion', 'type' => 'taxonomy', 'wptype' => 'taxonomy'),
            array('field_name' => 'proc_contra_perp', 'wp_field' => 'proceso_contra_perp', 'type' => 'taxonomy', 'wptype' => 'taxonomy'),
            array('type' => 'perpetrador', 'field_name' => 'perpetrador', 'wp_field' => 'perpetrador', 'multiple' => true, 'wptype' => 'taxonomy'),
        )
        ));

foreach ($sync['taxonomies'] as $taxonomy) {
    $data = $taxonomy;
    $data['data'] = array();
    $taxonomyData = MtFieldWrapper::getMTList($taxonomy['list_code']);
    foreach ($taxonomyData as $val) {
        $data['data'][] = array('oe_id' => $val['vocab_number'], 'name' => $val['label'], 'parent' => $val['parent_vocab_number']);
    }
    $syncData['taxonomies'][] = $data;
}

$browse = new Browse();
foreach ($sync['entities'] as $entity) {
    /* if($entity['entity'] == "event"){
      continue;
      } */
    $recordkeyName = get_primary_key($entity['entity']);
    $sql = "select " . $recordkeyName . " from " . $entity['entity'];
    $rows = $browse->ExecuteQuery($sql);
    $classname = ucfirst($entity['entity']);
    foreach ($rows as $row) {

        $object = new $classname();
        $object->LoadFromRecordNumber($row[$recordkeyName]);
        $object->LoadRelationships();
        $data = array();
        $data['post_type'] = $entity['wp_post_type'];
        $data['oe_id'] = $row[$recordkeyName];
        foreach ($entity['fields'] as $field) {
            $fieldName = $field['field_name'];
            $fieldVal = $object->$fieldName;
            $fieldData = null;
            if ($field['type'] == 'taxonomy' && $field['multiple']) {
                $fieldData = array();
                foreach ($fieldVal as $val) {
                    $fieldData[] = $val->vocab_number;
                }
            } elseif ($field['type'] == "count_muerte") {
                $sql = "SELECT count(*) as count FROM act a WHERE a.type_of_act ='51000000000004' and a.event='" . $row[$recordkeyName] . "'";
                $count = $browse->ExecuteQuery($sql);
                if ($count[0]['count']) {
                    $fieldData = (int) $count[0]['count'];
                }
            } elseif ($field['type'] == "count_herida") {
                $sql = "SELECT count(*) as count FROM act a WHERE a.type_of_act ='54010101000117' and a.event='" . $row[$recordkeyName] . "'";
                $count = $browse->ExecuteQuery($sql);
                if ($count[0]['count']) {
                    $fieldData = (int) $count[0]['count'];
                }
            } elseif ($field['type'] == "victimname") {
                $victim = new Person();
                $victim->LoadFromRecordNumber($object->victim);
                $fieldData = $victim->person_name . " " . $victim->other_names;
            } elseif ($field['type'] == "victimedadhechos") {
                $victim = new Person();
                $victim->LoadFromRecordNumber($object->victim);
                if ($victim->menoredad == 'y') {
                    $fieldData = (int) $victim->edadhechos;
                }
            } elseif ($field['type'] == "eventtitle") {
                $event = new Event();
                $event->LoadFromRecordNumber($object->event);
                $fieldData = $event->event_title;
            } elseif ($field['type'] == "perpetrador") {
                $sql = "SELECT involvement_record_number,perpetrator	FROM involvement  WHERE act = '" . $row[$recordkeyName] . "'";
                $res = $browse->ExecuteQuery($sql);
                $fieldData = array();

                if ($res) {
                    foreach ($res as $inv) {
                        $perpetrator = new Person();
                        $perpetrator->LoadFromRecordNumber($inv['perpetrator']);
                        $perpetrator->LoadRelationships();
                        $fieldVal = $perpetrator->perpetrador;
                        foreach ($fieldVal as $val) {
                            $fieldData[] = $val->vocab_number;
                        }
                    }
                }
            } elseif ($field['type'] == "radio") {
                if (strtolower($fieldVal) == 'y') {
                    $fieldData = _t('YES');
                } elseif (strtolower($fieldVal) == 'n') {
                    $fieldData = _t('NO');
                }
            } else {
                $fieldData = $fieldVal;
            }
            $wptype = $field['wptype'];
            if (!$data[$wptype]) {
                $data[$wptype] = array();
            }
            $data[$wptype][$field['wp_field']] = $fieldData;
        }
        $syncData['entities'][] = $data;
    }
}

file_put_contents($dataFile, json_encode($syncData));
?>