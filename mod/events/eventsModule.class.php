<?php

/**
 * Main Class of events module.
 *
 * This file is part of OpenEvsys.
 *
 * Copyright (C) 2009  Human Rights Information and Documentation Systems,
 *                     HURIDOCS), http://www.huridocs.org/, info@huridocs.org
 *
 * OpenEvsys is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OpenEvsys is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author	J P Fonseka <jo@respere.com>
 * @author	Kethees S <ks@respere.com>
 * @author	Nilushan Silva <nilushan@respere.com>
 * @author	Mahesh K K S <mahesh@respere.com>
 * @package	OpenEvsys
 * @subpackage	events
 *
 */
include_once APPROOT . 'inc/lib_entity_forms.inc';
include_once APPROOT . 'inc/lib_uuid.inc';
include_once APPROOT . 'inc/lib_form_util.inc';
include_once APPROOT . 'inc/lib_files.inc';
require_once APPROOT . 'inc/ArgumentEncoder.php';
include_once 'messages.inc';

class eventsModule extends shnModule {

    public $argumentEncoder;

    function act_default() {
        set_redirect_header('events', 'browse');
    }

    function section_mod_menu() {
        $data['breadcrumbs'] = shnBreadcrumbs::getBreadcrumbs();
        if ($_GET['act'] == 'new_event')
            $data['active'] = 'new';
        else if ($_GET['act'] == 'browse')
            $data['active'] = 'browse';
        return $data;
    }

    function __construct() {
        global $messages;
        global $event;
        $this->load_related_event();
        if (isset($_GET['act']) && !in_array($_GET['act'], array('new_event', 'browse', 'geocode', 'browse_act', 'browse_intervention', 'add_act_full'))) {
            $_GET['eid'] = (isset($_GET['eid'])) ? $_GET['eid'] : $_SESSION['eid'];
            if (!isset($_GET['eid'])) {
                shnMessageQueue::addInformation($messages['select_event']);
                set_redirect_header('events', 'browse');
                exit();
            }
            $event = new Event();
            $event->LoadFromRecordNumber($_GET['eid']);
            //if event does not exists
            if ($event->event_record_number != $_GET['eid'] || $event->event_record_number == '') {
                shnMessageQueue::addError($messages['event_not_found']);
                set_redirect_header('events', 'browse');
                exit();
            }
            $this->event = $event;
            $this->event_id = $event->event_record_number;
            $_SESSION['eid'] = $_GET['eid'];
            set_url_args('eid', $this->event->event_record_number);
        }

        $this->createArgumentEncoder();
    }

    public function createArgumentEncoder() {
        $whiteList = Array(
            'request_page', 'rpp', 'event_record_number', 'event_title',
            'initial_date', 'final_date', 'impact_of_event', 'project_title', 'filter', 
            'sort', 'sortorder'
        );
        
        $this->argumentEncoder = new ArgumentEncoder($whiteList);
    }

    private function load_related_event() {
        if (isset($_GET['inv_id'])) {
            $inv = new Involvement();
            $inv->LoadFromRecordNumber($_GET['inv_id']);
            $inv->LoadRelationships();
            $_GET['act_id'] = $inv->act;
            $act = new Act();
            $act->LoadFromRecordNumber($_GET['act_id']);
            $act->LoadRelationships();
            $_GET['eid'] = $act->event;
        } else if (isset($_GET['act_id'])) {
            $act = new Act();
            $act->LoadFromRecordNumber($_GET['act_id']);
            $act->LoadRelationships();
            $_GET['eid'] = $act->event;
        }
        if (isset($_GET['intervention_id'])) {
            $intervention = new Intervention();
            $intervention->LoadFromRecordNumber($_GET['intervention_id']);
            $intervention->LoadRelationships();
            $_GET['eid'] = $intervention->event;
        }
        if (isset($_GET['information_id'])) {
            $information = new Information();
            $information->LoadFromRecordNumber($_GET['information_id']);
            $information->LoadRelationships();
            $_GET['eid'] = $information->event;
        }
        if (isset($_GET['coe_id'])) {
            $coe = new ChainOfEvents();
            $coe->LoadFromRecordNumber($_GET['coe_id']);
            $coe->LoadRelationships();
            if ($_GET['reverse']) {
                $coe->reverse();
            }
            $_GET['eid'] = $coe->event;
        }
    }

    public function act_browse() {
        include_once APPROOT . 'inc/lib_form.inc';

        $notIn = acl_list_events_permissons();
        $notIn = 'allowed_records'; // passed to generateSql function to use the temporary table to find the allowed records
        require_once(APPROOT . 'mod/analysis/analysisModule.class.php');



        $analysisModule = new analysisModule();
        $sqlStatement = $analysisModule->generateSqlforEntity('event', null, $_GET, 'browse', $notIn);

        $entity_type_form_results = generate_formarray('event', 'browse');
        $entity_type_form_results['event_record_number']['type'] = 'text';
        $field_list = array();
        foreach ($entity_type_form_results as $field_name => $field) {
            // Generates the view's Label list
            $field_list[$field['map']['field']] = $field['label'];
        }

        foreach ($entity_type_form_results as $fieldName => &$field) {
            $field['extra_opts']['help'] = null;
            $field['label'] = null;
            $field['extra_opts']['clari'] = null;
            $field['extra_opts']['value'] = $_GET[$fieldName];
            $field['extra_opts']['required'] = null;
            $field['extra_opts']['class'] = "input-block-level";
        }

        $entity_fields_html = shn_form_get_html_fields($entity_type_form_results);
        $htmlFields = array();
        //iterate through the search fields, checking input values
        foreach ($entity_type_form_results as $field_name => $x) {
            // Generates the view's Label list
            $htmlFields[$field_name] = $entity_fields_html[$field_name];
        }

        //var_dump($sqlStatement);
        $this->result_pager = Browse::getExecuteSql($sqlStatement);
        $this->result_pager->setArgumentEncoder($this->argumentEncoder);

        $this->columnValues = $this->result_pager->get_page_data();
        $this->columnValues = set_links_in_recordset($this->columnValues, 'event');
        set_huriterms_in_record_array($entity_type_form_results, $this->columnValues);

        /*$sanitizedValues = [];
        foreach($this->columnValues[0] as $columnName => $columnValue) {
          $sanitizedValues[$columnName] = Reform::HtmlEncode($columnValue);
        }

        $this->columnValues[0] = $sanitizedValues;*/
        //rendering the view
        $this->columnNames = $field_list;
        $this->htmlFields = $htmlFields;
    }

