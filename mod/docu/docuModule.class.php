<?php

/**
 * Main Class of document module.
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
 * @author	Mahesh K K S <mahesh@respere.com>
 * @author	J P Fonseka <jo@respere.com>
 * @package	OpenEvsys
 * @subpackage	docu
 *
 */
include_once APPROOT . 'inc/lib_entity_forms.inc';
include_once APPROOT . 'inc/lib_uuid.inc';
include_once APPROOT . 'inc/lib_form_util.inc';
include_once APPROOT . 'inc/lib_files.inc';
require_once APPROOT . 'inc/ArgumentEncoder.php';
include_once 'messages.inc';

class docuModule extends shnModule {

    public $argumentEncoder;

    function section_mod_menu() {
        if ($_GET['act'] == 'new_document')
            $data['active'] = 'new';
        else if ($_GET['act'] == 'browse')
            $data['active'] = 'browse';
        return $data;
    }

    function __construct() {
        global $messages;
        if ($_GET['act'] != 'new_document' && $_GET['act'] != 'browse') {

            $_GET['doc_id'] = (isset($_GET['doc_id'])) ? $_GET['doc_id'] : $_SESSION['doc_id'];
            if (!isset($_GET['doc_id'])) {
                shnMessageQueue::addInformation($messages['select_docu']);
                set_redirect_header('docu', 'browse');
                exit();
            }
            global $supporting_docs_meta;
            $this->supporting_docs = new SupportingDocs();
            $this->supporting_docs->LoadfromRecordNumber($_GET['doc_id']);
            $supporting_docs_meta = new SupportingDocsMeta();
            $supporting_docs_meta->LoadfromRecordNumber($_GET['doc_id']);
            $supporting_docs_meta->LoadRelationships();
            $this->supporting_docs_meta = $supporting_docs_meta;
            //if event does not exists
            if ($this->supporting_docs_meta->doc_id != $_GET['doc_id'] || $this->supporting_docs_meta->doc_id == '') {
                //shnMessageQueue::addError($messages['docu_not_found']);
                set_redirect_header('docu', 'browse');
                exit();
            }
            $_SESSION['doc_id'] = $_GET['doc_id'];
            $_SESSION['type'] = $this->supporting_docs_meta->format;
            set_url_args('doc_id', $_SESSION['doc_id']);
        }

        $this->createArgumentEncoder();
    }

    public function createArgumentEncoder() {
        $whiteList = Array(
            'request_page', 'rpp', 'doc_id', 'title',
            'datecreated', 'datesubmitted', 'type', 'format',
            'filter', 'sort', 'sortorder'
        );

        $this->argumentEncoder = new ArgumentEncoder($whiteList);
    }

