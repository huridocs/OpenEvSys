<?php
include_once APPROOT.'inc/lib_entity_forms.inc';
include_once 'lib_analysis.inc';

/**
 * @author ks
 *
 */
class analysisModule extends shnModule
{
	
    function section_mod_menu()
    {
        $data['breadcrumbs'] = shnBreadcrumbs::getBreadcrumbs();
        if($_GET['act']=='search_query')
            $data['active'] = 'query';
        else if($_GET['act']=='adv_search')
            $data['active'] = 'adv_search';
        else if($_GET['act']=='adv_report')
            $data['active'] = 'adv_search';
        return $data;
    }
	
	function __construct()
	{	
		$this->main_entity = (isset($_POST['main_entity'])) ? $_POST['main_entity'] : $_GET['main_entity'];
		
		$this->search_entity = (isset($_GET['shuffle_results']) && $_GET['shuffle_results'] != 'all') ? $_GET['shuffle_results'] : $this->main_entity;			
	}


	public function _act_search()
	{
		$this->search_entities = analysis_get_search_entities();
	}

	/**
	*
	* creates shuffle actions relavent to the entity type
	*
	* @access public
	*
	*/

	public function shuffle_actions($main_entity)
	{
		$options = analysis_get_relationship_array();
		
		foreach($options as $entity=>$option){
			if($main_entity == $entity){
				foreach($options[$entity] as $key=>$value){
					$this->shuffle_options[$key] =$value['label'];
				}
			}			
		}
	}

	
	public function address_search_form($fields)
	{
		$address_index = null;
		foreach($fields as $key=>$field){
			if( $field['map']['field']=='person_addresses' ){
				$address_index = $key;
				break;
			}
		}
		$this->address_index = $address_index;
		
		if($fields[$address_index] != null){						
			$this->address_form = address_form('search');
			formArrayRefine($this->address_form);				
			$this->address_form['person_addresses'] =  array('type'=>'hidden', 'extra_opts' => array('value'=>'enabled' )  );	//added to be used when creating sql to inform that there is address in search	
			$this->address_fields = shn_form_get_html_fields($this->address_form);																						
		}		
	}

	public function entity_search_form($entity_type)	
	{
		include_once APPROOT.'inc/lib_form_util.inc';		
		
		if($entity_type != null){			
			switch($entity_type){
				case 'event':
					$this->search_header =_t("EVENT_SEARCH_FORM");
					$this->search_form = event_form('search');		
					formArrayRefine($this->search_form);			 
					$this->fields = shn_form_get_html_fields($this->search_form);									
					break;
				case 'person':
					$this->search_header = _t("PERSON_SEARCH_FORM");
					$this->search_form = person_form('search');
					$this->address_search_form($this->search_form);
					formArrayRefine($this->search_form);									
					$this->fields = shn_form_get_html_fields($this->search_form);
					
					break;
				case 'victim':
					$this->search_header = _t("VICTIM_SEARCH_FORM");					
					$this->search_form = victim_form('search');
                    $this->address_search_form($this->search_form);
                    formArrayRefine($this->search_form);                                    
                    $this->fields = shn_form_get_html_fields($this->search_form);
						
					break;
				case 'perpetrator':
					$this->search_header = _t("PERPETRATOR_SEARCH_FORM");
					$this->search_form = perpetrator_form('search');
                    $this->address_search_form($this->search_form);
                    formArrayRefine($this->search_form);                                    
                    $this->fields = shn_form_get_html_fields($this->search_form);		
					break;
				case 'source':
					$this->search_header = _t("SOURCE_SEARCH_FORM");
					$this->search_form = source_form('search');
                    $this->address_search_form($this->search_form);
                    formArrayRefine($this->search_form);                                    
                    $this->fields = shn_form_get_html_fields($this->search_form);	
					break;
				case 'intervening_party':
					$this->search_header = _t("INTERVENING_PARTY_SEARCH_FORM");
					$this->search_form = intervening_party_form('search');
                    $this->address_search_form($this->search_form);
                    formArrayRefine($this->search_form);                                    
                    $this->fields = shn_form_get_html_fields($this->search_form);	
					break;
				case 'information':
					$this->search_header = _t("INFORMATION_SEARCH_FORM");
					$this->search_form = information_form('search');
					formArrayRefine($this->search_form);					
					$this->fields = shn_form_get_html_fields($this->search_form);				
					break;
				case 'intervention':
					$this->search_header = _t("INTERVENTION_SEARCH_FORM");
					$this->search_form = intervention_form('search');
					formArrayRefine($this->search_form);					
					$this->fields = shn_form_get_html_fields($this->search_form);				
					break;	
				case 'act':
					$this->search_header = _t("ACT_SEARCH_FORM");
					$this->search_form = act_form('search');	
					formArrayRefine($this->search_form);				
					$this->fields = shn_form_get_html_fields($this->search_form);				
					break;
				case 'involvement':
					$this->search_header = _t("INVOLVEMENT_SEARCH_FORM");
					$this->search_form = involvement_form('search');
					formArrayRefine($this->search_form);					
					$this->fields = shn_form_get_html_fields($this->search_form);				
					break;			
	    		case 'supporting_docs_meta':
					$this->search_header = _t("DOCUMENT_SEARCH_FORM");
					$this->search_form = document_form('search');
					formArrayRefine($this->search_form);					
					$this->fields = shn_form_get_html_fields($this->search_form);				
					break;											
			}
		}
	}	