    public function act_browse_act() {
        include_once APPROOT . 'inc/lib_form.inc';

        //$notIn = acl_list_acts_permissons();
        $notIn = 'allowed_records'; // passed to generateSql function to use the temporary table to find the allowed records
        require_once(APPROOT . 'mod/analysis/analysisModule.class.php');



        $analysisModule = new analysisModule();
        $sqlStatement = $analysisModule->generateSqlforEntity('act', null, $_GET, 'browse');

        $entity_type_form_results = generate_formarray('act', 'browse');
        $entity_type_form_results['act_record_number']['type'] = 'text';
        $field_list = array();
        foreach ($entity_type_form_results as $field_name => $field) {
            // Generates the view's Label list
            $field_list[$field['map']['field']] = $field['label'];
        }

        foreach ($entity_type_form_results as $fieldName => &$field) {
            $field['extra_opts']['help'] = null;
            $field['label'] = null;
            $field['extra_opts']['clari'] = null;
            $field['extra_opts']['value'] = $_GET[$fieldName];
            $field['extra_opts']['required'] = null;
            $field['extra_opts']['class'] = "input-block-level";
        }

        $entity_fields_html = shn_form_get_html_fields($entity_type_form_results);
        $htmlFields = array();
        //iterate through the search fields, checking input values
        foreach ($entity_type_form_results as $field_name => $x) {
            // Generates the view's Label list
            $htmlFields[$field_name] = $entity_fields_html[$field_name];
        }

        //var_dump($sqlStatement);
        $this->result_pager = Browse::getExecuteSql($sqlStatement);
        $this->columnValues = $this->result_pager->get_page_data();
        $additionalurlfields = array();
        $additionalurlfields["event"] = array("entity" => "event", "val" => "event");
        $additionalurlfields["victim"] = array("entity" => "victim", "val" => "victim");
        $this->columnValues = set_links_in_recordset($this->columnValues, 'act', $additionalurlfields);
        // var_dump($this->columnValues);exit;
        set_huriterms_in_record_array($entity_type_form_results, $this->columnValues);


        //rendering the view
        $this->columnNames = $field_list;
        $this->htmlFields = $htmlFields;
    }

    public function act_browse_intervention() {
        include_once APPROOT . 'inc/lib_form.inc';

        //$notIn = acl_list_acts_permissons();
        $notIn = 'allowed_records'; // passed to generateSql function to use the temporary table to find the allowed records
        require_once(APPROOT . 'mod/analysis/analysisModule.class.php');



        $analysisModule = new analysisModule();
        $sqlStatement = $analysisModule->generateSqlforEntity('intervention', null, $_GET, 'browse');

        $entity_type_form_results = generate_formarray('intervention', 'browse');
        $entity_type_form_results['intervention_record_number']['type'] = 'text';
        $field_list = array();
        foreach ($entity_type_form_results as $field_name => $field) {
            // Generates the view's Label list
            $field_list[$field['map']['field']] = $field['label'];
        }

        foreach ($entity_type_form_results as $fieldName => &$field) {
            $field['extra_opts']['help'] = null;
            $field['label'] = null;
            $field['extra_opts']['clari'] = null;
            $field['extra_opts']['value'] = $_GET[$fieldName];
            $field['extra_opts']['required'] = null;
            $field['extra_opts']['class'] = "input-block-level";
        }

        $entity_fields_html = shn_form_get_html_fields($entity_type_form_results);
        $htmlFields = array();
        //iterate through the search fields, checking input values
        foreach ($entity_type_form_results as $field_name => $x) {
            // Generates the view's Label list
            $htmlFields[$field_name] = $entity_fields_html[$field_name];
        }

        //var_dump($sqlStatement);
        $this->result_pager = Browse::getExecuteSql($sqlStatement);
        $this->columnValues = $this->result_pager->get_page_data();
        $additionalurlfields = array();
        $additionalurlfields["event"] = array("entity" => "event", "val" => "event");
        $additionalurlfields["victim"] = array("entity" => "victim", "val" => "victim");
        $additionalurlfields["intervening_party"] = array("entity" => "intervening_party", "val" => "intervening_party");

        $this->columnValues = set_links_in_recordset($this->columnValues, 'intervention', $additionalurlfields);
        // var_dump($this->columnValues);exit;
        set_huriterms_in_record_array($entity_type_form_results, $this->columnValues);


        //rendering the view
        $this->columnNames = $field_list;
        $this->htmlFields = $htmlFields;
    }

    /* {{{ Event actions */

    /**
     * act_new_event will generate ui to add an new event 
     * 
     * @access public
     * @return void
     */
    public function act_new_event() {
        $this->event_form = event_form('new');
        //if a save is request save the event
        if (isset($_POST['save'])) {
            $status = shn_form_validate($this->event_form);

            if ($status) {
                $event = new Event();
                $event->event_record_number = shn_create_uuid('event');
                form_objects($this->event_form, $event);

                $event->SaveAll();
                $this->event = $event;

                set_url_args('eid', $this->event->event_record_number);
                change_tpl('new_event_finish');
            }
        }
    }

    public function act_permissions() {
        $gacl_api = acl_get_gacl_api();
        $this->roles = acl_get_roles();
        if (isset($_POST['update'])) {
            foreach ($this->roles as $role_val => $role_name) {
                if ($role_val == 'admin')
                    continue;
                $acl_id = $gacl_api->search_acl('access', 'access', FALSE, FALSE, $role_name, 'events', $this->event->event_record_number, FALSE, FALSE);
                if (isset($_POST['roles']) && in_array($role_val, $_POST['roles'])) {
                    if (count($acl_id) == 0) {
                        $aro_grp = $gacl_api->get_group_id($role_val, $role_name, 'ARO');
                        $return = $gacl_api->add_acl(array('access' => array('access')), null, array($aro_grp), array('events' => array($this->event->event_record_number)), null, 1);
                    }
                } else {
                    $gacl_api->del_acl($acl_id[0]);
                }
            }
            set_redirect_header('events', 'permissions');
        }

        if (isset($_POST['add_user']) && $_POST['add_user'] != '') {
            $username = $_POST['add_user'];
            if (UserHelper::isUser($username)) {
                $return = $gacl_api->add_acl(array('access' => array('access')), array("users" => array($username)), null, array('events' => array($this->event->event_record_number)), null, 1);
            } else {
                shnMessageQueue::addError(_t('USERID_DOES_NOT_EXISTS_'));
            }
        }

        if (isset($_POST['remove_user'])) {
            $acl_id = $gacl_api->search_acl('access', 'access', 'users', $_POST['remove_user'], FALSE, 'events', $this->event->event_record_number, FALSE, FALSE);
            if (isset($acl_id[0]))
                $gacl_api->del_acl($acl_id[0]);
        }

        //populate checkboxes
        $this->value = array();
        foreach ($this->roles as $role_val => $role_name) {
            $acl_id = $gacl_api->search_acl('access', 'access', FALSE, FALSE, $role_name, 'events', $this->event->event_record_number, FALSE, FALSE);
            if (count($acl_id) > 0) {
                $this->value[$role_val] = $role_val;
            }
        }

        //get users with permissions
        $this->users = acl_get_allowed_users($this->event->event_record_number);
    }

    public function act_get_event() {
        $this->event->LoadRelationships();
        $event_form = event_form('view');

        popuate_formArray($event_form, $this->event);
        $this->event_form = $event_form;
    }

