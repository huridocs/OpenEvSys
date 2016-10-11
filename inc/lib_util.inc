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
class AutoLoadClass {

    public static function __autoload($class_name) {
        if (file_exists(APPROOT . 'inc/' . $class_name . '.class.php')) {
            require_once(APPROOT . 'inc/' . $class_name . '.class.php');
        }
        if (file_exists(APPROOT . 'data/' . $class_name . '.php')) {
            require_once(APPROOT . 'data/' . $class_name . '.php');
        }
        if (file_exists(APPROOT . '3rd/Zend/ ' . $class_name . '.php')) {
            require_once(APPROOT . '3rd/Zend/ ' . $class_name . '.php');
        }
        if (file_exists(APPROOT . 'data/' . $class_name . '.php')) {
            require_once(APPROOT . 'data/' . $class_name . '.php');
        }
        if (file_exists(APPROOT . '3rd/' . $class_name . '.php')) {
            require_once(APPROOT . '3rd/' . $class_name . '.php');
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
function get_url($module = 'home', $action = 'default', $view = null, $args = null, $stream = null, $return = false) {
    global $global;


    if (is_array($global['url_args']) && is_array($args))
        $args = array_merge($global['url_args'], $args);

    if (!is_array($args))
        $args = $global['url_args'];

    $act = (isset($action)) ? "&amp;act=$action" : null;
    $view = (isset($view)) ? "&amp;view=$view" : null;
    $stream = (isset($stream)) ? "&amp;stream=$stream" : null;
    if (is_array($args)) {
        foreach ($args as $id => $value) {

            if (is_array($value)) {
                foreach ($value as $val) {

                    $al .= "&amp;{$id}[]=$val";
                }
            } else if (isset($value)) {
                $al .= "&amp;$id=$value";
            }
        }
    }

    if ($conf['mod_rewrite'] == 'true')
        $uri = "index.php/$module/$action/$id.$stream?";
    else
        $uri = "index.php?mod=$module$act$view$stream";

    if ($return)
        return $uri . $al;
    else
        echo $uri . $al;
}

function set_url_args($key, $value) {
    global $global;
    $global['url_args'][$key] = $value;
}

function clear_url_args() {
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
    } else {
        if (isset($_SERVER['argv'])) {
            $uri = $_SERVER['SCRIPT_NAME'] . '?' . $_SERVER['argv'][0];
        } elseif (isset($_SERVER['QUERY_STRING'])) {
            $uri = $_SERVER['SCRIPT_NAME'] . '?' . $_SERVER['QUERY_STRING'];
        } else {
            $uri = $_SERVER['SCRIPT_NAME'];
        }
    }

    return $uri;
}

function change_tpl($template) {
    $controller = shnFrontController::getController();
    $controller->view->setTemplate($template);
}

function get_mt_term($vocab_number) {
    $mtterms = new MtTerms();
    $mtterms->LoadfromVocabNumber($vocab_number);
    return $mtterms->label;
}

function set_redirect_header($module = 'home', $action = 'default', $view = null, $args = null, $stream = 'html', $return = false) {
    global $global;


    if (is_array($global['url_args']) && is_array($args))
        $args = array_merge($global['url_args'], $args);

    if (!is_array($args))
        $args = $global['url_args'];


    $view = (isset($view)) ? "&view=$view" : null;

    if (is_array($args))
        foreach ($args as $id => $value) {

            if (is_array($value)) {
                foreach ($value as $val) {
                    $al .= "&$id" . "[]=$val";
                }
            } else {
                $al .= "&$id=$value";
            }
        }

    if ($conf['mod_rewrite'] == 'true')
        $uri = "index.php/$module/$action/$id.$stream?";
    else
        $uri = "index.php?mod=$module&act=$action$view";

    $uri = $uri . $al;
    header("location: " . $uri);
    return $uri;
}

function array_to_list($array) {
    if (is_array($array)) {
        echo implode(" , ", $array);
        /* foreach ($array as $item)
          echo "$item , "; */
    }
    else
        echo $array;
}

function get_record_url($record_number, $entity, $url_type = "view") {

    if ($url_type == "edit") {
        switch ($entity) {
            case 'event':
                return "index.php?mod=events&act=edit_event&eid=$record_number";
            case 'person':
                return "index.php?mod=person&act=edit_person&pid=$record_number";
            case 'victim':
                return "index.php?mod=person&act=person&pid=$record_number";
            case 'perpetrator';
                return "index.php?mod=person&act=person&pid=$record_number";
            case 'source':
                return "index.php?mod=person&act=person&pid=$record_number";
            case 'intervening_party':
                return "index.php?mod=person&act=person&pid=$record_number";
            case 'act':
                return "index.php?mod=events&act=edit_act&act_id=$record_number";
            case 'involvement':
                return "index.php?mod=events&act=vp_list&inv_id=$record_number&type=inv";
            case 'information':
                return "index.php?mod=events&act=src_list&type=information&information_id=$record_number";
            case 'intervention':
                return "index.php?mod=events&act=edit_intv&intervention_id=$record_number";
            case 'supporting_docs_meta':
                return "index.php?mod=docu&act=edit_document&doc_id=$record_number";
            case 'biographic_details':
                return "index.php?mod=person&act=biography_list&type=bd&biography_id=$record_number";
            default:
                return;
        }
    } elseif ($url_type == "delete") {
        switch ($entity) {
            case 'event':
                return "index.php?mod=events&act=delete_event&eid=$record_number";
            case 'person':
                return "index.php?mod=person&act=delete_person&pid=$record_number";
            case 'victim':
                return "index.php?mod=person&act=delete_person&pid=$record_number";
            case 'perpetrator';
                return "index.php?mod=person&act=delete_person&pid=$record_number";
            case 'source':
                return "index.php?mod=person&act=delete_person&pid=$record_number";
            case 'intervening_party':
                return "index.php?mod=person&act=delete_person&pid=$record_number";
            case 'act':
                return "index.php?mod=events&act=delete_act&act_id=$record_number";
            case 'involvement':
                return "index.php?mod=events&act=vp_list&inv_id=$record_number&type=inv";
            case 'information':
                return "index.php?mod=events&act=src_list&type=information&information_id=$record_number";
            case 'intervention':
                return "index.php?mod=events&act=delete_intervention&intervention_id=$record_number";
            case 'supporting_docs_meta':
                return "index.php?mod=docu&act=delete_document&doc_id=$record_number";
            case 'biographic_details':
                return "index.php?mod=person&act=biography_list&type=rp&biography_id=$record_number";
            default:
                return;
        }
    } else {
        switch ($entity) {
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
                return "index.php?mod=person&act=biography_list&type=bd&biography_id=$record_number";
            default:
                return;
        }
    }
}

function get_table_for_entity($entity) {

    switch ($entity) {
        case 'victim':
            return "person";
        case 'perpetrator':
            return "person";
        case 'source':
            return "person";
        case 'intervening_party':
            return "person";
        default:
            return $entity;
    }
}

function get_primary_key($entity) {
  switch ($entity) {
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
        case 'supporting_docs':
            return "doc_id";
        default:
            return search_primary_key($entity);
        }

}

function search_primary_key($entity){
  $browse = new Browse();
  $sql = "SELECT KU.table_name as tablename,column_name as primary_key
          FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS AS TC
          INNER JOIN
          INFORMATION_SCHEMA.KEY_COLUMN_USAGE AS KU
          ON TC.CONSTRAINT_TYPE = 'PRIMARY KEY' AND
          TC.CONSTRAINT_NAME = KU.CONSTRAINT_NAME
          GROUP BY KU.TABLE_NAME";

  $primary_keys = $browse->ExecuteQuery($sql);

  $primary_key = $entity . "_record_number";

  foreach($primary_keys as $table){
    if($entity == $table['tablename']){
      $primary_key = $table['primary_key'];
    }
  }

  return $primary_key;
}

function get_mod_label($entity, $type = "single") {

    switch ($entity) {
        case 'event':
        case 'events':
            if ($type == "single") {
                return _t('EVENT');
            } else {
                return _t('EVENTS');
            }
        case 'person':
        case 'person':
            if ($type == "single") {
                return _t('PERSON');
            } else {
                return _t('PERSONS');
            }
        case 'docu':
            if ($type == "single") {
                return _t('DOCUMENT');
            } else {
                return _t('DOCUMENTS');
            }
        default:
            if ($type == "single") {
                return _t(ucfirst($entity));
            } else {
                return _t(ucfirst($entity));
            }
            return;
    }
}

function get_primary_entity($entity) {
    switch ($entity) {
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

function set_links_in_recordset($recordset, $entity, $additionalurlfields = array()) {

    $pk = get_primary_key($entity);

    $recordArray = array();

    $primary_entity = get_primary_entity($entity);

    foreach ($recordset as $record) {
        if ($primary_entity != null) {
            $primary_entity_record_number = $record[get_primary_key($primary_entity)];
        } else {
            $primary_entity_record_number = null;
        }
        foreach($record as &$val){
            $val = makeLinksClickable($val);
        }
        $record['record_url'] = get_record_url($record[$pk], $entity, $primary_entity_record_number);
        $record['record_edit_url'] = get_record_url($record[$pk], $entity, "edit");
        $record['record_delete_url'] = get_record_url($record[$pk], $entity, "delete");
        if ($additionalurlfields) {
            foreach ($additionalurlfields as $field => $additionalurlfield) {
                if (isset($record[$field]) && isset($record[$additionalurlfield["val"]])) {
                    $entity2 = $additionalurlfield["entity"];
                    $primary_entity2 = get_primary_entity($entity);
                    if ($primary_entity2 != null) {
                        $primary_entity_record_number2 = $record[get_primary_key($primary_entity2)];
                    } else {
                        $primary_entity_record_number2 = null;
                    }
                    $record[$field . '_record_url'] = get_record_url($record[$additionalurlfield["val"]], $entity2, $primary_entity_record_number2);
                }
            }
        }
        $recordArray[] = $record;

        //var_dump( $entity , $record);
    }



    return $recordArray;
}

function getMtFields($form_array) {

    $mtTermArray = array();
    foreach ($form_array as $name => $field) {

        if ($field['map']['mt'] != '0') {
            $mtTermArray[] = $field['map']['field'];
        }
    }
    return $mtTermArray;
}

function set_huriterms_in_record_array($formArray, &$valuesArray) {

    $mtTerms = getMtFields($formArray);

    if ($mtTerms != null && count($mtTerms) > 0) {
        foreach ($mtTerms as $term) {

            foreach ($valuesArray as &$record) {
                $termsArray = split(' , ', $record[$term]);
                //var_dump($termsArray);
                if (is_array($termsArray) && sizeof($termsArray) > 1) {

                    $termList = null;
                    $termList .= '<ul>';
                    foreach ($termsArray as $recordElement) {
                        $termList .= '<li>';
                        $mtName = get_mt_term(trim($recordElement));
                        if ($mtName != null)
                        //var_dump($mtName);
                            $termList .= $mtName;

                        $termList .= '</li>';
                    }
                    $termList .= '</ul>';
                    if ($termList != null) {
                        $record[$term] = $termList;
                    }
                } else {
                    $mtName = get_mt_term($record[$term]);
                    if ($mtName != null)
                        $record[$term] = $mtName;
                }
            }
        }
    }
}

function formArrayRefine(&$formArray) {

    foreach ($formArray as &$field) {
        if ($field['type'] == 'hidden') {
            $field['type'] = 'text';
        }
        $field['extra_opts']['clari'] = null;
        $field['extra_opts']['help'] = null;
        $field['extra_opts']['required'] = null;
    }
}

function mergeAddressFields(&$valuesArray) {

    foreach ($valuesArray as &$valuesField) {
        $addressList = null;
        $addressList .= "<ul>";
        if ($valuesField['address_type'] != null)
            $addressList = $addressList . "<li>" . _t('ADDRESS_TYPE') . ' : ' . get_mt_term($valuesField['address_type']) . "</li>";
        if ($valuesField['address'] != null)
            $addressList = $addressList . "<li>" . _t('ADDRESS') . ' : ' . $valuesField['address'] . "</li>";
        if ($valuesField['country'] != null)
            $addressList = $addressList . "<li>" . _t('COUNTRY') . ' : ' . get_mt_term($valuesField['country']) . "</li>";
        if ($valuesField['phone'] != null || $valuesField['cellular'] != null)
            $addressList = $addressList . "<li>" . _t('PHONE') . ' : ' . $valuesField['phone'] . ' | ' . _t('CELLULAR') . ' : ' . $valuesField['cellular'] . ' | ' . _t('FAX') . ' : ' . $valuesField['fax'] . "</li>";
        if ($valuesField['email'] != null || $valuesField['web'] != null)
            $addressList = $addressList . "<li>" . _t('EMAIL') . ' : ' . $valuesField['email'] . ' | ' . _t('WEBSITE') . ' : ' . $valuesField['web'] . "</li>";
        if ($valuesField['start_date'] != null || $valuesField['end_date'] != null)
            $addressList = $addressList . "<li>" . _t('START_DATE') . ' : ' . $valuesField['start_date'] . ' | ' . _t('END_DATE') . ' : ' . $valuesField['end_date'] . "</li>";

        $addressList .= "</ul>";

        $valuesField['person_addresses'] = $addressList;
    }
}

function is_locale_changed() {
    global $conf;
    if ($conf['fb_locale'] != $conf['locale'])
        return true;
    return false;
}

function is_management_field($formField) {

    if ($formField['field_number'] == "0172") {
        return true;
    }
    if ($formField['type'] == 'textarea' || $formField['type'] == 'text' || $formField['type'] == 'date' || $formField['type'] == 'hidden') {
        $fld_name = $formField['map']['field'];
        if (
                $fld_name == 'entity_type' ||
                $fld_name == 'entity_id' ||
                $fld_name == 'date_received' ||
                $fld_name == 'date_of_entry' ||
                $fld_name == 'entered_by' ||
                $fld_name == 'project_title' ||
                $fld_name == 'comments' ||
                $fld_name == 'record_grouping' ||
                $fld_name == 'date_updated' ||
                $fld_name == 'updated_by' ||
                $fld_name == 'monitoring_status'
        ) {
            return true;
        }
    }


    return false;
    /*
      if($formField['type'] == 'document' || $formField['type'] == 'mt_select' || $formField['type'] =='mt_tree' || $formField['type'] =='checkbox' || $formField['type'] =='radio'){
      return false;
      }
      $field_number = $formField['field_number'] %100;
      if( $field_number >=  60 && $field_number< 80 && !($field_number == 67 || $field_number == 66 )){
      return true;
      }else{
      return false;
      } */
}

function is_location_field($entity, $field) {
    global $global;
    $entity = $global['db']->qstr($entity);
    $field = $global['db']->qstr($field);
    $sql = "SELECT count(*) as counts FROM data_dict WHERE field_name = $field AND entity = $entity and field_type='location'";
    $counts = $global['db']->GetOne($sql); 
    return $counts!=0;
}

function is_mt_field($entity, $field) {
    global $global;
    $entity = $global['db']->qstr($entity);
    $field = $global['db']->qstr($field);
    $sql = "SELECT list_code FROM data_dict WHERE field_name = $field AND entity = $entity";
    $list_code = $global['db']->GetOne($sql);
    if ($list_code == 0)
        return false;
    else
        return true;
}

function data_uri($file, $mime) {
    $contents = file_get_contents($file);
    $base64 = base64_encode($contents);
    return "data:$mime;base64,$base64";
}

function get_chaintype_reverse($type) {
    $types = array("01000000000022" => "22000000000022",
        "02000000000022" => "21000000000022",
        "03000000000022" => "23000000000022",
        "11000000000022" => "11000000000022",
        "21000000000022" => "02000000000022",
        "22000000000022" => "01000000000022",
        "23000000000022" => "03000000000022");
    if ($types[$type]) {
        return $types[$type];
    }
    return $type;
}

function get_biography_reverse($type) {
    $types = array("01010300000021" => "01010400000021",
        "01010400000021" => "01010300000021",
        "01020300000021" => "01020400000021",
        "01020400000021" => "01020300000021",
        "01040200000021" => "01040300000021",
        "01040300000021" => "01040200000021",
        "01060100000021" => "01060200000021",
        "01060200000021" => "01060100000021",
        "01060201000021" => "01060100000021",
        "21010000000021" => "21020000000021",
        "21020000000021" => "21010000000021");
    if ($types[$type]) {
        return $types[$type];
    }
    return $type;
}

function get_module() {
    return $_GET['mod'];
}

function get_action() {
    return $_GET['act'];
}

function getDefaultMenuItems() {

    $module = get_module();
    $action = get_action();


    if ($module == "events") {
        $eid = (isset($_GET['eid'])) ? $_GET['eid'] : $_SESSION['eid'];
    } elseif ($module == "person") {
        $pid = (isset($_GET['pid'])) ? $_GET['pid'] : $_SESSION['pid'];
    } elseif ($module == "docu") {
        $doc_id = (isset($_GET['doc_id'])) ? $_GET['doc_id'] : $_SESSION['doc_id'];
    }

    $defaultMenuItems = array(
        "eventsbrowse" => array("level" => 0, "title" => _t('EVENTS'), "aclmod" => "events", "url" => get_url('events', 'browse', null, null, null, true), "module" => "events", "action" => "browse",
            "checkactive"=>"checkMenuActiveModule"),
        "get_event" => array("level" => 1, "title" => _t('EVENT_DESCRIPTION'), "aclmod" => "events", "parent" => "eventsbrowse",
            "url" => get_url('events', 'get_event', null, array('eid' => $eid), null, true), "module" => "events", "action" => "get_event",
            "aliases" => array("new_event", "edit_event", "delete_event")),
        "vp_list" => array("level" => 1, "title" => _t('VICTIMS_AND_PERPETRATORS'), "aclmod" => "events", "parent" => "eventsbrowse",
            "url" => get_url('events', 'vp_list', null, array('eid' => $eid), null, true), "module" => "events", "action" => "vp_list",
            "aliases" => array("add_act", "add_ad", "add_involvement", "add_perpetrator", "add_victim",
                "delete_act", "delete_ad", "edit_act",
                "edit_ad", "edit_involvement", "edit_perpetrator", "edit_victim")),
        "src_list" => array("level" => 1, "title" => _t('SOURCES'), "aclmod" => "events", "parent" => "eventsbrowse",
            "url" => get_url('events', 'src_list', null, array('eid' => $eid), null, true), "module" => "events", "action" => "src_list",
            "aliases" => array("add_information", "add_source", "delete_information", "edit_information", "edit_source",)),
        "intv_list" => array("level" => 1, "title" => _t('INTERVENTIONS'), "aclmod" => "events", "parent" => "eventsbrowse",
            "url" => get_url('events', 'intv_list', null, array('eid' => $eid), null, true), "module" => "events", "action" => "intv_list",
            "aliases" => array("add_intv", "add_intv_party", "delete_intervention", "edit_intv", "edit_intv_party",
        )),
        "coe_list" => array("level" => 1, "title" => _t('CHAIN_OF_EVENTS'), "aclmod" => "events", "parent" => "eventsbrowse",
            "url" => get_url('events', 'coe_list', null, array('eid' => $eid), null, true), "module" => "events", "action" => "coe_list",
            "aliases" => array("add_coe", "delete_coe", "edit_coe")),
        "event_doc_list" => array("level" => 1, "title" => _t('DOCUMENTS'), "aclmod" => "events", "parent" => "eventsbrowse",
            "url" => get_url('events', 'doc_list', null, array('eid' => $eid), null, true), "module" => "events", "action" => "doc_list",
            "aliases" => array()),
        "event_audit" => array("level" => 1, "title" => _t('AUDIT_LOG'), "aclmod" => "events", "parent" => "eventsbrowse",
            "url" => get_url('events', 'audit', null, array('eid' => $eid), null, true), "module" => "events", "action" => "audit",
            "aliases" => array()),
        "event_permissions" => array("level" => 1, "title" => _t('PERMISSIONS'), "aclmod" => "events", "parent" => "eventsbrowse",
            "url" => get_url('events', 'permissions', null, array('eid' => $eid), null, true), "module" => "events", "action" => "permissions",
            "aliases" => array(), "check" => create_function("", 'global $event;

if ($event->confidentiality == "y") {
return true;
}
return false;')),
        "personsbrowse" => array("level" => 0, "title" => _t('PERSONS'), "aclmod" => "person", "url" => get_url('person', 'browse', null, null, null, true),
            "module" => "person", "action" => "browse","checkactive"=>create_function('$menu','$defaultMenuItems = getDefaultMenuItems();
    $module = get_module();
    $action = get_action();
    $mod = $defaultMenuItems[$menu["slug"]]["module"];
    $act = $defaultMenuItems[$menu["slug"]]["action"];
    $aliases = array();
    if ($defaultMenuItems[$menu["slug"]]["aliases"]) {
        $aliases = $defaultMenuItems[$menu["slug"]]["aliases"];
    }
    if ($module == $mod && $action != "browse_biography") {
        return true;
    }
    return false;')),
        "person" => array("level" => 1, "title" => _t('PERSON_RECORDS_S_'), "aclmod" => "person", "parent" => "personsbrowse",
            "url" => get_url('person', 'person', null, array('pid' => $pid), null, true), "module" => "person", "action" => "person",
            "aliases" => array("new_person", "delete_person", "edit_person")),
        "person_address_list" => array("level" => 1, "title" => _t('PERSON_ADDRESS_ES_'), "aclmod" => "person", "parent" => "personsbrowse",
            "url" => get_url('person', 'address_list', null, array('pid' => $pid), null, true), "module" => "person", "action" => "address_list",
            "aliases" => array("delete_address",
                "new_address",
                "edit_address",)),
        "person_biography_list" => array("level" => 1, "title" => _t('BIOGRAPHIC_DETAIL_S_'), "aclmod" => "person", "parent" => "personsbrowse",
            "url" => get_url('person', 'biography_list', null, array('pid' => $pid), null, true), "module" => "person", "action" => "biography_list",
            "aliases" => array("delete_biographic",
                "edit_biography",
                "new_biography")),
        "person_role_list" => array("level" => 1, "title" => _t('ROLE_LIST'), "aclmod" => "person", "parent" => "personsbrowse",
            "url" => get_url('person', 'role_list', null, array('pid' => $pid), null, true), "module" => "person", "action" => "role_list",
            "aliases" => array()),
        "person_audit_log" => array("level" => 1, "title" => _t('AUDIT_LOG'), "aclmod" => "person", "parent" => "personsbrowse",
            "url" => get_url('person', 'audit_log', null, array('pid' => $pid), null, true), "module" => "person", "action" => "audit_log",
            "aliases" => array()),
        "person_permissions" => array("level" => 1, "title" => _t('PERMISSIONS'), "aclmod" => "person", "parent" => "personsbrowse",
            "url" => get_url('person', 'permissions', null, array('pid' => $pid), null, true), "module" => "person", "action" => "permissions",
            "aliases" => array(), "check" => create_function("", 'global $person;

if ($person->confidentiality == "y") {
return true;
}
return false;')),
        "documentsbrowse" => array("level" => 0, "title" => _t('DOCUMENTS'), "aclmod" => "docu", "url" => get_url('docu', 'browse', null, null, null, true),
            "module" => "docu", "action" => "browse","checkactive"=>"checkMenuActiveModule"),
        "view_document" => array("level" => 1, "title" => _t('DOCUMENT_DETAILS'), "aclmod" => "docu", "parent" => "documentsbrowse",
            "url" => get_url('docu', 'view_document', null, array('doc_id' => $doc_id), null, true), "module" => "docu", "action" => "view_document",
            "aliases" => array("new_document",
                "delete_document",
                "edit_document",)),
        "document_link" => array("level" => 1, "title" => _t('LINKS'), "aclmod" => "docu", "parent" => "documentsbrowse",
            "url" => get_url('docu', 'link', null, array('doc_id' => $doc_id), null, true), "module" => "docu", "action" => "link",
            "aliases" => array()),
        "document_audit" => array("level" => 1, "title" => _t('AUDIT_LOG'), "aclmod" => "docu", "parent" => "documentsbrowse",
            "url" => get_url('docu', 'audit', null, array('doc_id' => $doc_id), null, true), "module" => "docu", "action" => "audit",
            "aliases" => array()),
        "biographybrowse" => array("level" => 0, "title" => _t('BIOGRAPHIC_DETAILS'), "aclmod" => "person", "url" => get_url('person', 'browse_biography', null, null, null, true),
            "module" => "person", "action" => "browse_biography",'check'=>create_function('$menu','
               global $conf;
if ($conf["menus"]["biography_list"]) {
return true;
}
return false;
    ')),
        "addnew" => array("level" => 0, "url" => "#", "title" => _t('ADD_NEW')),
        "new_event" => array("level" => 1, "title" => _t('ADD_NEW_EVENT'), "aclmod" => "events", "parent" => "addnew",
            "prefix" => '<i class="icon-plus"></i>',
            "url" => get_url('events', 'new_event', null, array('eid' => null), null, true), "module" => "events", "action" => "new_event",
            "aliases" => array()),
        "add_act_full" => array("level" => 1, "title" => _t('ADD_ACT'), "aclmod" => "events", "parent" => "addnew",
            "prefix" => '<i class="icon-plus"></i>',
            "url" => get_url('events', 'add_act_full', null, array('eid' => null), null, true), "module" => "events", "action" => "add_act_full",
            "aliases" => array()),
       "new_person" => array("level" => 1, "title" => _t('ADD_NEW_PERSON'), "aclmod" => "person", "parent" => "addnew",
            "prefix" => '<i class="icon-plus"></i>',
            "url" => get_url('person', 'new_person', null, array('pid' => null), null, true), "module" => "person", "action" => "new_person",
            "aliases" => array()),
        "new_document" => array("level" => 1, "title" => _t('ADD_NEW_DOCUMENT'), "aclmod" => "docu", "parent" => "addnew",
            "prefix" => '<i class="icon-plus"></i>',
            "url" => get_url('docu', 'new_document', null, array('doc_id' => null), null, true), "module" => "docu", "action" => "new_document",
            "aliases" => array()),
        "add_user" => array("level" => 1, "title" => _t('ADD_NEW_USER'), "aclmod" => "admin", "parent" => "addnew",
            "prefix" => '<i class="icon-plus"></i>',
            "url" => get_url('admin', 'add_user', null, null, null, true), "module" => "admin", "action" => "add_user",
            "aliases" => array()),
        "dashboard" => array("level" => 0, "title" => _t('Dashboard'), "aclmod" => "dashboard",
            "url" => get_url('dashboard', 'dashboard', null, null, null, true), "module" => "dashboard", "action" => "dashboard",
            "aliases" => array(),"checkactive"=>"checkMenuActiveModule"),
        "analysis" => array("level" => 0, "title" => _t('ANALYSIS'), "aclmod" => "analysis",
            "url" => get_url('analysis', 'adv_search', null, null, null, true), "module" => "analysis", "action" => "adv_search",
            "aliases" => array(),"checkactive"=>"checkMenuActiveModule"),
        "adv_search" => array("level" => 1, "title" => _t('ADVANCED_SEARCH'), "aclmod" => "analysis", "parent" => "analysis",
            "url" => get_url('analysis', 'adv_search', null, null, null, true), "module" => "analysis", "action" => "adv_search",
            "aliases" => array()),
        "search_query" => array("level" => 1, "title" => _t('SAVED_QUERIES'), "aclmod" => "analysis", "parent" => "analysis",
            "url" => get_url('analysis', 'search_query', null, null, null, true), "module" => "analysis", "action" => "search_query",
            "aliases" => array()),
        "facetsearch" => array("level" => 1, "title" => _t('Charts and map'), "aclmod" => "analysis", "parent" => "analysis",
            "url" => get_url('analysis', 'facetsearch', null, null, null, true), "module" => "analysis", "action" => "facetsearch",
            "aliases" => array()),
        "edit_user" => array("level" => 0, "title" => _t('EDIT_PROFILE'), "aclmod" => "",
            "url" => get_url('home', 'edit_user', null, null, null, true), "module" => "home", "action" => "edit_user",
            "aliases" => array()),
        "edit_password" => array("level" => 0, "title" => _t('CHANGE_PASSWORD'), "aclmod" => "",
            "url" => get_url('home', 'edit_password', null, null, null, true), "module" => "home", "action" => "edit_password",
            "aliases" => array()),
        "edit_security" => array("level" => 0, "title" => _t('Security'), "aclmod" => "",
            "url" => get_url('home', 'edit_security', null, null, null, true), "module" => "home", "action" => "edit_security",
            "aliases" => array())
    );
    return $defaultMenuItems;
}

function checkMenuActiveDefault($menu) {
    $defaultMenuItems = getDefaultMenuItems();
    $module = get_module();
    $action = get_action();
    $mod = $defaultMenuItems[$menu["slug"]]["module"];
    $act = $defaultMenuItems[$menu["slug"]]["action"];
    $aliases = array();
    if ($defaultMenuItems[$menu["slug"]]["aliases"]) {
        $aliases = $defaultMenuItems[$menu["slug"]]["aliases"];
    }
    if (($action == $act && $module == $mod) || (in_array($action, $aliases))) {
        return true;
    }
    return false;
}
function checkMenuActiveModule($menu) {
    $defaultMenuItems = getDefaultMenuItems();
    $module = get_module();
    $action = get_action();
    $mod = $defaultMenuItems[$menu["slug"]]["module"];
    $act = $defaultMenuItems[$menu["slug"]]["action"];
    $aliases = array();
    if ($defaultMenuItems[$menu["slug"]]["aliases"]) {
        $aliases = $defaultMenuItems[$menu["slug"]]["aliases"];
    }
    if ($module == $mod) {
        return true;
    }
    return false;
}
function getMenus() {
    $defaultMenuItems = getDefaultMenuItems();
    $menuNames = array("top_menu" => array("title" => _t("Top menu"), "depth" => 2,
            "items" => array("eventsbrowse" => $defaultMenuItems["eventsbrowse"],
                "personsbrowse" => $defaultMenuItems["personsbrowse"],
                "documentsbrowse" => $defaultMenuItems["documentsbrowse"],
                "biographybrowse" => $defaultMenuItems["biographybrowse"],
                "addnew" => $defaultMenuItems["addnew"],
                "new_event" => $defaultMenuItems["new_event"],
                "add_act_full"=> $defaultMenuItems["add_act_full"],
                "new_person" => $defaultMenuItems["new_person"],
                "new_document" => $defaultMenuItems["new_document"],
                "add_user" => $defaultMenuItems["add_user"])),
        "top_right_menu" => array("title" => _t("Top right menu"), "depth" => 2,
            "items" => array("dashboard" => $defaultMenuItems["dashboard"],
                "analysis" => $defaultMenuItems["analysis"])
        ),
        "events_menu" => array("title" => _t("Event menu"), "depth" => 1,
            "items" => array("get_event" => $defaultMenuItems["get_event"],
                "vp_list" => $defaultMenuItems["vp_list"],
                "src_list" => $defaultMenuItems["src_list"],
                "intv_list" => $defaultMenuItems["intv_list"],
                "coe_list" => $defaultMenuItems["coe_list"],
                "event_doc_list" => $defaultMenuItems["event_doc_list"],
                "event_audit" => $defaultMenuItems["event_audit"],
                "event_permissions" => $defaultMenuItems["event_permissions"])
        ),
        "person_menu" => array("title" => _t("Person menu"), "depth" => 1,
            "items" => array("person" => $defaultMenuItems["person"],
                "person_address_list" => $defaultMenuItems["person_address_list"],
                "person_biography_list" => $defaultMenuItems["person_biography_list"],
                "person_role_list" => $defaultMenuItems["person_role_list"],
                "person_audit_log" => $defaultMenuItems["person_audit_log"],
                "person_permissions" => $defaultMenuItems["person_permissions"])
        ),
        "docu_menu" => array("title" => _t("Document menu"), "depth" => 1,
            "items" => array("view_document" => $defaultMenuItems["view_document"],
                "document_link" => $defaultMenuItems["document_link"],
                "document_audit" => $defaultMenuItems["document_audit"])
        ),
        "analysis_menu" => array("title" => _t("Analysis menu"), "depth" => 1,
            "items" => array("adv_search" => $defaultMenuItems["adv_search"],
                "search_query" => $defaultMenuItems["search_query"],
                "facetsearch" => $defaultMenuItems["facetsearch"])
        ),
        "home_menu" => array("title" => _t("Preferences menu"), "depth" => 1,
            "items" => array("edit_user" => $defaultMenuItems["edit_user"],
                "edit_password" => $defaultMenuItems["edit_password"],
                "edit_security" => $defaultMenuItems["edit_security"])
        )
    );
    return $menuNames;
}

function getMenu($menu) {
    $menuNames = getMenus();
    if (!$menuNames[$menu]) {
        return array();
    }
    $activeMenuItems = $menuNames[$menu]['items'];
    $activeMenuOrdered = array();
    $order = 0;
    $slugToID = array();
    $id = 1;
    foreach ($activeMenuItems as $key => $value) {
        $slugToID[$key] = $id;
        $value['order'] = $order;
        $value['slug'] = $key;
        $value['id'] = $id;
        if ($value['parent'] && $slugToID[$value['parent']]) {
            $value['parent'] = $slugToID[$value['parent']];
        } else {
            $value['parent'] = 0;
        }

        $activeMenuOrdered[$id] = $value;
        $order++;
        $id++;
    }
    $menuItemsNew = array();
    foreach ($activeMenuOrdered as $key => $menu) {
        $menuItemsNew[] = $menu;
    }

    $activeMenuOrdered = $menuItemsNew;
    return $activeMenuOrdered;
}

function makeLinksClickable($text){
    return preg_replace('!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1" target="_blank">$1</a>', $text);
}

function getAllFormats(){
    include_once APPROOT . 'mod/admin/entities.php';
    $entities = new Entities();
    return $entities->select_options();
}

function getActiveFormats(){
    global $conf;
    $formats = getAllFormats();
    foreach($formats as $key=>$value){
        if($conf['hide_'.$key.'_format'] == 'true'){
            unset($formats[$key]);
        }
    }

    return $formats;
}

function isFormatActive($format){
    global $conf;
    if(isset($conf['hide_'.$format.'_format']) && $conf['hide_'.$format.'_format'] == 'true'){
        return false;
    }
    return true;
}