	public function _act_search_form() // removed
	{		
		$this->entity_search_form($this->main_entity);
	}

	
/*{{{ Search Result */
	public function _act_search_result() // removed
	{
		include_once APPROOT.'inc/lib_form_util.inc';		
		$_POST = $_GET; //need to remove this hack some how
		//shuffle actions for search result
		$this->shuffle_actions($this->main_entity);

		//actions for search result		
		$this->actions = analysis_get_actions();

		//display second level search form
		$this->entity_search_form($this->main_entity);
    
        if(isset($_GET['query_save'])){
            $this->act_save_query();
        }

		
		if(isset($_GET['shuffle_results'])){				
			if($_GET['shuffle_results'] != 'all'){					
				//$shufSql = $this->getShuffelSql($this->main_entity, $this->search_entity);
				$sqlStatement = $this->generateSqlforEntity($this->main_entity,true);	
				//echo "SHUFFLE STATEMENT : $sqlStatement<br>";
			}
			else{
				$sqlStatement = $this->generateSqlforEntity($this->main_entity);		
			}						
		}else{
			$sqlStatement = $this->generateSqlforEntity($this->search_entity);
		}

        if(isset($_GET['action'])){				
	        switch($_GET['actions'])
    		{
            	case 'csv':
                  	$this->act_csv();                        
                    break;
                case 'report':
                   	$this->act_report();
                   	change_tpl('act_report');                       
                    break;
				case 'export_spreadsheet':
					$this->act_export_spreadsheet();						
                    break;
				case 'save_org_sql':
                    
				case 'save_shuffle_sql':
                    $this->save_query = true ;
                    break;
// 				case 'view_record_seq':
//                  set_redirect_header('analysis','view_record_seq',null,null,'text');
//              	break;
// 				case 'view_record_map':
//                  set_redirect_header('analysis','view_record_map',null,null,'text');
//                  break;
        	}
        }

		if($where != null){
			 $sqlStatement .= " WHERE $where";			
		}		
			
		$entity_type_form_results = generate_formarray($this->search_entity ,'search_view');

				
		$field_list = array();
			//iterate through the search fields, checking input values
		foreach($entity_type_form_results as $field_name => $field){
				// Generates the view's Label list
			$field_list[ $field['map']['field']  ] = $field[ 'label' ];				
		}

//echo ($sqlStatement);
		$this->result_pager = Browse::getExecuteSql($sqlStatement);			
        $this->columnValues = $this->result_pager->get_page_data();
            
        $displayEntity = $this->main_entity;
        if($_POST['shuffle_results'] != 'all' && $_POST['shuffle_results'] != null ){
            $displayEntity = $_POST['shuffle_results'];
        }
        else{
            $displayEntity = $this->main_entity;
        }
        
        $displayEntity = get_table_for_entity($displayEntity);
        $this->columnValues = set_links_in_recordset( $this->columnValues , $displayEntity );
        
        set_huriterms_in_record_array( $entity_type_form_results , $this->columnValues );
        if(isset($_POST['person_addresses'])){
        	mergeAddressFields($this->columnValues);
        }
        		
		//rendering the view
		$this->columnNames = $field_list;
		
		//var_dump('values' , $this->columnValues);
        //var_dump('names' , $this->columnNames); 
	
	}/*}}}*/

/*{{{ Query Functions*/

	public function act_save_query()
	{		
		include_once APPROOT.'inc/lib_uuid.inc';
	    if($_GET['actions']=='save_org_sql')
            unset($_GET['shuffle_results']);	
		if(isset($_GET['query_save'])){
			$saveQuery = new SaveQuery();                
			$saveQuery->save_query_record_number = shn_create_uuid('query');	
			$saveQuery->name = $_GET['query_name'];
			$saveQuery->description = $_GET['query_desc'];
			$saveQuery->created_date = date("Y-m-d");
			$saveQuery->created_by = $_SESSION['username'];
            $query = (isset($_GET['query']))?$_GET['query']:analysis_get_query();
            $query_type = (isset($_GET['query']))?'advanced':'basic';
			$saveQuery->query = $query; 
			$saveQuery->query_type = $query_type; 
			$saveQuery->Save();
            if($_GET['stream']=='text') 
                echo "{'success':true}";
            else
                shnMessageQueue::addInformation(_t('QUERY_WAS_SAVED_SUCCESSFULLY_'));
		}
	}

    public function act_search_query()
    {
        if(isset($_GET['delete']) && is_array($_GET['sq'])){
            $this->delete = true;
            unset($_GET['delete']);
        }

		include_once APPROOT.'inc/lib_form.inc';
		$this->query_result_pager = Browse::getSaveQueryList($_GET);
	    $this->query_list = $this->query_result_pager->get_page_data();
    }

    public function act_delete_query()
    {
        if(isset($_POST['delete_yes'])&&is_array($_POST['sq'])){
    		$saveQuery = new SaveQuery();
            foreach($_POST['sq'] as $query){
                $saveQuery->DeleteFromRecordNumber($query);
            }
            shnMessageQueue::addInformation(_t('QUERIES_DELETED_SUCCESSFULLY_'));
        }
        set_redirect_header('analysis','search_query');
    }

/*}}}*/



/*{{{ Actions */

    //hack for csv export
    public function act_csv()
    {      
        include_once APPROOT.'inc/lib_form_util.inc';
		
		global $global;
		
    	if(isset($_GET['shuffle_results'])){				
			if($_GET['shuffle_results'] != 'all'){
				$sqlStatement = $this->generateSqlforEntity($this->main_entity,true);
			}
			else{
				$sqlStatement = $this->generateSqlforEntity($this->main_entity);		
			}						
		}else{
			$sqlStatement = $this->generateSqlforEntity($this->search_entity);
		}
        define('ADODB_FETCH_BOTH',3); 
	    $global['db']->SetFetchMode(ADODB_FETCH_BOTH);
        $res = $global['db']->Execute($sqlStatement);        

		$entity_type_form_results = generate_formarray($this->search_entity ,'search_view');
		
		$displayEntity = get_table_for_entity($this->search_entity);
        $res = set_links_in_recordset( $res , $displayEntity );
		set_huriterms_in_record_array( $entity_type_form_results , $res);
		
    	$field_list = array();
		
		foreach($entity_type_form_results as $field_name => $field){
			$field_list[  $field['map']['field']  ] = $field[ 'label' ];
		}

        $filename = 'openevsys_results_'.date('Ymd-His').'.csv';
        header("Pragma: public"); 
        header("Content-Type: application");
	    header("Content-Disposition: attachment; filename=$filename;");
        
       
	    if($res){
	    	foreach($res as $record){
				foreach($record as $key=>$value){
					if($field_list[$key] != null){
						echo '"'.str_replace('"', '""', $value).'"' . ',';												
					}									
				}
				echo "\n";
			}
	    }
		exit();
	}

	public function act_report()
	{
		include_once APPROOT.'inc/lib_form_util.inc';

		global $global;

		$sqlStatement = $this->generateSqlforEntity($this->main_entity,true);
        $res = $global['db']->Execute($sqlStatement);

		$entity_type_form_results = generate_formarray($this->search_entity ,'search_view');
		
		$displayEntity = get_table_for_entity($this->search_entity);
        $res = set_links_in_recordset( $res , $displayEntity );
		set_huriterms_in_record_array( $entity_type_form_results , $res);
		
		$field_list = array();
		
		foreach($entity_type_form_results as $field_name => $field){
			$field_list[  $field['map']['field']  ] = $field[ 'label' ];
		}		
		
		$this->rpt_columnValues = $res;		
		$this->columnNames = $field_list;	
	}