    public function act_edit_event() {
        $event_form = event_form('edit');
        if (isset($_POST['save'])) {
            $status = shn_form_validate($event_form);
            if ($status) {
                $this->event->LoadRelationships();

                form_objects($event_form, $this->event);
//var_dump($_POST,$this->event);exit;
                $this->event->SaveAll();
                set_redirect_header('events', 'get_event', null, array('eid' => $this->event->event_record_number));
                return;
            }
        }

        if (isset($this->event)) {
            $this->event->LoadRelationships();
            $this->event->LoadManagementData();
            popuate_formArray($event_form, $this->event);
            $this->event_form = $event_form;
        }
    }

    public function act_delete_event() {
        if (isset($_POST['no'])) {
            set_redirect_header('events', 'get_event');
            return;
        }

        $this->del_confirm = true;
        if ($this->event->event_record_number != null && isset($_POST['yes'])) {
            $this->event->DeleteFromRecordNumber($this->event->event_record_number);

            set_redirect_header('events', 'browse');
            return;
        }

        $this->events = Browse::getChainOfEvents($this->event->event_record_number);
        $this->events_reverse = Browse::getChainOfEventsReverse($this->event->event_record_number);
        $this->acts = Browse::getActsOfEvents($this->event->event_record_number);
        $this->involvements = Browse::getInvolvementsOfEvents($this->event->event_record_number);
        $this->informations = Browse::getInformationsOfEvents($this->event->event_record_number);
        $this->interventions = Browse::getInterventionsOfEvents($this->event->event_record_number);
    }

    /* }}} */

    /* {{{1 Victim & Perpetrator functions */

    public function act_duplicate_act() {
        if (!isset($_GET['act_id'])) {
            set_redirect_header('events', 'vp_list');
            return;
        }
        $act_id = $_GET['act_id'];
        $act = new Act();
        $act->LoadFromRecordNumber($act_id);
        $act->LoadRelationships();
        if ($act->act_record_number != $act_id || $act->act_record_number == '') {
            shnMessageQueue::addError($messages['act_not_found']);
            unset($_GET['act_id']);
            $this->act_vp_list();
            change_tpl('act_vp_list');
            return;
        }
        $this->victim = new Person();
        $this->victim->LoadFromRecordNumber($this->act->victim);

        $act_new = $act;
        $act_new->act_record_number = shn_create_uuid('act');
        $act_new->event = $this->event->event_record_number;
        $act_new->ClearManagementData();
        //var_dump($act_new);exit;
        $act_new->SaveAll();
        $_SESSION['vp']['act'] = $act_new->act_record_number;

        $ad_types = array('killing' => _t('KILLING'), 'destruction' => _t('DESTRUCTION'), 'arrest' => _t('ARREST'), 'torture' => _t('TORTURE'));
        foreach ($ad_types as $ad_type => $ad_name) {
            $ad_class = ucfirst($ad_type);
            $ad = new $ad_class();
            $record_number = $ad_type . '_record_number';
            $ad->LoadFromRecordNumber($act_id);
            $ad->LoadRelationships();

            if (isset($ad->$record_number)) {
                $ad_new = $ad;
                $ad_new->$record_number = $act_new->act_record_number;
                $ad_new->ClearManagementData();
                $ad_new->SaveAll();
            }
        }
        $vp_list = Browse::getVpListArray(array($act_id));
        foreach ($vp_list as $record) {

            $inv = new Involvement();
            $inv->LoadFromRecordNumber($record['involvement_record_number']);
            $inv->LoadRelationships();
            if ($inv->involvement_record_number != $record['involvement_record_number'] || $inv->involvement_record_number == '') {
                continue;
            }
            $inv->involvement_record_number = shn_create_uuid('inv');
            $inv->event = $this->event_id;
            $inv_new = $inv;
            $inv_new->act = $act_new->act_record_number;

            $inv_new->ClearManagementData();

            $inv_new->SaveAll();
        }

//shnMessageQueue::addInformation($messages['select_event']);
        set_redirect_header('events', 'vp_list');
        return;
        $this->act_vp_list();
        change_tpl('act_vp_list');
    }

    /**
     * act_vp_list will list victim and perpetrator related to a perticuler event
     * 
     * @access public
     * @return void
     */
    public function act_vp_list() {
        $this->vp_list = Browse::getVpList($this->event_id);
        $_SESSION['vp'] = null;
        $_SESSION["acts"] = null;
        var_export($_SESSION);
        //if an involvement is requested
        if (isset($_GET['inv_id']))
            $this->set_inv();
        //if an act is requested
        if (isset($_GET['act_id']))
            $this->set_act();

        switch ($_GET['type']) {
            case 'victim':
                $this->victim = new Person();
                $this->victim->LoadFromRecordNumber($this->act->victim);
                $this->victim->LoadRelationships();
                $this->victim->LoadAddresses();
                $this->victim->LoadPicture();
                break;
            case 'act':
                //check if additional detals exists
                $ad_types = array('killing' => _t('KILLING'), 'destruction' => _t('DESTRUCTION'), 'arrest' => _t('ARREST'), 'torture' => _t('TORTURE'));
                foreach ($ad_types as $ad_type => $ad_name) {
                    $ad_class = ucfirst($ad_type);
                    $ad = new $ad_class();
                    $record_number = $ad_type . '_record_number';
                    $ad->LoadFromRecordNumber($this->act->act_record_number);
                    $ad->LoadRelationships();
                    if (isset($ad->$record_number)) {
                        $this->ad_type = $ad_type;
                        $this->ad = $ad;
                        break;
                    }
                }
                break;
            case 'perter':
                $this->perpetrator = new Person();
                $this->perpetrator->LoadFromRecordNumber($this->inv->perpetrator);
                $this->perpetrator->LoadRelationships();
                $this->perpetrator->LoadAddresses();
                $this->perpetrator->LoadPicture();
                break;
            case 'inv':
                break;
        }
    }

    protected function set_act() {
        global $messages;
        $act = new Act();
        $act->LoadFromRecordNumber($_GET['act_id']);
        $act->LoadRelationships();
        $this->set_victim_dob($act->victim);
        if ($act->act_record_number != $_GET['act_id'] || $act->act_record_number == '') {
            shnMessageQueue::addError($messages['act_not_found']);
            unset($_GET['type']);
        }
        else
            $this->act = $act;
    }

    protected function set_victim_dob($person_id) {
        $person = new Person();
        $person->LoadfromRecordNumber($person_id);
        $this->victim_dob = $person->date_of_birth;
        $this->victim_dob_type = $person->date_of_birth_type;
    }

    protected function set_inv() {
        global $messages;
        $inv = new Involvement();
        $inv->LoadFromRecordNumber($_GET['inv_id']);
        $inv->LoadRelationships();
        if ($inv->involvement_record_number != $_GET['inv_id'] || $inv->involvement_record_number == '') {
            shnMessageQueue::addError($messages['involvement_not_found']);
            unset($_GET['type']);
        } else {
            $this->inv = $inv;
            $_GET['act_id'] = $this->inv->act;
        }
    }

