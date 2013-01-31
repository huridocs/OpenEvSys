<?php
 /**	
 * Class for search existing events and return selected event_record_number.
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

class EventSearch {
        
    public function render($data){
		$this->showForm($data);
        $this->searchResult($data);		
    }

    protected function showForm($data){

        include_once APPROOT.'inc/lib_form_util.inc';
        include_once APPROOT.'inc/lib_form.inc';
		
   		$event_form = event_form('search');
   				
		formArrayRefine($event_form);

    	foreach($event_form as $key=>&$element) {    	
			if($_GET[$key] != null){
				$element['extra_opts']['value'] = $_GET[$key];
			}
		}
		
		$fields = shn_form_get_html_fields($event_form);	
		$fields = place_form_elements($event_form,$fields);
		
		shn_form_submit(_t('SEARCH'),'search');
		?>
		<a class="btn" href="#" id="related_event_search_close"><i class="icon-remove"></i> <?php echo _t('CLOSE')?></a>
		<?php
		echo "<br/><br />";	
    }

	protected function searchResult($data){	
	require_once(APPROOT.'mod/analysis/analysisModule.class.php');
    	
    	$analysisModule = new analysisModule();
    	$dataArray = array();    	
    	$_REQUEST['search_type'] = "event";
        
    	//var_dump($_REQUEST);
		foreach($_REQUEST as $key=>$element) {
			if($_REQUEST[$key] != null){
    			$_GET[$key] = $_REQUEST[$key];
			}
    	}

    	$sqlStatement = $analysisModule->generateSqlforEntity('event',null,$_REQUEST, 'search');
    	    	
 		$entity_type_form_results = generate_formarray('event' ,'search_view');	
 		$entity_type_form_results['event_record_number']['type'] = 'text';
 		$field_list = array();
		foreach($entity_type_form_results as $field_name => $field){
			$field_list[  $field['map']['field']  ] = $field[ 'label' ];
		}
 		
		foreach($entity_type_form_results as $fieldName => &$field){
			$field['extra_opts']['help'] = null;
			$field['label'] = null;
			$field['extra_opts']['clari'] = null;
			$field['extra_opts']['value'] = $_GET[$fieldName];	
		    $field['extra_opts']['required'] = null;
		}

		$entity_fields_html = shn_form_get_html_fields($entity_type_form_results);		
		
		$htmlFields = array();
		//iterate through the search fields, checking input values
		foreach($entity_type_form_results as $field_name => $x){   
			// Generates the view's Label list
			$htmlFields[$field_name] = $entity_fields_html[$field_name]	;		
		}
		$result_pager = Browse::getExecuteSql($sqlStatement);			
        $columnValues = $result_pager->get_page_data();
        $columnValues = set_links_in_recordset( $columnValues , 'event' );

        set_huriterms_in_record_array( $entity_type_form_results , $columnValues );
        
		//rendering the view
		$columnNames = $field_list;	
    	$this->htmlFields = $htmlFields;

	    if($columnValues != null && count($columnValues) ){
			$result_pager->render_pages();		
			shn_form_get_html_event_search_ctrl($columnNames, $columnValues, $htmlFields,$_GET['mod'], $_GET['act']);	
			$result_pager->render_pages();		
		}
		else{
			shnMessageQueue::addInformation(_t('NO_RECORDS_WERE_FOUND_'));
	        echo shnMessageQueue::renderMessages();
		}
	}
}
?>