	public function act_export_spreadsheet()
	{
		include_once APPROOT.'inc/lib_form_util.inc';

		global $global;
		
		if(isset($_GET['shuffle_results'])){				
			if($_GET['shuffle_results'] != 'all'){
				$sqlStatement = $this->generateSqlforEntity($this->main_entity,true);
			}
			else{
				$sqlStatement = $this->generateSqlforEntity($this->main_entity);		
			}						
		}else{
			$sqlStatement = $this->generateSqlforEntity($this->search_entity);
		}
		
        define('ADODB_FETCH_BOTH',3); 
	    $global['db']->SetFetchMode(ADODB_FETCH_BOTH);
        $res = $global['db']->Execute($sqlStatement);

		$entity_type_form_results = generate_formarray($this->search_entity ,'search_view');
		
		$displayEntity = get_table_for_entity($this->search_entity);
        $res = set_links_in_recordset( $res , $displayEntity );
		set_huriterms_in_record_array( $entity_type_form_results , $res);

		$field_list = array();
		
		foreach($entity_type_form_results as $field_name => $field){
			$field_list[  $field['map']['field']  ] = $field[ 'label' ];
		}

        $filename = 'openevsys_results_'.date('Ymd-His').'.xls';
		header("Pragma: public"); 
        header("Content-Type: application/x-msexcel");
        header("Content-Disposition: attachment; filename=$filename;");

		if($res){
			$count = 1;
            foreach($res as $record){
				if($count ==1 ){
					foreach($record as $key=>$value){
						if($field_list[$key] != null){
							echo '"'.str_replace('"', '""', $field_list[$key]).'"';
							echo "\t";
						}					
					}
					$count ++;
					echo "\n";
				}
				
				foreach($record as $key=>$value){
					if($field_list[$key] != null){
						$value = str_replace('<ul><li>','',$value);
						$value = str_replace('</li><li>',',',$value);
						$value = str_replace('</li></ul>','',$value);
						echo '"'.str_replace('"', '""', $value).'"';
						echo "\t";
					}					
				}
				echo "\n";
			}
        }
		
        exit();
        
	}
	
	public function act_adv_csv()
	{		
		include_once 'searchSql.php';
		
		if(isset($_GET['query'])){
			$searchSql = new SearchResultGenerator();
	    	$sqlArray = $searchSql->sqlForJsonQuery($_GET['query']);
	    	
	    	global $global;
	    	$db = $global['db'];
	    	$db->SetFetchMode(ADODB_FETCH_ASSOC);
        	$recordset = $db->GetAll($sqlArray['result']);

			$query = json_decode($_GET['query']);
	    	$fields_array = array();        

	    	if($query->select !=NULL){
	    		foreach($query->select as $field){
	                $mt = is_mt_field($field->entity, $field->field);	                
	                if($mt){
	                	$fields_array[] = $field->field;
	                }    		    
	            }
	    	}

        	$filename = 'openevsys_adv_search_results_'.date('Ymd-His').'.csv';
        	header("Pragma: public"); 
        	header("Content-Type: application");
	    	header("Content-Disposition: attachment; filename=$filename;");
	    	
	    	$count = 1;
        	foreach($recordset as $records){        		
        		foreach($records as $key=>$record){        			
        			if($count == 1){
        				$key = strstr($key,"_");      				
        				$key = substr($key, 1);
        				echo '"'. ucwords(str_replace('_', ' ', $key)) .'"' . ',';
        			}        			        			
        		}
        		$count++;
        	}
        	echo "\n";
	    	
			foreach($recordset as $records){	    		
	    		foreach($records as $key=>$record){
	    			$key = strstr($key,"_");      				
        			$key = substr($key, 1);	    				    	
	    			
	    			if(in_array($key, $fields_array)){	    				
	    				echo '"'. get_mt_term(trim( $record)) .'"' . ',';
	    			}
	    			else if($key == 'confidentiality'){
	    				if($record == 'y'){
	    					echo '"'. _t('YES') .'"' . ',';
	    				}
	    				else{
	    					echo '"'. _t('NO') .'"' . ',';
	    				}
	    			}
	    			else if($key == 'deceased'){
	    				if($record == 'y'){
	    					echo '"'. _t('YES') .'"' . ',';
	    				}
	    				else{	    					
	    					echo '"'. _t('NO') .'"' . ',';
	    				}
	    			}
	    			else{
	    				echo '"'. $record .'"' . ',';
	    			}
	    		}
	    		echo "\n";
	    	}	    	
			exit();
		}
	}
	
	public function act_adv_export_spreadsheet()
	{		
		include_once 'searchSql.php';
		
		if(isset($_GET['query'])){
			$searchSql = new SearchResultGenerator();
	    	$sqlArray = $searchSql->sqlForJsonQuery($_GET['query']);
	    	
	    	global $global;
	    	$db = $global['db'];
	    	$db->SetFetchMode(ADODB_FETCH_ASSOC);
        	$recordset = $db->GetAll($sqlArray['result']);

 
	    	$query = json_decode($_GET['query']);
	    	$fields_array = array();        

	    	if($query->select !=NULL){
	    		foreach($query->select as $field){
	                $mt = is_mt_field($field->entity, $field->field);	                
	                if($mt){
	                	$fields_array[] = $field->field;
	                }    		    
	            }
	    	}
			
        	$filename = 'openevsys_adv_search_results_'.date('Ymd-His').'.xls';
			header("Pragma: public"); 
        	header("Content-Type: application/x-msexcel");
        	header("Content-Disposition: attachment; filename=$filename;");       	
        	
			$count = 1;
        	foreach($recordset as $records){        		
        		foreach($records as $key=>$record){        			
        			if($count == 1){
        				$key = strstr($key,"_");      				
        				$key = substr($key, 1);
        				echo '"'. ucwords(str_replace('_', ' ', $key)) .'"' . ',';
        			}        			        			
        		}
        		$count++;
        	}
        	echo "\n";
        	
	    	foreach($recordset as $records){	    		
	    		foreach($records as $key=>$record){
	    			$key = strstr($key,"_");      				
        			$key = substr($key, 1);	    				    	
	    			
	    			if(in_array($key, $fields_array)){	    				
	    				echo '"'. get_mt_term(trim( $record)) .'"' . ',';
	    			}
	    			else if($key == 'confidentiality'){
	    				if($record == 'y'){
	    					echo '"'. _t('YES') .'"' . ',';
	    				}
	    				else{
	    					echo '"'. _t('NO') .'"' . ',';
	    				}
	    			}
	    			else if($key == 'deceased'){
	    				if($record == 'y'){
	    					echo '"'. _t('YES') .'"' . ',';
	    				}
	    				else{	    					
	    					echo '"'. _t('NO') .'"' . ',';
	    				}
	    			}
	    			else{
	    				echo '"'. $record .'"' . ',';
	    			}
	    		}
	    		echo "\n";
	    	}   	
			exit();
		}	
	}
	