    /* {{{2 victim action */

    /**
     * act_add_victim Allow you to select or add a victim 
     * 
     * @access public
     * @return void
     */
    public function act_add_victim() {
        //if a new person save 
        if (isset($_POST['save'])) {
            $this->victim = $this->save_person();
            $_SESSION['vp']['victim'] = $this->victim->person_record_number;
            set_redirect_header('events', 'add_act', null, array('victim' => $_SESSION['vp']['victim']));
        } else if (isset($_REQUEST['person_id'])) {
            $this->victim = new Person();
            $this->victim->LoadFromRecordNumber($_REQUEST['person_id']);
            $_SESSION['vp']['victim'] = $this->victim->person_record_number;

            $this->victim->LoadPicture();
        }
    }

    /* }}} */

    function act_add_act_full() {
        $events_form = array('related_event' => array(
                'type' => 'related_event', 'label' => _t('Event'),
                'field_number' => '2203', 'view_type' => 'new',
                'map' => array('entity' => 'act', 'field' => 'related_event',
                    'mt' => '0', 'mlt' => false, 'link_table' => NULL,
                    'link_field' => NULL,), 'extra_opts' => array(
                    'label' => _t('Event'), 'clari' => false,
                    'validation' => array(0 => '', 1 => 'notnull',),
                    'required' => true, 'help' => '2203',)));

        if (isset($_GET['event_id'])) {
            $_SESSION['eid'] = $_GET['event_id'];
            set_redirect_header('events', 'add_victim', null, array('eid' => $_SESSION['eid'], 'view' => 'search_victim'));
        }

        $this->events_form = $events_form;
    }

    public function act_add_act() {
       
        if (isset($_REQUEST['acts'])) {
            $_SESSION['eid'] = $_GET['eid'];
            $acts = $_REQUEST['acts'];

            $act_id = $acts[0];
            $act = new Act();
            $act->LoadFromRecordNumber($act_id);
            $act->LoadRelationships();
            $victim = $act->victim;
            $_SESSION['vp']['victim'] = $victim;
            
        } else {
            $victim = $_REQUEST['victim'];
        }
        //fetch the victim
        $this->victim = new Person();
        $this->victim->LoadFromRecordNumber($victim);

        $this->set_victim_dob($victim);
        //if action is not set
        $this->act_form = act_form('new');
        if (isset($_POST['save']) || isset($_POST['add_ad']) || isset($_POST['save_without'])) {
            $status = shn_form_validate($this->act_form);
            if ($status) {
                $act = new Act();
                $act->act_record_number = shn_create_uuid('act');
                form_objects($this->act_form, $act);
                $act->event = $this->event->event_record_number;
                $act->SaveAll();
                $_SESSION['vp']['act'] = $act->act_record_number;

                if (isset($_POST['add_ad'])) {
                    set_redirect_header('events', 'add_ad', null, array('act_id' => $act->act_record_number));
                } elseif (isset($_POST['save_without'])) {
                    set_redirect_header('events', 'vp_list', null, null);
                } else {
                    set_redirect_header('events', 'add_perpetrator', null, array('act_id' => $act->act_record_number));
                }
            }
        }
    }

    public function act_add_ad() {
        $_GET['act_id'] = (isset($_GET['act_id'])) ? $_GET['act_id'] : $_SESSION['act_id'];
        $_SESSION['act_id'] = $_GET['act_id'];

        $this->types = array('killing' => _t('KILLING'), 'destruction' => _t('DESTRUCTION'), 'arrest' => _t('ARREST'), 'torture' => _t('TORTURE'));

        $this->act = new Act();
        $this->act->LoadFromRecordNumber($_GET['act_id']);
        $this->act_name = get_mt_term($this->act->type_of_act);

        if (isset($_POST['type']) && isset($this->types[$_POST['type']]))
            $this->type = $_POST['type'];

        if (isset($_POST['save']) && isset($this->type)) {
            $ad_name = $this->types[$this->type];
            $ad_class = ucfirst($this->type);
            $ad = new $ad_class();
            $ad_form = generate_formarray($this->type, 'new');
            $ad->LoadFromRecordNumber($_GET['act_id']);

            $rec_number = $this->type . '_record_number';
            $ad->$rec_number = $_GET['act_id'];
            form_objects($ad_form, $ad);
            ;

            $ad->SaveAll();

            set_redirect_header('events', 'add_perpetrator', null, array('act_id' => $this->act->act_record_number));
        }
    }

    public function act_edit_ad() {
        $this->vp_list = Browse::getVpList($this->event_id);
        //if an involvement is requested
        if (isset($_GET['act_id']))
            $this->set_act();

        $ad_types = array('killing' => _t('KILLING'), 'destruction' => _t('DESTRUCTION'), 'arrest' => _t('ARREST'), 'torture' => _t('TORTURE'));
        $this->ad_types = $ad_types;
        foreach ($ad_types as $ad_type => $ad_name) {
            $class = ucfirst($ad_type);
            $ad = new $class();
            $record_number = $ad_type . '_record_number';
            $ad->LoadFromRecordNumber($this->act->act_record_number);
            $ad->LoadRelationships();
            if (isset($ad->$record_number)) {
                $this->ad_type = $ad_type;
                $this->ad_name = $ad_name;
                $this->ad = $ad;
                break;
            }
        }

        if (isset($_POST['ad_type']) && array_key_exists($_POST['ad_type'], $ad_types))
            $this->ad_type = $_POST['ad_type'];

        if (isset($_POST['update']) && isset($this->ad_type)) {
            $class = ucfirst($this->ad_type);
            $ad = new $class();
            $ad_form = generate_formarray($this->ad_type, 'edit');
            $ad->LoadFromRecordNumber($this->act->act_record_number);
            $ad->LoadManagementData();
            $ad->LoadRelationships();
            $rec_number = $this->ad_type . '_record_number';
            $ad->$rec_number = $this->act->act_record_number;
            form_objects($ad_form, $ad);
            $ad->SaveAll();
            set_redirect_header('events', 'vp_list', null, array('act_id' => $this->act->act_record_number, 'type' => 'act'));
        }
        //check if ad exists 
    }

    public function act_delete_ad() {
        if (isset($_GET['act_id']))
            $this->set_act();
        if (isset($_POST['yes'])) {
            $ad_types = array('killing' => _t('KILLING'), 'destruction' => _t('DESTRUCTION'), 'arrest' => _t('ARREST'), 'torture' => _t('TORTURE'));
            $this->ad_types = $ad_types;
            foreach ($ad_types as $ad_type => $ad_name) {
                $class = ucfirst($ad_type);
                $ad = new $class();
                $res = $ad->DeleteFromRecordNumber($this->act->act_record_number);
            }
            set_redirect_header('events', 'vp_list', null, array('act_id' => $this->act->act_record_number, 'type' => 'act'));
        } else {
            $_GET['type'] = 'act';
            $this->delete_ad = true;
            $this->act_vp_list();
            change_tpl('act_vp_list');
        }
    }

