<?php
/**
 * Person search class used by the main controlers
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
 * @author	Kethees S <ks@respere.com>
 * @package	OpenEvsys
 * @subpackage	person
 *
 */

class RelatedPersonSearch {	
    public $mod;
    public $act;    
    public $cancel;	
	public $view;
    
    public function render($data){		
        if(isset($_POST['search'])){	
			$this->mod = $_POST['mod'];
			$this->act = $_POST['act'];
			$this->view = $_POST['view'];
			$this->cancel = $_POST['cancel'];			
        }
		else{
			$this->mod = $data['mod'];
			$this->act = $data['act'];
			$this->view = $data['view'];			
			$this->cancel = $data['cancel'];            
        }
		$this->showForm();
        $this->searchResult();
    }

    protected function showForm(){

        include_once APPROOT.'inc/lib_form_util.inc';
        include_once APPROOT.'inc/lib_uuid.inc';	
        
        $person_form = person_form('search');
		$address_form = address_form('search');
		
		formArrayRefine($person_form);		

    	foreach($person_form as $key=>&$element) {
			if($_GET[$key] != null){
				$element['extra_opts']['value'] = $_GET[$key];
			}
		}
		
		$fields = shn_form_get_html_fields($person_form);
					
		if($fields['person_addresses'] != null){			
			formArrayRefine($address_form);			
			$address_fields = shn_form_get_html_fields($address_form);																						
		}
		
		if($fields['person_addresses'] != null){
			$fields['person_addresses'] = null;						
			$fields = place_form_elements($person_form,$fields);
			$address_fields = place_form_elements($address_form,$address_fields);																
		}
		else{
			$fields = place_form_elements($person_form,$fields);
		}
		         
    ?>       
	<?php
		echo ($this->mod != null) ? "<input type='hidden' name='mod' value='{$this->mod}'/>" : '';
		echo ($this->act != null) ? "<input type='hidden' name='act' value='{$this->act}'/>" : '';
		echo ($this->view != null) ? "<input type='hidden' name='view' value='{$this->view}'/>" : '';
		echo ($this->cancel != null) ? "<input type='hidden' name='cancel' value='{$this->cancel}'/>" : '';
		
	?>			
       <a class="but" href="<?php echo get_url($this->mod,$this->act,null,null) ?>"><?php echo _t('PREVIOUS')?></a>
       <a class="but" href="<?php echo get_url($this->mod,$this->cancel,null,null) ?>"><?php echo _t('CANCEL')?></a>
       <?php echo $fields['search'];?>
       <br /><br />
    <?php     
    }

	protected function searchResult(){	
	require_once(APPROOT.'mod/analysis/analysisModule.class.php');
    	
    	$analysisModule = new analysisModule();
    	$dataArray = array();
        //assign post search queries to get
		
    	foreach($_REQUEST as $key=>$element) {
    		if($_REQUEST[$key] != null){
    			$_GET[$key] = $_REQUEST[$key];
    		}
    	}
	
    	$sqlStatement = $analysisModule->generateSqlforEntity('person',null,$_REQUEST, 'search');
 		$entity_type_form_results = generate_formarray('person' ,'search_view');	
 		$entity_type_form_results['person_record_number']['type'] = 'text';
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
        $columnValues = set_links_in_recordset( $columnValues , 'person' );

        set_huriterms_in_record_array( $entity_type_form_results , $columnValues );
        
		//rendering the view
		$columnNames = $field_list;	
    	$this->htmlFields = $htmlFields;

	    if($columnValues != null && count($columnValues) ){
			$result_pager->render_pages();		
			shn_form_get_html_person_search_ctrl($columnNames, $columnValues, $htmlFields,$_GET['mod'], $_GET['act']);	
			$result_pager->render_pages();		
		}
		else{
			shnMessageQueue::addInformation(_t('NO_RECORDS_WERE_FOUND_'));
	        echo shnMessageQueue::renderMessages();
		}
	}
        
    protected function search_person()
    {
        include_once APPROOT.'inc/lib_uuid.inc';
        include_once APPROOT.'inc/lib_form_util.inc';
        $person_form = person_form('new');
        $person = new Person();
        $person = $person->getAll();
        return $person;		
    }
}
?>