	public function act_adv_report()
	{		
		if(isset($_GET['query'])){
			include_once 'searchSql.php';
			
			$searchSql = new SearchResultGenerator();
	    	$sqlArray = $searchSql->sqlForJsonQuery($_GET['query']);

	    	global $global;
	    	$db = $global['db'];
	    	$db->SetFetchMode(ADODB_FETCH_ASSOC);
        	$recordsets = $db->GetAll($sqlArray['result']);
        	
			$query = json_decode($_GET['query']);
	    	$fields_array = array();        

	    	if($query->select !=NULL){
	    		foreach($query->select as $field){
	                $mt = is_mt_field($field->entity, $field->field);	                
	                if($mt){
	                	$fields_array[] = $field->field;
	                }    		    
	            }
	    	}

        	$count = 1;
        	foreach($recordsets as $records){        		
        		foreach($records as $key=>$record){        			
        			if($count == 1){
        				$key = strstr($key,"_");      				
        				$key = substr($key, 1);        				
        				$this->columnNames[$key] = ucwords(str_replace('_', ' ', $key));
        			}        			        			
        		}
        		$count++;	
        	}

			foreach($recordsets as $rkey=>$records){        		
        		foreach($records as $key=>$record){
        			$key = strstr($key,"_");      				
        			$key = substr($key, 1);
        			
        			if(in_array($key, $fields_array)){
	    				$this->columnValues[$rkey][$key] = get_mt_term(trim( $record));
	    			}
        			else if($key == 'confidentiality'){
	    				if($record == 'y'){
	    					$this->columnValues[$rkey][$key] = _t('YES');
	    				}
	    				else{
	    					$this->columnValues[$rkey][$key] = _t('NO');
	    				}
	    			}
	    			else if($key == 'deceased'){
	    				if($record == 'y'){
	    					$this->columnValues[$rkey][$key] = _t('YES');
	    				}
	    				else{	    					
	    					$this->columnValues[$rkey][$key] = _t('NO');
	    				}
	    			}
	    			else{
	    				$this->columnValues[$rkey][$key] = $record;
	    			}        			  			      			        			
        		}	
        	}
		}
	}

	public function act_view_record_seq()
	{
		include_once APPROOT.'inc/lib_form_util.inc';

		global $global;
		$sqlStatement = $this->generateSqlforEntity($this->search_entity);
        $res = $global['db']->Execute($sqlStatement);
	}

	public function act_view_record_map()
	{
		include_once APPROOT.'inc/lib_form_util.inc';

		global $global;
		$sqlStatement = $this->generateSqlforEntity($this->search_entity);
        $res = $global['db']->Execute($sqlStatement);
	}
	/*}}}*/

/*{{{ SQL generation functions */
	