    public function act_add_perpetrator() {
        //set the act number
        if (isset($_REQUEST['acts'])) {
            $acts = $_REQUEST['acts'];
        } elseif (isset($_GET['act_id'])) {
            $acts = array($_GET['act_id']);
        } else {
            $acts = $_SESSION['acts'];
        }
        $_SESSION['acts'] = $acts;
        if (!$acts) {
            set_redirect_header('events', 'vp_list');
        }

        //if a new person save 
        if (isset($_POST['save'])) {
            $this->perpetrator = $this->save_person();
            $_SESSION['vp']['perpetrator'] = $this->perpetrator->person_record_number;
            set_redirect_header('events', 'add_involvement', null, array('perpetrator' => $this->perpetrator->person_record_number));
        } else if (isset($_REQUEST['person_id'])) {
            $this->perpetrator = new Person();
            $this->perpetrator->LoadFromRecordNumber($_REQUEST['person_id']);
            $_SESSION['vp']['perpetrator'] = $this->perpetrator->person_record_number;
            $this->perpetrator->LoadPicture();
        }

        $acts_array = array();
        foreach ($_SESSION['acts'] as $act_id) {
            $act = new Act();
            $act->LoadFromRecordNumber($act_id);
            $victim = new Person();
            $victim->LoadFromRecordNumber($act->victim);
            $act_array = array();
            $act_array['act'] = $act;
            $act_array['act_name'] = get_mt_term($act->type_of_act);

            $act_array['victim'] = $victim;
            $acts_array[] = $act_array;
        }
        $this->acts = $acts_array;
    }

    public function act_add_involvement() {
        $involvement_form = involvement_form('new');
        $this->involvement_form = $involvement_form;
        //if finish save and go to vp_list
        if (isset($_POST['finish'])) {
            $status = shn_form_validate($this->involvement_form);
            if ($status) {
                $this->save_involvement();
                set_redirect_header('events', 'vp_list');
            }
        }
        //if add more go to perpotrator
        if (isset($_POST['more'])) {
            $status = shn_form_validate($this->involvement_form);
            if ($status) {
                $this->save_involvement();
                $this->act_add_perpetrator();
                set_redirect_header('events', 'add_perpetrator', null, array('acts' => $_SESSION['acts']));
                return;
            }
        }

        $acts_array = array();
        foreach ($_SESSION['acts'] as $act_id) {
            $act = new Act();
            $act->LoadFromRecordNumber($act_id);
            $victim = new Person();
            $victim->LoadFromRecordNumber($act->victim);
            $perpetrator = new Person();
            $perpetrator->LoadFromRecordNumber($_SESSION['vp']['perpetrator']);
            $act_array = array();
            $act_array['act'] = $act;
            $act_array['act_name'] = get_mt_term($act->type_of_act);
            $act_array['perpetrator'] = $perpetrator;

            $acts_array[] = $act_array;
        }
        $this->acts = $acts_array;

        /* $this->act = new Act();
          $this->act->LoadFromRecordNumber($_SESSION['act_id']);
          $this->act_name = get_mt_term($this->act->type_of_act);
          $this->perpetrator = new Person();
          $this->perpetrator->LoadFromRecordNumber($_SESSION['vp']['perpetrator']); */
    }

    protected function save_involvement() {
        $invs = array();
        foreach ($_SESSION['acts'] as $act_id) {
            $involvement_form = involvement_form('new');
            $inv = new Involvement();
            $inv->involvement_record_number = shn_create_uuid('inv');
            form_objects($involvement_form, $inv);
            $inv->event = $this->event_id;
            $inv->act = $act_id;
            $inv->perpetrator = $_SESSION['vp']['perpetrator'];
            $inv->SaveAll();
            $invs[] = $inv;
        }
        return $invs;
    }

    public function act_edit_victim() {
        $person_form = person_form('edit');

        $this->vp_list = Browse::getVpList($this->event_id);

        if (isset($_POST['update'])) {
            $status = shn_form_validate($person_form);
            if ($status) {
                $this->edit_person($_REQUEST['pid'], $person_form);
                $_GET['type'] = 'victim';
                set_redirect_header('events', 'vp_list', null, array('act_id' => $_REQUEST['act_id'], 'type' => 'victim'));
                return;
            }
        }

        if ($_GET['act_id']) {
            $this->act = new Act();
            $this->act->LoadFromRecordNumber($_GET['act_id']);
            $this->edit_person_information($this->act->victim, $person_form);
        }
    }

    public function act_edit_perpetrator() {
        $person_form = person_form('edit');

        $this->vp_list = Browse::getVpList($this->event_id);

        if (isset($_POST['update'])) {
            $status = shn_form_validate($person_form);
            if ($status) {
                $this->edit_person($_REQUEST['pid'], $person_form);
                $_GET['type'] = 'perter';
                set_redirect_header('events', 'vp_list', null, array('inv_id' => $_GET['inv_id'], 'type' => 'perter'));
                return;
            }
        }

        if ($_GET['inv_id']) {
            $inv = new Involvement();
            $inv->LoadFromRecordNumber($_GET['inv_id']);
            $this->edit_person_information($inv->perpetrator, $person_form);
        }
    }

    public function act_edit_source() {
        $person_form = person_form('edit');

        $this->sources = Browse::getSourceListforEvent($this->event->event_record_number);

        if (isset($_POST['update'])) {
            $status = shn_form_validate($person_form);
            if ($status) {
                $this->edit_person($_REQUEST['pid'], $person_form);
                $_GET['type'] = 'person';
                set_redirect_header('events', 'src_list', null, array('information_id' => $_GET['information_id'], 'type' => 'person'));
                return;
            }
        }

        if (isset($_GET['information_id'])) {
            $information = new Information();
            $information->LoadFromRecordNumber($_GET['information_id']);
            $this->edit_person_information($information->source, $person_form);
        }
    }

    public function act_edit_intv_party() {
        $person_form = person_form('edit');

        $this->intv_list = Browse::getIntvList($this->event->event_record_number);

        if (isset($_POST['update'])) {
            $status = shn_form_validate($person_form);
            if ($status) {
                $this->edit_person($_REQUEST['pid'], $person_form);
                $_GET['type'] = 'intv_party';
                set_redirect_header('events', 'intv_list', null, array('intervention_id' => $_GET['intervention_id'], 'type' => 'intv_party'));
                return;
            }
        }

        if (isset($_GET['intervention_id'])) {
            $intervention = new Intervention();
            $intervention->LoadFromRecordNumber($_GET['intervention_id']);
            $this->edit_person_information($intervention->intervening_party, $person_form);
        }
    }

