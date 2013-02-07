<?php

/**
 * Class for search,add new document and link the document(s) to event or person.
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
 * @auther  Kethees S <ks@respere.com>
 * @package Framework
 * 
 */
class DocumentSearch {

    public function render() {
        if (isset($_POST['new_doc'])) {
            $this->showDocumentAddForm();
        } else {
            $this->showSearchForm();
            $this->searchResult();
        }
    }

    protected function showDocumentAddForm() {
        include_once APPROOT . 'inc/lib_form_util.inc';

        echo "<h4>" . _t('ADD_SUPPORTING_DOCUMENT_S_') . "</h4>";

        $document_form = document_form('new');
        $fields = shn_form_get_html_fields($document_form);
        $fields = place_form_elements($document_form, $fields);
        ?>
        <div class="control-group">
            <div class="controls"> 
                <button type="submit" class="btn" name="save_doc"  onclick="add_anchor(this.form,'document_field');" ><i class="icon-ok"></i> <?php echo _t('SAVE_DOCUMENT') ?></button>	
                <a class="btn" href="#document_field" id='close_doc_add_form'><i class="icon-remove"></i> <?php echo _t('CLOSE'); ?></a>
            </div></div>
        <?php
    }

    protected function showSearchForm() {
        include_once APPROOT . 'inc/lib_form_util.inc';
        include_once APPROOT . 'inc/lib_form.inc';

        $document_form = document_form('search');
        formArrayRefine($document_form);

        foreach ($person_form as $key => &$element) {
            if ($_GET[$key] != null) {
                $element['extra_opts']['value'] = $_GET[$key];
            }
        }

        $fields = shn_form_get_html_fields($document_form);
        $fields = place_form_elements($document_form, $fields);
        ?>
        <div class="control-group">
            <div class="controls"> 
                   <a class="btn" href="#document_field" id='close_doc_search_form'><i class="icon-remove"></i> <?php echo _t('CLOSE'); ?></a>
                <button type="submit" class="btn" name="search"  onclick="add_anchor(this.form,'document_field');" ><i class="icon-search"></i> <?php echo _t('SEARCH') ?></button>	
               <button type="submit" class="btn btn-primary" name="new_doc"  onclick="add_anchor(this.form,'document_field');" ><i class="icon-plus icon-white"></i> <?php echo _t('NEW') ?></button>
          </div></div>
        <?php
    }

    protected function searchResult() {
        include_once APPROOT . 'inc/lib_form.inc';

        require_once(APPROOT . 'mod/analysis/analysisModule.class.php');
        $analysisModule = new analysisModule();
        $sqlStatement = $analysisModule->generateSqlforEntity('supporting_docs_meta', null, $_POST, 'search');
        $entity_type_form_results = generate_formarray('supporting_docs_meta', 'browse');
        $entity_type_form_results['doc_id']['type'] = 'text';
        $field_list = array();
        foreach ($entity_type_form_results as $field_name => $field) {
            $field_list[$field['map']['field']] = $field['label'];
        }

        foreach ($entity_type_form_results as $fieldName => &$field) {
            $field['extra_opts']['help'] = null;
            $field['label'] = null;
            $field['extra_opts']['clari'] = null;
            $field['extra_opts']['value'] = $_GET[$fieldName];
            $field['extra_opts']['required'] = null;
        }

        $entity_fields_html = shn_form_get_html_fields($entity_type_form_results);

        $htmlFields = array();
        //iterate through the search fields, checking input values
        foreach ($entity_type_form_results as $field_name => $x) {
            // Generates the view's Label list
            $htmlFields[$field_name] = $entity_fields_html[$field_name];
        }
        $result_pager = Browse::getExecuteSql($sqlStatement);
        $columnValues = $result_pager->get_page_data();
        $columnValues = set_links_in_recordset($columnValues, 'supporting_docs_meta');

        set_huriterms_in_record_array($entity_type_form_results, $columnValues);

        //rendering the view
        $columnNames = $field_list;
        $this->htmlFields = $htmlFields;

        if ($columnValues != null && count($columnValues)) {
            $result_pager->render_post_pages();
            shn_form_get_html_doc_search_ctrl($columnNames, $columnValues, $htmlFields);
            $result_pager->render_post_pages();
        } else {
            shnMessageQueue::addInformation(_t('NO_RECORDS_WERE_FOUND_'));
            echo shnMessageQueue::renderMessages();
        }
    }

}
?>