	public function generateSqlforEntity($entity_type , $shuffle=false , $dataArrayO = null , $search_type = null , $notIn=null){

        if($search_type == null){
            $search_type = 'search';
        }
        if($dataArrayO == null){
        	$dataArray = $_GET;
        }else{
        	$dataArray = $dataArrayO;
        }
        $dataArray = array_map('addslashes_deep', $dataArray);
        
        $sqlArray = array('select'=>array() , 'from'=>null,
        'join'=>array(), //array('table'=>null , 'jointype'=>null , 'field1'=>null, 'field2' =>null  , 'as'=>null )  
        'where'=>array() , 'orderby'=>array() , 'groupby'=>array()  );
        
    	$sqlStatement = "SELECT * FROM $entity_type";
    	
    	$sqlArray['from'] = $entity_type;
    	
    	$keyValue = '' ; //" , {$entity_type}_record_number as keyValue";
    	switch($entity_type){
			case 'event':
				$sqlStatement = "SELECT * $keyValue  FROM $entity_type";
				$entity_type_form = generate_formarray('event',$search_type);
				break;
			case 'person':
				$sqlStatement = "SELECT * $keyValue FROM $entity_type";
				$entity_type_form = generate_formarray('person',$search_type);
				break;
			case 'victim':
				$sqlArray['from'] = 'person';
				$sqlArray['join'][] = array('table'=>'act' , 'jointype'=>null , 'field1'=>'victim', 'field2' =>'person_record_number'  , 'as'=>null );  
				$sqlStatement  = "SELECT * $keyValue FROM person JOIN act ON victim = person_record_number " ;
				$entity_type_form = generate_formarray('victim',$search_type);
				break;
			case 'perpetrator':				
				$sqlArray['from'] = 'person';
                $sqlArray['join'][] = array('table'=>'involvement' , 'jointype'=>null , 'field1'=>'perpetrator', 'field2' =>'person_record_number'  , 'as'=>null );
				
				$sqlStatement  = "SELECT * $keyValue FROM person JOIN involvement ON perpetrator = person_record_number " ;
				//$entity_type = 'person';
				$entity_type_form = generate_formarray('perpetrator',$search_type);
				break;
			case 'source':
				$sqlArray['from'] = 'person';
                $sqlArray['join'][] = array('table'=>'information' , 'jointype'=>null , 'field1'=>'source', 'field2' =>'person_record_number'  , 'as'=>null );     
				
				$sqlStatement  = "SELECT * $keyValue FROM person JOIN information ON source = person_record_number " ;
				//$entity_type = 'person';
				$entity_type_form = generate_formarray('source',$search_type);
				break;
			case 'intervening_party':
				
				$sqlArray['from'] = 'person';
                $sqlArray['join'][] = array('table'=>'intervention' , 'jointype'=>null , 'field1'=>'intervening_party', 'field2' =>'person_record_number'  , 'as'=>null );
                
				$sqlStatement  = "SELECT * $keyValue FROM person JOIN intervention ON intervening_party = person_record_number " ;
				//$entity_type = 'person';
				$entity_type_form = generate_formarray('intervening_party',$search_type);
				break;	
			default:
				$entity_type_form = generate_formarray( $entity_type ,$search_type);
				break;
		}
		
		
		//var_dump('person address' , $dataArray ) ;
		if( get_table_for_entity( $entity_type )=='person' && isset($dataArray['person_addresses'] ) ){
			
			$sqlArray['join'][] = array('table'=>'address' , 'jointype'=>'LEFT' , 'field1'=>'person_record_number', 'field2' =>'person'  , 'as'=>null );                
			$sqlArray['select'][] = 'address.*';
			$sqlStatement = $sqlStatement . " LEFT JOIN address ON person_record_number= person ";
		}

		//SHUFFLE
        if($shuffle == true){
            $this->getShuffelSql($entity_type , $this->search_entity , $sqlArray );
        }
        //END SHUFFLE
		
		
		$sqlend = $this->generateSqlWhereforEntity($entity_type ,$entity_type_form , $shuffleJoin , $dataArrayO, $sqlArray);
		
		$sqlStatement .= $sqlend;

        $view_type = ($search_type == 'browse')?'browse':'search_view';	
        if($shuffle==false){
        	$view_entity = $entity_type;
        }else{
        	$view_entity = $this->search_entity;
        }
        
        
        
		$entity_type_form_results = generate_formarray(  $view_entity ,$view_type);
		
		//Generate select terms and join for mlt search VIEW
		$mtJoins = array();
     	foreach($entity_type_form_results as $field_name => $field){   //iterate through the search fields, checking input values
            
     		$fieldName = $this->getSelectFieldName($field, $sqlArray);
     		if($fieldName!=null){
     		$sqlArray['select'][] = $fieldName ;  //management data dosent appear in the $entity table ** FIX TO HANDLE THAT
     		}
     		
    		if( $field['map']['mlt'] == true   ){
    			$isMLTpresent  = true;   // this is only mlt present in VIEW, could have been searched too 
					$mtJoinArray = $this->generateMtJoin($field , $dataArray , $field_name , $sqlArray);
					$mtJoins['select'] .= " , " . $mtJoinArray['select'] ;
					if( $this->isInSearchArray($entity_type_form , $field_name ) == false || is_array( $dataArray[$field_name] ) == false ){
						$mtJoins['join'] .= $mtJoinArray['join'] ;
					}
					//var_dump($mtJoinArray);
				}
			}
		
			//add Primary key if not exists
			$pkOfEntity = $this->tableOfEntity( $view_entity ).'.'.get_primary_key($view_entity);
			if(in_array($pkOfEntity , $sqlArray['select']) == false ){
                $sqlArray['select'][] =  $this->tableOfEntity( $view_entity ).'.'.get_primary_key($view_entity) ;
			}
			
		
		//var_dump($mtJoins);
		//put the join ahead of the WHERE statement
		$whereIndex = stripos($sqlStatement , " WHERE");
		if($whereIndex == false){
			$sqlStatement .= $mtJoins['join'];
		}else{
			$sqlStatement = substr_replace($sqlStatement , $mtJoins['join'] , $whereIndex , 0);
		}
			
	
		$sqlStatement = substr_replace($sqlStatement , $mtJoins['select'] ,9 ,0);
		//var_dump($sqlStatement);
		//echo $sqlStatement;
		
		// GROUP BY 
		
		//if($isMLTpresent == true){
			$pkField = get_primary_key($this->tableOfEntity( $entity_type));
			
			$sqlArray['groupby'][] = $pkField;
			$sqlStatement .= " GROUP BY $pkField ";
		//}
		
		//END GROUP BY
				

		//SORT
			include_once APPROOT.'inc/lib_util.inc';
			$pk = get_primary_key($entity_type);			
			

			
    		if( isset( $dataArray['sort'] ) ){		
    		    foreach( $entity_type_form_results as $orderbykey => $orderbyField ){
	                if( $dataArray['sort'] == $orderbyField['map']['field'] ){
	                    $sortKey = $orderbykey;
	                }
	            }
				$sortField = $entity_type_form_results[ $sortKey ]['map']['field'];
    		}else{
    			$sortField = $pk;
    		}
    		
			if($sortField == $pk){
				$naturalSort = ' + 0 ';
			}
			
			$sortOrder = $dataArray['sortorder'] == 'desc' ? ' DESC ' : ' ASC '; 
			
			$sqlArray['orderby'][] = "$sortField $naturalSort $sortOrder";
			$sqlStatement .= " ORDER BY $sortField $naturalSort $sortOrder";

		//} SORT END
		
			//NotIn
			if(is_array($notIn)){
			    
			    $sqlArray['where'][] = " $pkOfEntity NOT IN( '" . implode("','" , $notIn) . "')";
			}if($notIn=='allowed_records'){
			    $sqlArray['where'][] = " $pkOfEntity NOT IN( SELECT id FROM (SELECT * from allowed_records GROUP BY id
HAVING order_id = min( order_id ) ) as ori WHERE allowed = 0 )";
			}
			
		
			//var_dump($sqlArray);
			$sqlStatement = $this->sqlArrayToSql($entity_type ,$sqlArray);
		//echo $sqlStatement;	
		return $sqlStatement;
		
    }
    
    private function isInSearchArray($search_form_array , $check_field_name){
    	
    	foreach($search_form_array as $field_name => $field){
    		
    		if ( $check_field_name == $field_name){
    			return true;
    		}
    		
    	}
    	return false;
    	
    }
    
    private function generateSqlWhereforEntity($entity_type , $search_field_array , $shuffleJoin = null , $dataArray = null , &$sqlArray){
    	
    	if($dataArray == null){
    		$dataArray = $_GET;
    	}
    	
    	//var_dump('dataarray' , $dataArray);
    	//var_dump('searchfieldarray',$search_field_array);
        $mtJoins = array();    	    	
    	$entity_type = $this->tableOfEntity($entity_type);
    	//var_dump('search field array',$search_field_array);
    	foreach($search_field_array as $field_name => $field){   //iterate through the search fields, checking input values
			
    		//var_dump($field , $dataArray[$field['map']['field']  ]);
    		if( $field['map']['mlt'] == true   ){
    			//var_dump($dataArray[$field_name]);
				if( is_array($dataArray[$field_name])){
					$mtJoinArray = $this->generateMtJoin($field , $dataArray, $field_name , $sqlArray );
					$mtJoins['join'] .= $mtJoinArray['join'];
					$mtJoins['where'] .= $mtJoinArray['where'];
					//var_dump($mtJoinArray);
				}
			}
			else{
				if( trim( $dataArray[ $field_name ] ) != '' ){	
					$condition = $this->generateCondition($field , $dataArray, $field_name , $sqlArray );
					if($condition == null)	{		
						//$condition =  $field['map']['field'] .' LIKE '. "'%" . $dataArray[$field_name] . "%'" ;
					}
						
					if($where == null ){
						$and = '';
					}
					else{
						$and = ' AND ';
					}
					if($condition != null && trim($condition) != '' ){						
						$where = $where . $and . $condition ;	
					}
				}
			}
		}
		
		$this->relatedEventOrPersonSearch( $dataArray , $sqlArray);
		
		$sqlArray['join'][] = array('table'=>'management' , 'jointype'=>'LEFT' , 'field1'=>'entity_id', 'field2' => get_primary_key($entity_type)  , 'as'=>null , 'condition'=>" AND entity_type ='$entity_type' " );
		$managementJoin = ' LEFT JOIN management on entity_id=' .  get_primary_key($entity_type) . " AND entity_type ='$entity_type' "; //$entity_type . '_record_number ';     	
    	
    	if($where != null || $mtJoins['where'] != null){
			$sqlStatement = $managementJoin . $mtJoins['join'] . $shuffleJoin . ' WHERE ' . $where . ($mtJoins['where'] != null && trim($mtJoins['where'] && $where != null)  != '' ? 'AND': null ) . $mtJoins['where'];
		}else{
			$sqlStatement = $managementJoin . $mtJoins['join'] . $shuffleJoin   ;			
		}
		
/*		if(isset($dataArray['sort'])){
			$sortField = $search_field_array[ $dataArray['sort'] ]['map']['field'];
			$sortOrder = $dataArray['sortorder'] == 'desc' ? ' DESC ' : ' ASC '; 
			$sqlStatement .= " ORDER BY $sortField $sortOrder";

		}*/
		
		return $sqlStatement;
    }
    
    private function relatedEventOrPersonSearch( $dataArray , &$sqlArray)
    {	
    	if($dataArray['search_type'] != null){    		
			switch($dataArray['search_type']){
				case 'person':
					if($sqlArray['from']=='person'){
						$sqlArray['where'][] = " person_record_number <> '{$dataArray['pid']}'";					
					}
					break;
				case 'event':
					$sqlArray['where'][] = " event_record_number <> '{$dataArray['eid']}'";					
					break;
				case 'victim':
					$sqlArray['where'][] = " act.event = '{$dataArray['eid']}'";					
					break;													
			}
			
		}
    }
    
    private function generateCondition($fieldArray  , $dataArray , $fieldName  , &$sqlArray ){
    	//var_dump('fieldArray type ' , $fieldArray );
        $dataArray = array_map('addslashes_deep', $dataArray);
        if( is_management_field($fieldArray) ){
            $fieldArray['map']['entity'] = 'management';
        }
        
    	switch($fieldArray['type']){
    		case 'hidden' :
    			$condition = $fieldArray['map']['entity'] .'.'. $fieldArray['map']['field'] . " LIKE  '%$dataArray[$fieldName]%'" ;
    			//return $condition;
    			break;
            case 'textarea':
            	$condition = // ' SOUNDEX(' . $fieldArray['map']['field'] . ")  LIKE concat( '%', SOUNDEX( '$dataArray[$fieldName]' ) , '%' ) OR " .
                $fieldArray['map']['entity'] .'.'. $fieldArray['map']['field'] . " LIKE  '%$dataArray[$fieldName]%'" ;
                //return $condition;
                break;  
    		case 'text' :
    			$condition = // ' SOUNDEX(' . $fieldArray['map']['field'] . ")  LIKE concat( '%', SOUNDEX( '$dataArray[$fieldName]' ) , '%' ) OR " .
    			$fieldArray['map']['entity'] .'.'. $fieldArray['map']['field'] . " LIKE  '%$dataArray[$fieldName]%'" ;
    			//return $condition;
    			break;    			
    		case 'mt_select' :
    			$condition = " {$fieldArray['map']['field']} = '$dataArray[$fieldName]' " ;     			
    			//return $condition;
    			break;
    		case 'mt_tree':
    			$huriParent = rtrim( substr( $dataArray[$fieldName] ,0 ,10 )  , '0' );
    			$condition = " {$fieldArray['map']['field']} LIKE  '$huriParent%' " ;
    			//return $condition;
    			break;
    		case 'date':
    			$condition =  ' ' . $fieldArray['map']['entity'] .'.'. $fieldArray['map']['field'] .' LIKE '. "'%" . $dataArray[$fieldName] . "%'" ;
    			//return $condition;
    			break;
    		case 'document' :
    		
    			break;
    		case 'upload':
    			
    			break;
    			
    		case 'checkbox':
    			$compValue = "'n' , '0'";
    			if( strtolower( $dataArray[$fieldName] )=='on'   ){
    				$compValue = "'y' , '1' ";
    			}    			
                $condition = $fieldArray['map']['entity'] .'.'. $fieldArray['map']['field'] . " IN ( $compValue)" ;
                break;
    		case 'address' :
    			$condition = null;
    			$and = null;
    			$addressSearchForm = address_form('search');
    			//var_dump($addressSearchForm);
    			foreach($addressSearchForm as $addressFieldName => $addressField){
    				//var_dump($addressFieldName);
    				if(trim( $dataArray[ $addressFieldName ] ) != ''){
    					//var_dump($dataArray[ $addressFieldName ]);
    					$conditionPart = null;
    					$conditionPart = $this->generateCondition($addressField ,$dataArray, $addressFieldName, $sqlArray);
    					
    					if($condition == null || trim($condition) == '' ){
							$and = '';
						}else {
							$and = ' AND ';
						}
						if($conditionPart != null){
							$condition = $condition . $and . $conditionPart ;
						}
    				}
    				
    			}
    			return $condition;
    			break;

    			
    	}   			
    	//var_dump('generate condition' , $condition);
        $sqlArray['where'][]=$condition;
        
        return  $condition;
    	
    }
    
    
    private function generateMtJoin($field , $dataArray, $field_name , &$sqlArray){
    	$entity_type = $field['map']['entity'];
    	$fieldName = $field['map']['field'];
    	//var_dump('mlt' ,$entity_type , $fieldName);
    	$mtValues  = $dataArray[$field_name];
    	$mltTable = ' mlt_'. $this->tableOfEntity( $entity_type) . '_' . $fieldName;
    	$mtJoin =array();
    	
    	$sameMltPresent = false;
    	foreach($sqlArray['join'] as $joinSqlElement){
    		if($joinSqlElement['table'] == $mltTable ){
    			$sameMltPresent = true;
    			break;
    		}
    	}
    	
    	if($sameMltPresent == false){
    	
	    	$sqlArray['select'][] = " GROUP_CONCAT(DISTINCT $mltTable.vocab_number ORDER BY $mltTable.vocab_number ASC SEPARATOR ' , ')  as $fieldName ";
	        $sqlArray['join'][] = array('table'=>$mltTable , 'jointype'=>'LEFT' , 'field1'=>'record_number', 'field2' =>get_primary_key($entity_type) , 'entity1'=>$mltTable  , 'as'=>null );  
	                
	    	
	    	$mtJoin['join'] =  ' left  JOIN '. $mltTable . '  ON ' .$mltTable . '.record_number=' .  get_primary_key($entity_type); // $entity_type . '_record_number ';
			$mtJoin['select'] = " GROUP_CONCAT(DISTINCT $mltTable.vocab_number ORDER BY $mltTable.vocab_number ASC SEPARATOR ' , ')  as $fieldName ";
			$in=null;
			if(is_array($mtValues) ) {
		    	foreach($mtValues as $value ){
//var_dump('mt_mlt' , $mtValues);
		    		$dataArray['__temp_mt'] = null;                     // THIS IS A HACK.THIS is a manupilation of data so taht teh function can generate results.
		    		$dataArray['__temp_mt'] = $value;                   //    
		    		$field['map']['field'] = $mltTable.'.vocab_number'; // SHOULD be FIXED 
		    		$this->generateCondition($field , $dataArray, '__temp_mt' , $sqlArray );
		    		
		    		
		    		if($in == null){
		    			$in .= $value;
		    		}else{
		    			$in .= ','. $value;
		    		}
		    	}
			}
	    	
	    	if($in != null){
	    		
	    		//$sqlArray['where'][]= " $mltTable.vocab_number IN ( $in )  ";
	    		$mtJoin['where'] .= " $mltTable.vocab_number IN ( $in )  ";
	    		//var_dump($mtJoin['where']);
	    	}
    	}
    	//var_dump($sqlArray['where']);
    	return $mtJoin;
    }
    
    private function tableOfEntity($entity_type){
        if($entity_type == 'victim' || $entity_type == 'perpetrator'  ||  $entity_type == 'source' || $entity_type=='intervening_party'){
			$entity_table = 'person'; 
		}else{
			$entity_table = $entity_type;
		}
		return $entity_table;
    }
    
    private function getShuffelSql($start_entity , $final_entity, &$sqlArray=null){
    	$relaArray = analysis_get_relationship_array();
    	
    	$joinArray = $relaArray[$start_entity][$final_entity]['rel'];
    	foreach($joinArray as $join){

    		$sqlArray['join'][] = array('table'=>$join[2] , 'jointype'=>'INNER' , 'field1'=>$join[1], 'field2' =>$join[3] ,'entity1'=>$join[0] , 'entity2'=>$join[2] , 'as'=>null );
    		
    		$joinSql .= " INNER JOIN $join[2] ON $join[0].$join[1]=$join[2].$join[3] ";
    		
    	}
    	//$sqlArray['select'][] = $this->tableOfEntity($final_entity).'.* ' ; // change to handle sec entities 
    	return $joinSql;
    	
    }/*}}}*/
    
    private function getSelectFieldName($formField , &$sqlArray ){
    	//var_dump($formField);
    	if($formField['map']['mlt']){
    	   return null;	
    	}
    	if( is_management_field($formField) ){
    		return 'management'.'.'. $formField['map']['field'];
    	}
    	if($formField['map']['entity']=='person' && $formField['map']['field']=='person_addresses' ){
    		//$sqlArray['select'][] = 'address.*';
    		//$sqlArray['join'][] = array('table'=>'address' , 'jointype'=>'LEFT' , 'field1'=>'person_record_number', 'field2' =>'person'  , 'as'=>null );
    		return null;
    	}
    	if($formField['type'] == 'document' ){
    		return null;
    	}
        if($formField['type'] == 'upload' ){
            return null;
        }
    	
    	return $formField['map']['entity'].'.'. $formField['map']['field'];    	
    }
    
    
    private function sqlArrayToSql($entityType , $sqlArray){
    	
    	$selectTableArray = array();
    	
        //JOIN
    	foreach($sqlArray['join'] as  $joinElement){
    		$on1 = ($joinElement['entity1']!=null) ? "{$joinElement['entity1']}.{$joinElement['field1']}" : "{$joinElement['field1']}"; 
    		$on2 = ($joinElement['entity2']!=null) ? "{$joinElement['entity2']}.{$joinElement['field2']}" : "{$joinElement['field2']}";
    		
    		//join condition
    		$joinCondition = null;
    		if($joinElement['condition']!=null){
    			$joinCondition = $joinElement['condition']; 
    		}
    		
    		$joinSql .= " {$joinElement['jointype']} JOIN {$joinElement['table']} ON $on1 = $on2 $joinCondition";
    		
    		//select the tables that come for SELECT xxxx fields ignore mlt tables and choose all other tables
    		/*if( strncmp( trim($joinElement['table']) , 'mlt_' , 4) != 0 ){
    			$selectTableArray[] = $joinElement['table'];
    		}  */  		
    		
//var_dump('JOIN' , $joinSql );
    	}
    	//JOIN END
//var_dump('selectTableArray' , $selectTableArray);


        //SELECT

        
        //$selectTableSql = $entityType.'.* ';
        foreach($selectTableArray as $selectTableElement){
        	$selectTableSql .= ($selectTableSql == null ? '' : ' , ') . $selectTableElement . ".* "; 
        }
        $selectSql = $selectTableSql;
        foreach($sqlArray['select'] as $selectElement){
           $selectSql .= ($selectSql==null ?'':' , ') . $selectElement;
        }
        
//var_dump('SELECT' , $selectSql );
        
        //SELECT END



        //WHERE
        foreach($sqlArray['where'] as $whereElement){
           $whereSql .= ($whereSql==null ?'':' AND ') . $whereElement;
        }
//var_dump('WHERE' , $whereSql );
        //WHERE END

        //GROUP BY
        foreach($sqlArray['groupby'] as $groupbyElement){
           $groupbySql .= ($groupbySql==null ?'':' , ') . $groupbyElement;
        }
        //GROUP BY END

        //ORDER BY
        foreach($sqlArray['orderby'] as $orderbyElement){
           $orderbySql .= ($orderbySql==null ?'':' , ') . $orderbyElement;
        }
        //GROUP BY END
        
        
        $whereSql = $whereSql != null ? ' WHERE '. $whereSql : '';
        $groupbySql = $groupbySql != null ? ' GROUP BY '. $groupbySql : ''; 
        $orderbySql = $orderbySql != null ? ' ORDER BY '. $orderbySql : '';
        
    	$sqlStatement = "SELECT $selectSql FROM {$sqlArray['from']} $joinSql $whereSql $groupbySql  $orderbySql ";
        //echo $sqlStatement;
    	return $sqlStatement;
    }
    
    public function act_adv_search()
    {
        if(isset($_GET['qid'])){
            $this->query = new SaveQuery();
            $this->query->LoadfromRecordNumber($_GET['qid']);
        }
        //the advance search is an client side javascript app
    }

    public function act_get_data_dict()
    {
        $res = Browse::getAllEntityFields();
        $domain = new Domain();
        foreach($res as $record){
            $entity = $record['entity'];
            if(isset($entity) && !isset($domain->$entity)){
                $domain->$entity = new domain();
                $domain->$entity->fields = new domain();
//                $domain->$entity->fields = array();
            }
            $fo = new Domain();
            $name = $record['field_name'];
            $fo->value = $record['field_name'];
            $fo->label = $record['field_label'];
            $fo->field_type = ($record['datatype'] == 'N')? 'number':$record['field_type'];
            $fo->list_code = $record['list_code'];
            $fo->select = $record['in_results'];
            $domain->$entity->fields->$name = $fo ;
        }
        //add the entity list
        $entities = analysis_get_advance_search_entities();

        foreach($entities as $key=>$entity){
            $domain->$key->value = $entity['type'];
            $domain->$key->label = $entity['title'];
            $domain->$key->desc = $entity['desc'];
            $domain->$key->ac_type = $entity['ac_type'];
        }
        echo json_encode($domain);
        exit(0);
    }

	
    public function act_query(){
        exit(0);
    }

    
    public function act_load_grid(){
    	global $global;
    	include_once 'searchSql.php';
    	
    	$page = (int)$_GET['page'];
    	$limit = (int)$_GET['rows']; 
    	$sidx = $_GET['sidx']; 
    	$sord = $_GET['sord'];

        //convert json query to an object 
    	$query = json_decode($_GET['query']);
        //build the select field array
    	$fields_array = array();
        $entities = analysis_get_search_entities();
    	if($query->group_by !=NULL){
            //if the query is a count put group by field to the array
    		foreach($query->group_by as $field){
                $entity = (isset($entities[$field->entity]['ac_type']))?$entities[$field->entity]['ac_type']:$field->entity;
                $mt = is_mt_field($entity, $field->field);
    		    array_push($fields_array, array('name'=>$field->entity.'_'.$field->field , 'mt'=>$mt));
            }
    		array_push($fields_array, array('name'=>'count'));
    	}
    	else{
            //if the query is a search put select fields to the array
    		foreach($query->select as $field){
                $entity = (isset($entities[$field->entity]['ac_type']))?$entities[$field->entity]['ac_type']:$field->entity;
                $mt = is_mt_field($entity, $field->field);
        		array_push($fields_array, array('name'=>$field->entity.'_'.$field->field , 'mt'=>$mt));
            }
    	}
    	
//var_dump('fields_array',$fields_array);
    	if(!$sidx) $sidx =1;


	    $searchSql = new SearchResultGenerator();
	    $sqlArray = $searchSql->sqlForJsonQuery($_GET['query']);
    	//var_dump($sqlArray);exit;
    	//$count_query = $sqlArray['count'];
        $count_query = "SELECT COUNT(*) FROM ({$sqlArray['result']}) as results";
    	
        try{
		$res_count = $global['db']->Execute($count_query);
		}catch( Exception $e){
		    $response->error = "error";//$e->getMessage();
		    $res_count = null;
		}
    	
		if($res_count != null)
    	while(!$res_count->EOF)
    	{
    		$count = $res_count->fields[0];
    		$res_count->MoveNext();
    	}
    	
    	if( $count > 0 ) { 
              $total_pages = ceil($count/$limit); 
		} else { 
              $total_pages = 0; 
		}
		
		if ($page > $total_pages) $page=$total_pages;
		
		$start = $limit*$page - $limit;
		
		if($start <0) $start = 0; 
		
		$sql = $sqlArray['result'];
		//print $sql;
		
		$sql .= " LIMIT $start , $limit";
		//$sql .= "LIMIT $start , $limit";
		
		//echo $sql;
		try{
		$res = $global['db']->Execute($sql);
		}catch( Exception $e){
		    $response->error = "error";//$e->getMessage();
		}

		$response->page = $page; // current page
		$response->total = $total_pages; // total pages
		$response->records = $count; // total records
		$i=0;
		
		/*var_dump($res);
		while(!$res->EOF)
    	{
    		var_dump($res->fields[0]);
    		
    		$res->MoveNext();
    	}*/
		$number_of_fields = count($fields_array);
		foreach($res as $key=>$val)
		{			
			$response->rows[$i]['id'] = $val[$fields_array[0]];
			$array_values = array();
			
			for($count = 0;$number_of_fields > $count;$count++)
			{				
				$field_name = $fields_array[$count]['name'];				
				$record_number_field = substr($field_name, strlen($field_name)-13);
				
				$confidentiality_field = substr($field_name, strlen($field_name)-15);
				$deceased_field = substr($field_name, strlen($field_name)-8);
				$doc_field = substr($field_name, strlen($field_name)-6);
				
				if($confidentiality_field == 'confidentiality'){
					if($val[$field_name] == 'y'){
						$val[$field_name] = _t('YES');
					}
					else{
						$val[$field_name] = _t('NO');
					}
				}

				if($deceased_field == 'deceased'){
					if($val[$field_name] == 'y'){
						$val[$field_name] = _t('YES');
					}
					else{
						$val[$field_name] = _t('NO');
					}
				}
				
	            $string = null;
	            if($fields_array[$count]['mt']){
	            	$list = explode(',' , $val[$field_name]);
	                foreach($list as $term){
	                	$string = $string .", " . get_mt_term(trim($term));
	               	}
	                $array_values[] = ltrim($string , ',');
	          	}
				else if($record_number_field == 'record_number' || $doc_field == 'doc_id'){
					if(preg_match('/event/',$field_name)){
						$link_entity = 'event';
					}
					else if(preg_match('/act/',$field_name)){
						$link_entity = 'act';
					}					
					else if(preg_match('/source/',$field_name)){
						$link_entity = 'source';
					}
					else if(preg_match('/perpetrator/',$field_name)){
						$link_entity = 'perpetrator';
					}
					else if(preg_match('/victim/',$field_name)){
						$link_entity = 'victim';
					}
					else if(preg_match('/involvement/',$field_name)){
						$link_entity = 'involvement';
					}
					else if(preg_match('/information/',$field_name)){
						$link_entity = 'information';
					}
					else if(preg_match('/intervention/',$field_name)){
						$link_entity = 'intervention';
					}
					else if(preg_match('/intervening_party/',$field_name)){
						$link_entity = 'intervening_party';
					}
					else if(preg_match('/person/',$field_name)){
						$link_entity = 'person';
					}					
					else if(preg_match('/biographic_details/',$field_name)){
						$link_entity = 'biographic_details';
					}
					else if(preg_match('/supporting_docs_meta/',$field_name)){
						$link_entity = 'supporting_docs_meta';
					}
					else if(preg_match('/address/',$field_name)){
						$link_entity = 'address';
					}
					
					if($link_entity != 'address'){
	             		$url = get_record_url($val[$field_name], $link_entity);
						$array_values[] = "<a href='$url' target='_blank'>" . $val[$field_name] . "</a>";
					}
					else{
						$array_values[] = $val[$field_name];
					}
				}
				else{
	                $array_values[] = $val[$field_name];
				}
			}		
			$response->rows[$i]['cell'] = $array_values;
			$i++;
		}

    	echo json_encode($response);
    	
    	exit(0);
    }
}