    public function act_edit_act() {
        $act_form = act_form('edit');

        $this->vp_list = Browse::getVpList($this->event_id);

        if (isset($_POST['update'])) {
            $status = shn_form_validate($act_form);

            if ($status) {
                $act = new Act();
                $act->LoadFromRecordNumber($_REQUEST['act_id']);
                $act->LoadRelationships();
                $act->LoadManagementData();
                form_objects($act_form, $act);
                $act->SaveAll();
                $this->act = $act;
                $_GET['type'] = 'act';
                set_redirect_header('events', 'vp_list', null, array('act_id' => $_REQUEST['act_id'], 'type' => 'act'));
                return;
            }
        }
        $this->act_form = $act_form;
        //if an involvement is requested
        if (isset($_GET['inv_id']))
            $this->set_inv();
        //if an act is requested
        if (isset($_GET['act_id']))
            $this->set_act();
    }

    /* {{{ involvement edit function */

    public function act_edit_involvement() {
        $this->vp_list = Browse::getVpList($this->event_id);
        $involvement_form = involvement_form('edit');
        if (isset($_POST['update'])) {
            $status = shn_form_validate($involvement_form);
            if ($status) {
                $inv = new Involvement();
                $inv->LoadFromRecordNumber($_REQUEST['inv_id']);
                $inv->LoadRelationships();
                $inv->LoadManagementData();
                form_objects($involvement_form, $inv);

                $inv->SaveAll();
                set_redirect_header('events', 'vp_list', null, array('inv_id' => $inv->involvement_record_number, 'type' => 'inv'));
                return;
            }
        }
        //if an involvement is requested
        if (isset($_GET['inv_id']))
            $this->set_inv();
        //if an act is requested
        if (isset($_GET['act_id']))
            $this->set_act();
    }

    /* }}} */

    public function act_delete_act() {
        if (isset($_POST['no'])) {
            set_redirect_header('events', 'vp_list');
            return;
        }
        if ($_GET['act_id']) {
            $act_ids = array($_GET['act_id']);
        } else {
            $act_ids = $_POST['acts'];
        }
        $this->act_ids = $act_ids;
        $this->del_confirm = true;
        if (isset($_POST['yes'])) {
            if (isset($_POST['act'])) {
                array_push($act_ids, $_POST['act']);
            } else if (isset($_POST['inv'])) {
                array_push($_POST['invs'], $_POST['inv']);
            }
            //if multiplt events are selected
            if (is_array($act_ids)) {
                foreach ($act_ids as $act) {
                    $c = new Act();
                    $c->DeleteFromRecordNumber($act);
                }
            } else if (is_array($_POST['invs'])) {
                foreach ($_POST['invs'] as $inv) {
                    $i = new Involvement();
                    $i->DeleteFromRecordNumber($inv);
                }
            }
            set_redirect_header('events', 'vp_list');
            return;
        }

        if (isset($act_ids)) {
            $this->vp_list = Browse::getVpListArray($act_ids);
        } else if (isset($_POST['invs'])) {
            $this->vp_list = Browse::getVpListInvArray($_POST['invs']);
        }
    }

    /* }}} */

    /* {{{ Source actions */

    /**
     * act_src_list Will display a list of source 
     * 
     * @access public
     * @return void
     */
    public function act_src_list() {
        $this->sources = Browse::getSourceListforEvent($this->event->event_record_number);

        if (isset($_GET['information_id']))
            $this->set_information();

        switch ($_GET['type']) {
            case 'person':
                $this->source = new Person();
                $this->source->LoadFromRecordNumber($this->information->source);
                $this->source->LoadRelationships();
                $this->source->LoadAddresses();
                $this->source->LoadPicture();
                break;
        }
    }

    protected function set_information() {
        global $messages;
        $information = new Information();
        $information->LoadFromRecordNumber($_GET['information_id']);
        $information->LoadRelationships();
        if ($information->information_record_number != $_GET['information_id'] || $information->information_record_number == '') {
            shnMessageQueue::addError($messages['information_not_found']);
            unset($_GET['type']);
        }
        else
            $this->information = $information;
    }

    public function act_add_source() {
        if (isset($_POST['save'])) {
            $this->source = $this->save_person();
            $_SESSION['src']['source'] = $this->source->person_record_number;
            set_redirect_header('events', 'add_information');
        } else if (isset($_REQUEST['person_id'])) {
            $this->source = new Person();
            $this->source->LoadFromRecordNumber($_REQUEST['person_id']);
            $_SESSION['src']['source'] = $this->source->person_record_number;
            $this->source->LoadPicture();
        }
    }

    public function act_add_information() {
        $this->information_form = information_form('new');
        if (isset($_POST['finish'])) {
            $status = shn_form_validate($this->information_form);
            if ($status) {
                $this->save_information();
                //unset($_POST);			
                set_redirect_header('events', 'src_list');
            }
        }
        $this->information_form['event']['extra_opts']['value'] = $this->event_id;
        $this->information_form['source']['extra_opts']['value'] = $_SESSION['src']['source'];
    }

    public function act_edit_information() {
        $this->sources = Browse::getSourceListforEvent($this->event->event_record_number);

        if (isset($_POST['update'])) {
            $this->information = $this->save_information();
            unset($_POST);
            $_GET['type'] = 'information';
            set_redirect_header('events', 'src_list', null, array('information_id' => $this->information->information_record_number, 'type' => 'information'));
        } else {
            $_GET['information_id'] = (isset($_GET['information_id']) ? $_GET['information_id'] : $_POST['information_record_number']);
            $information = new Information();
            $information->LoadFromRecordNumber($_GET['information_id']);
            $information->LoadRelationships();

            $information_form = information_form('edit');
            popuate_formArray($information_form, $information);
            $this->information_form = $information_form;
        }
    }

    public function act_delete_information() {
        if (!isset($_POST['informations']) || isset($_POST['no'])) {
            set_redirect_header('events', 'src_list');
            return;
        }

        $this->del_confirm = true;
        if (isset($_POST['yes'])) {
            if (isset($_POST['information']))
                array_push($_POST['informations'], $_POST['information']);
            //if multiplt events are selected
            if (is_array($_POST['informations'])) {
                foreach ($_POST['informations'] as $information) {
                    $i = new Information();
                    $i->DeleteFromRecordNumber($information);
                }
            }
            set_redirect_header('events', 'src_list');
            return;
        }

        $this->sources = Browse::getSourceListArray($_POST['informations']);
    }

    /* }}} */

    /* {{{1 Intervention actions */

    public function act_intv_list() {
        $this->intv_list = Browse::getIntvList($this->event->event_record_number);

        $_SESSION['intv'] = null;

        if (isset($_GET['intervention_id']))
            $this->set_intervention();

        switch ($_GET['type']) {
            case 'intv_party':
                $this->intervening_party = new Person();
                $this->intervening_party->LoadFromRecordNumber($this->intervention->intervening_party);
                $this->intervening_party->LoadRelationships();
                $this->intervening_party->LoadAddresses();
                $this->intervening_party->LoadPicture();
                break;
            case 'intv':
                break;
        }
    }