    public function act_browse() {
        global $conf;

        include_once APPROOT . 'inc/lib_form.inc';

        require_once(APPROOT . 'mod/analysis/analysisModule.class.php');
        $analysisModule = new analysisModule();
        $sqlStatement = $analysisModule->generateSqlforEntity('supporting_docs_meta', null, $_GET, 'browse');

        $entity_type_form_results = generate_formarray('supporting_docs_meta', 'browse');

        $entity_type_form_results['doc_id']['type'] = 'text';
        if (isset($entity_type_form_results['format'])) {
            $entity_type_form_results['format']['type'] = 'text';
        }

        $field_list = array();
        foreach ($entity_type_form_results as $field_name => $field) {
            // Generates the view's Label list
            $field_list[$field['map']['field']] = $field['label'];
        }
        if (is_array($conf['browsefields']['supporting_docs_meta']) && in_array("eventslinks", $conf['browsefields']['supporting_docs_meta'])) {
            $field_list['eventslinks'] = _t('LINKS');
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
        $this->result_pager->setArgumentEncoder($this->argumentEncoder);

        $this->columnValues = $this->result_pager->get_page_data();

        $this->columnValues = set_links_in_recordset($this->columnValues, 'supporting_docs_meta');
        set_huriterms_in_record_array($entity_type_form_results, $this->columnValues);
        foreach ($this->columnValues as $k => $v) {
            $linksa = array();
            $links = Browse::getDocumentLinks($v['doc_id'], 'event');
            foreach ($links as $record) {
                $linksa[] = "<a href=\"" . get_record_url($record['record_number'], "event") . "\" >" . $record['record_number'] . "</a>";
            }
            $this->columnValues[$k]['eventslinks'] = implode("<br/>", $linksa);
        }
        //rendering the view
        $this->columnNames = $field_list;
        $this->htmlFields = $htmlFields;
        //var_dump($this->columnNames , $this->columnValues , $htmlFields	);
    }

    public function act_new_document() {
        global $conf;
        $document_form = document_form('new');
        $this->document_form = $document_form;
        if (isset($_POST['save'])) {
            $status = shn_form_validate($this->document_form);
            if (!$status)
                return;

            unset($document_form['doc_id']);
            $supporting_docs = new SupportingDocs();
            $supporting_docs_meta = new SupportingDocsMeta();
            $type = null;
            $uri = shn_files_store('choose_file_upload', null, $type); //"http://test";

            if ($uri == null) {
                $uri = '';
            }

            $doc_uuid = shn_create_uuid('doc');
            $supporting_docs->doc_id = $doc_uuid;
            $supporting_docs_meta->doc_id = $doc_uuid;
            $supporting_docs->uri = $uri;
            form_objects($document_form, $supporting_docs);
            form_objects($document_form, $supporting_docs_meta);

            $supporting_docs_meta->format = $type;
            $supporting_docs->Save();
            $supporting_docs_meta->SaveAll();

            $this->supporting_docs = $supporting_docs;
            $this->supporting_docs_meta = $supporting_docs_meta;
            set_url_args('doc_id', $this->supporting_docs_meta->doc_id);
            change_tpl('add_document_finish');
            set_redirect_header('docu', 'view_document');
            exit();
        }
    }

    public function act_view_document() {
        $this->supporting_docs_meta = new SupportingDocsMeta();
        $this->supporting_docs_meta->LoadfromRecordNumber($_GET['doc_id']);
        $this->supporting_docs_meta->LoadRelationships();

        $this->supporting_docs = new SupportingDocs();
        $this->supporting_docs->LoadfromRecordNumber($_GET['doc_id']);

        if (file_exists($this->supporting_docs->uri)) {
            $this->supporting_docs_meta->file_size = filesize($this->supporting_docs->uri);
        }
    }

    /**
     * act_edit_document Action to edit document details
     *
     * @access public
     * @return void
     */
    public function act_edit_document() {
        $this->document_form = document_form('edit');

        if (isset($_POST['update']) || isset($_POST['yes']) || isset($_POST['no'])) {
            $status = shn_form_validate($this->document_form);

            if (!$status)
                return;

            $this->fileExist = false;
            $type = null;
            $uri = shn_files_store('choose_file_upload', null, $type); //"http://test";

            if ($uri == null) {
                $uri = '';
            }

            if (isset($_POST['yes'])) {
                $this->supporting_docs->uri = $_SESSION['uri'];
                $this->supporting_docs->Save();
                shnMessageQueue::addInformation(_t('THE_OLD_FILE_ATTACHMENT_WAS_UPDATED_WITH_THE_NEW_FILE_ATTACHMENT_'));
            } else if (isset($_POST['no'])) {

            } else if ($this->supporting_docs->uri != null && $uri != '') {
                $this->fileExist = true;
                $_SESSION['uri'] = $uri;
                $_SESSION['type'] = $type;
                return;
            } else if ($this->supporting_docs->uri != null && $uri == '') {

            } else {
                $this->supporting_docs->uri = $uri;
                $this->supporting_docs->Save();
            }

            if ($_SESSION['type'] != null) {
                $type = $_SESSION['type'];
            }

            form_objects($this->document_form, $this->supporting_docs_meta);
            $this->supporting_docs_meta->format = $type;
            $this->supporting_docs_meta->SaveAll();
            unset($_SESSION['type']);
            set_redirect_header('docu', 'view_document', null);
            exit();
        }
    }

    public function act_delete_document() {
        if (isset($_POST['cancel'])) {
            set_redirect_header('docu', 'view_document', null);
        }
        if (isset($_POST['delete'])) {
            $this->supporting_docs_meta->DeleteFromRecordNumber($this->supporting_docs_meta->doc_id);

            unlink(APPROOT . 'media/' . basename($this->supporting_docs->uri));

            $this->supporting_docs->Delete();

            global $global;
            unset($global['url_args']);
            set_redirect_header('docu', 'browse', null, null);
            return;
        }
    }

    /**
     * act_link
     *
     * @access public
     * @return void
     */
    public function act_link() {
        $entities = array('event' => _t('EVENT'), 'person' => _t('PERSON'), 'act' => _t('ACT'), 'involvement' => _t('INVOLVEMENT'), 'information' => _t('INFORMATION'), 'intervention' => _t('INTERVENTION'));
        foreach ($entities as $entity_id => $entity) {
            $links[$entity_id] = Browse::getDocumentLinks($this->supporting_docs->doc_id, $entity_id);
        }
        $this->entities = $entities;
        $this->links = $links;
    }

    /**
     * act_audit
     *
     * @access public
     * @return void
     */
    public function act_audit() {
        $this->res = $supporting_docs_meta;

        if (isset($_GET['doc_id'])) {
            $supporting_docs_meta = new SupportingDocsMeta();
            $supporting_docs_meta->LoadfromRecordNumber($_GET['doc_id']);
            $supporting_docs_meta->LoadRelationships();
            $this->supporting_docs_meta = $supporting_docs_meta;

            $logs = Browse::getAuditLogForDocument($_GET['doc_id']);

            $this->logs = $logs;
        }
    }

    public function act_download() {
        //get file id
        //load document detaild
        $supporting_docs_meta = new SupportingDocsMeta();
        $supporting_docs_meta->LoadfromRecordNumber($_GET['doc_id']);
        $supporting_docs_meta->LoadRelationships();

        $supporting_docs = new SupportingDocs();
        $supporting_docs->LoadfromRecordNumber($_GET['doc_id']);
        //set headers

        if ($supporting_docs->uri != null) {
            $supporting_docs->uri = APPROOT . 'media/' . basename($supporting_docs->uri);

            $ext = shn_file_findexts($supporting_docs->uri);
            //fetch document
            //stream document
            $title = $supporting_docs_meta->title;
            $file_name = str_replace(" ", "_", $title);
            header("Content-Type: application/$ext");
            header("Content-Disposition: filename=" . urlencode("$file_name.$ext"));
            header("Content-Length: " . filesize($supporting_docs->uri));
            $fp = fopen($supporting_docs->uri, 'rb');
            fpassthru($fp);
            //inthis case we dont need to go to the templates so exit from the script
        } else {
            shnMessageQueue::addInformation('No attachment found to this document.');
            set_redirect_header('docu', 'view_document', null, null);
        }
        exit();
    }

}