    protected function set_intervention() {
        global $messages;
        $intervention = new Intervention();
        $intervention->LoadFromRecordNumber($_GET['intervention_id']);
        $intervention->LoadRelationships();
        if ($intervention->intervention_record_number != $_GET['intervention_id'] || $intervention->intervention_record_number == '') {
            shnMessageQueue::addError($messages['intervention_not_found']);
            unset($_GET['type']);
        }
        else
            $this->intervention = $intervention;
    }

    public function act_add_intv_party() {
        if (isset($_POST['save'])) {
            $this->intv_party = $this->save_person();
            $_SESSION['intv']['intv_party'] = $this->intv_party->person_record_number;
            set_redirect_header('events', 'add_intv');
        } else if (isset($_REQUEST['person_id'])) {
            $this->intv_party = new Person();
            $this->intv_party->LoadFromRecordNumber($_REQUEST['person_id']);
            $_SESSION['intv']['intv_party'] = $this->intv_party->person_record_number;
            $this->intv_party->LoadPicture();
        }
    }

    public function act_edit_intv() {
        $this->intv_list = Browse::getIntvList($this->event->event_record_number);

        $_GET['intervention_id'] = (isset($_GET['intervention_id']) ? $_GET['intervention_id'] : $_POST['intervention_record_number']);
        $this->intv = new Intervention();
        $this->intv->LoadFromRecordNumber($_GET['intervention_id']);
        $this->intv->LoadRelationships();

        $this->intervention_form = intervention_form('edit');

        if (isset($_POST['update'])) {
            $status = shn_form_validate($this->intervention_form);
            if ($status) {
                form_objects($this->intervention_form, $this->intv);
                if (trim($this->intv->victim) == '') {
                    $this->intv->victim = null;
                }
                $this->intv->SaveAll();
                $_GET['type'] = 'intv';
                set_redirect_header('events', 'intv_list', null, array('intervention_id' => $this->intv->intervention_record_number, 'type' => 'intv'));
                return;
            }
        }

        popuate_formArray($this->intervention_form, $this->intv);
    }

    public function act_add_intv() {
        $this->intervention_form = intervention_form('new');
        if (isset($_POST['finish'])) {
            $status = shn_form_validate($this->intervention_form);
            if ($status) {
                $this->save_intervention();
                //unset($_POST);
                set_redirect_header('events', 'intv_list');
            }
        }

        $this->intervention_form['event']['extra_opts']['value'] = $this->event_id;
        $this->intervention_form['intervening_party']['extra_opts']['value'] = $_SESSION['intv']['intv_party'];
    }

    public function act_delete_intervention() {
        if ($_GET['intervention_id']) {
            $intervention_ids = array($_GET['intervention_id']);
        } else {
            $intervention_ids = $_POST['interventions'];
        }
        if (!$intervention_ids || isset($_POST['no'])) {
            set_redirect_header('events', 'intv_list');
            return;
        }
        $this->intervention_ids = $intervention_ids;

        $this->del_confirm = true;
        if (isset($_POST['yes'])) {
            if (isset($_POST['intervention']))
                array_push($intervention_ids, $_POST['intervention']);
            //if multiplt events are selected
            if (is_array($intervention_ids)) {
                foreach ($intervention_ids as $intervention) {
                    $i = new Intervention();
                    $i->DeleteFromRecordNumber($intervention);
                }
            }
            set_redirect_header('events', 'intv_list');
            return;
        }

        $this->intv_list = Browse::getIntvListArray($intervention_ids);
    }

    /* }}} */

    /* {{{1 Chane of events actions */

    public function act_coe_list() {
        $this->related_events = Browse::getChainOfEvents($this->event->event_record_number);
        $this->related_events_reverse = Browse::getChainOfEventsReverse($this->event->event_record_number);

        if (isset($_GET['coe_id'])) {
            global $messages;
            $coe = new ChainOfEvents();
            $coe->LoadFromRecordNumber($_GET['coe_id']);
            if ($_GET['reverse']) {
                $coe->reverse();
            }
            $coe->LoadRelationships();

            if ($coe->chain_of_events_record_number != $_GET['coe_id'] || $coe->chain_of_events_record_number == '') {
                shnMessageQueue::addError($messages['coe_not_found']);
                unset($_GET['type']);
            }
            else
                $this->coe = $coe;
        }

        switch ($_GET['type']) {
            case 'coe':
                $this->related_event = new Event();
                $this->related_event->LoadFromRecordNumber($coe->related_event);
                $this->related_event->LoadRelationships();
                break;
        }
    }

    function act_add_coe() {
        $chain_of_events_form = chain_of_events_form('new');
        if (isset($_POST['save'])) {
            $status = shn_form_validate($chain_of_events_form);
            if ($status) {
                $this->event_id = $this->event->event_record_number;
                $coe = new ChainOfEvents();
                form_objects($chain_of_events_form, $coe);
                $coe->event = $this->event_id;
                $coe->SaveAll();
                $this->coeid = $coe->chain_of_events_record_number;
                $coe->LoadFromRecordNumber($this->coeid);
                $coe->LoadRelationships();

                popuate_formArray($chain_of_events_form, $coe);
                change_tpl('coe_finish');
            }
        }

        $this->chain_of_events_form = $chain_of_events_form;
    }

    function act_edit_coe() {
        $chain_of_events_form = chain_of_events_form('edit');
        $this->chain_of_events_form = $chain_of_events_form;

        if (isset($_GET['coeid'])) {
            $coe = new ChainOfEvents();
            $coe->LoadFromRecordNumber($_GET['coeid']);
            $coe->LoadRelationships();
            $coe->LoadManagementData();
            popuate_formArray($chain_of_events_form, $coe);
            $this->chain_of_events_form = $chain_of_events_form;
        } else if (isset($this->event->event_record_number)) {
            $event = new Event();
            $event->LoadFromRecordNumber($this->event->event_record_number);
            $event->LoadRelationships();
            $event->LoadManagementData();
            $event_form = event_form('new');
            popuate_formArray($event_form, $event);
            $this->events = $event_form;
        }

        if (isset($_POST['update'])) {
            $coe = new ChainOfEvents();
            form_objects($chain_of_events_form, $coe);
            $coe->event = $this->event_id;
            $coe->SaveAll();
            $this->coeid = $coe->chain_of_events_record_number;
            $coe->LoadFromRecordNumber($this->coeid);
            $coe->LoadRelationships();

            $res = Browse::getChainOfEvents($this->event->event_record_number);
            $this->res = $res;
            set_redirect_header('events', 'coe_list');
        }
    }

    public function act_delete_coe() {
        if (!isset($_POST['coes']) || isset($_POST['no'])) {
            set_redirect_header('events', 'coe_list');
            return;
        }

        $this->del_confirm = true;
        if (isset($_POST['yes'])) {
            if (isset($_POST['coe']))
                array_push($_POST['coes'], $_POST['coe']);
            //if multiplt events are selected
            if (is_array($_POST['coes'])) {
                foreach ($_POST['coes'] as $coe) {
                    $c = new ChainOfEvents();
                    $c->DeleteFromRecordNumber($coe);
                }
            }
            set_redirect_header('events', 'coe_list');
            return;
        }

        $this->related_events = Browse::getCOEListArray($_POST['coes']);
    }

    /* }}} */

    public function act_doc_list() {
        $sqlStatement = Browse::getEventsDocList($this->event_id, $_GET);
        include_once APPROOT . 'inc/lib_form.inc';

        $entity_type_form_results = array(
            'doc_id' => array('type' => 'text', 'label' => 'Document ID', 'map' => array('entity' => 'supporting_docs_meta', 'field' => 'doc_id')),
            'entity_type' => array('type' => 'text', 'label' => 'Entity', 'map' => array('entity' => 'supporting_docs_meta', 'field' => 'entity_type')),
            'title' => array('type' => 'text', 'label' => 'Document Title', 'map' => array('entity' => 'supporting_docs_meta', 'field' => 'title')),
            'type' => array('type' => 'mt_select', 'label' => 'Type', 'map' => array('entity' => 'supporting_docs_meta', 'field' => 'type', 'mt' => 16)),
            'format' => array('type' => 'text', 'label' => 'Format', 'map' => array('entity' => 'supporting_docs_meta', 'field' => 'format')));

        $field_list = array();
        foreach ($entity_type_form_results as $field_name => $field) {
            // Generates the view's Label list
            $field_list[$field['map']['field']] = $field['label'];
        }

        foreach ($entity_type_form_results as $fieldName => &$field) {
            $field['extra_opts']['help'] = null;
            $field['label'] = null;
            $field['extra_opts']['clari'] = null;
            $field['extra_opts']['value'] = $_GET[$fieldName];
            $field['extra_opts']['required'] = null;
            $field['extra_opts']['class'] = "input-block-level";
        }

        $entity_fields_html = shn_form_get_html_fields($entity_type_form_results);

        $htmlFields = array();
        //iterate through the search fields, checking input values
        foreach ($entity_type_form_results as $field_name => $x) {
            // Generates the view's Label list
            $htmlFields[$field_name] = $entity_fields_html[$field_name];
        }

        $this->result_pager = Browse::getExecuteSql($sqlStatement);
        $this->columnValues = $this->result_pager->get_page_data();
        $this->columnValues = set_links_in_recordset($this->columnValues, 'supporting_docs_meta');
        $recordArray = array();
        foreach ($this->columnValues as $key => $columnValue) {
            foreach ($columnValue as $val => $value) {
                if ($val == 'entity_type') {
                    $recordArray[$key]['entity_record_url'] = get_record_url($columnValue['record_number'], $columnValue['entity_type']);
                    $recordArray[$key][$val] = ucfirst($columnValue['entity_type']);
                } else {
                    $recordArray[$key][$val] = $value;
                }
            }
        }

        $this->columnValues = $recordArray;
        set_huriterms_in_record_array($entity_type_form_results, $this->columnValues);

        //rendering the view
        $this->columnNames = $field_list;
        $this->htmlFields = $htmlFields;
    }

    protected function save_person() {
        $person_form = person_form('new');   // save can be both new or edit.. shoudl be handled
        $person = new Person();
        form_objects($person_form, $person);
        $person->deceased = ($person->deceased == 'on') ? 'y' : 'n';
        if (isset($person->number_of_persons_in_group) && !$person->number_of_persons_in_group) {
            $person->number_of_persons_in_group = Null;
        }
        if (isset($person->dependants) && !$person->dependants) {
            $person->dependants = Null;
        }
        $person->SaveAll();
        $person->SaveAddresses($_POST['person_address']);
        $person->SavePicture();
        return $person;
    }

    protected function edit_person($person_id, $person_form) {
        $person = new Person();
        $person->LoadFromRecordNumber($person_id);
        $person->LoadRelationships();
        $person->LoadManagementData();
        form_objects($person_form, $person);
        $person->deceased = ($person->deceased == 'on') ? 'y' : 'n';
        if (isset($person->number_of_persons_in_group) && !$person->number_of_persons_in_group) {
            $person->number_of_persons_in_group = Null;
        }
        if (isset($person->dependants) && !$person->dependants) {
            $person->dependants = Null;
        }
        $person->SaveAll();
        $person->SavePicture();
        $person->SaveAddresses($_POST['person_address']);

        return $person;
    }

    protected function save_information() {
        $information_form = information_form('new');
        $information = new Information();
        $information->information_record_number = shn_create_uuid('information');
        form_objects($information_form, $information);

        if (trim($_POST['related_person']) == '') {
            $information->related_person = null;
        }

        $information->SaveAll();

        return $information;
    }

    protected function save_intervention() {
        $intervention_form = intervention_form('new');
        $intv = new Intervention();
        $intv->intervention_record_number = shn_create_uuid('intv');
        form_objects($intervention_form, $intv);

        if (trim($intv->victim) == '') {
            $intv->victim = null;
        }
        //var_dump($intv);
        $intv->SaveAll();
        return $intv;
    }

    public function person_information($person_id, $person_form) {
        $person = new Person();
        $person->LoadFromRecordNumber($person_id);
        $person->LoadRelationships();
        $person->LoadAddresses();
        popuate_formArray($person_form, $person);
        $this->person_form = $person_form;
        $this->fields = shn_form_get_html_labels($person_form);
        return $person;
    }

    public function edit_person_information($person_id, $person_form) {
        $person = new Person();
        $person->LoadFromRecordNumber($person_id);
        $person->LoadRelationships();
        $person->LoadAddresses();
        popuate_formArray($person_form, $person);
        $this->person_form = $person_form;
        $this->fields = shn_form_get_html_fields($person_form);
        return $person;
    }

    public function act_audit() {
        $this->pager = Browse::getAuditLogForEvent($this->event->event_record_number);
        $this->logs = $this->pager->get_page_data();
    }

    public function act_print() {
        $this->event->LoadRelationships();
        $this->vp_list = Browse::getVpList($this->event_id);
        $this->sources = Browse::getSourceListforEvent($this->event->event_record_number);
        $this->intv_list = Browse::getIntvList($this->event->event_record_number);
        $this->related_events = Browse::getChainOfEvents($this->event->event_record_number);
        $this->related_events_reverse = Browse::getChainOfEventsReverse($this->event->event_record_number);
    }

    public function findexts($filename) {
        $filename = strtolower($filename);
        $exts = split("[/\\.]", $filename);
        $n = count($exts) - 1;
        $exts = $exts[$n];
        return $exts;
    }

    public function act_geocode() {
        if (isset($_POST['address']) AND !empty($_POST['address'])) {

            $geocode_result = Map::geocode($_POST['address']);
            if ($geocode_result) {
                echo json_encode(array_merge(
                                $geocode_result, array('status' => 'success')
                        ));
            } else {
                echo json_encode(array(
                    'status' => 'error',
                    'message' => 'ERROR!'
                ));
            }
        } else {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'ERROR!'
            ));
        }

        exit(0);
    }

}

