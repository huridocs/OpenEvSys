<?php
/**
 * Main Class of person module.
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
 * @package	OpenEvsys
 * @subpackage	person
 *
 */

include_once APPROOT.'inc/lib_entity_forms.inc';
include_once APPROOT.'inc/lib_form_util.inc';
include_once APPROOT.'inc/lib_uuid.inc';
include_once APPROOT.'inc/lib_files.inc';

include_once 'messages.inc';

class personModule extends shnModule
{
    function act_default()
    {
        set_redirect_header('person','browse');
    }

    function section_mod_menu()
    {
        $data['breadcrumbs'] = shnBreadcrumbs::getBreadcrumbs();
        if($_GET['act']=='new_person')
            $data['active'] = 'new';
        else if($_GET['act']=='browse')
            $data['active'] = 'browse';
        return $data;
    }

    function __construct() {
        global $messages;
        $this->load_related_person();
        if(isset($_GET['act']) && $_GET['act']!='new_person' && $_GET['act']!='browse'){
            $_GET['pid'] = (isset($_GET['pid']))?$_GET['pid']:$_SESSION['pid'];
            $this->pid = $_GET['pid'];		
            if(!isset($_GET['pid'])){
                shnMessageQueue::addInformation($messages['select_person']);
                set_redirect_header('person','browse');
                exit();
            }
            $this->person = new Person();
            $this->person->LoadFromRecordNumber($_GET['pid']);
			$this->person->LoadRelationships();
			$this->person->LoadAddresses();
			$this->person->LoadPicture();
            //if person does not exist
            if($this->person->person_record_number != $_GET['pid']){
                shnMessageQueue::addError($messages['person_not_found']);
                set_redirect_header('person','browse');
                exit();
            }
            $_SESSION['pid']=$_GET['pid'];            
            set_url_args('pid',$this->person->person_record_number);
        }
        global $person; //hack for the permission card list; need to replaced with proper code
        $person = $this->person;
    }

    private function load_related_person(){
        if(isset($_GET['biography_id'])){
    		$this->biographic_details = new BiographicDetail();
        	$this->biographic_details->LoadFromRecordNumber($_GET['biography_id']);    
		    $this->biographic_details->LoadRelationships();
            $_GET['pid'] = $this->biographic_details->person; 
        }
    }
    /**
     * act_new_person will generate ui to add a new person 
     * 
     * @access public
     * @return void
     */
	public function act_new_person()
    {    	
    	$this->person_form = person_form('new');
    	unset($this->person_form['person_addresses']);
        if(isset($_POST['save'])){           
            $status = shn_form_validate($this->person_form);
            if($status){
                $person = new Person();
                $person->person_record_number = shn_create_uuid('person');
                form_objects($this->person_form, $person);
				$person->deceased = ($person->deceased == 'on') ? 'y' : 'n';
                $person->SaveAll();
                $person->SavePicture();				
                $this->person = $person;
                set_url_args('pid',$this->person->person_record_number);                
				set_redirect_header('person','person');                
            }
        }		
    }

	/**
     * act_person_address_list will generate ui for person address 
     * 
     * @access public
     * @return void
     */
	public function act_address_list()
    {    
    	$this->address = new Address();
    	
    	$this->person = new Person();
        $this->person->LoadFromRecordNumber($this->pid);
        $this->person->LoadRelationships();
		$this->person->LoadAddresses();
        $this->addresses = $this->person->person_addresses;

        if(isset($_GET['address_id'])){
        	$this->address->LoadfromRecordNumber($_GET['address_id']);        	
			$address_form = address_form('view');			
    		popuate_formArray($address_form, $this->address);
          	$this->address_form = $address_form;
        }
    }
    
    public function act_new_address()
    {
    	$this->address = new Address(); 	
    	$this->address_form = address_form('new');		
		
    	if(isset($_POST['save'])){
    		$status = shn_form_validate($this->address_form);
    		if($status){		
				form_objects($this->address_form, $this->address);
				$this->address->person = $this->pid;			
				$this->address->Save();
				
				set_url_args('pid',$this->pid);
				set_redirect_header('person','address_list');
	           	return;
    		}
        }
    }
    
	public function act_edit_address()
    {
    	$this->address = new Address(); 	
    	$this->address_form = address_form('edit');	

    	if(isset($_GET['address_id'])){
	    	$this->address->LoadfromRecordNumber($_GET['address_id']);        	
			$address_form = address_form('view');			
	    	popuate_formArray($address_form, $this->address);
	        $this->address_form = $address_form;
    	}
    	if(isset($_POST['update'])){
    		$status = shn_form_validate($this->address_form);
    		if($status){
				form_objects($this->address_form, $this->address);
				$this->address->person = $this->pid;			
				$this->address->Save();
				
				set_url_args('pid',$this->pid);
				set_redirect_header('person','address_list');
				return;
    		}
        }
    }
    
    function act_delete_address()
    {
    	$this->address = new Address();
    	
    	if(!isset($_POST['addresses']) || isset($_POST['no'])){
            set_redirect_header('person','address_list');			
            return;
        }
		if(is_array($_POST['addresses']) && count($_POST['addresses']) != 0){
        	$this->addresses = $_POST['addresses'];
		}

        $this->del_confirm = true;
        if(isset($_POST['yes'])){            
            if(is_array($_POST['addresses'])){
                foreach($_POST['addresses'] as $address){
                    $a = new Address();
                    $a->DeleteFromRecordNumber($address);
                }
            }
            set_url_args('pid',$this->pid);
            set_redirect_header('person','address_list');
            return;
        }
    }
    
	function act_edit_person()
	{
		include_once APPROOT.'inc/lib_form_util.inc';
        include_once APPROOT.'inc/lib_uuid.inc';
        
		$person_form = person_form('edit');
		unset($person_form['person_addresses']);		

		if(isset($_POST['update'])){            
            $status_person = shn_form_validate($person_form);
            if($status_person){
                $person = new Person();                               
                form_objects($person_form, $person);				
				$person->deceased = ($person->deceased == 'on') ? 'y' : 'n';								
                $person->SaveAll();
                $person->SavePicture();				
                $this->person = $person;
                set_url_args('pid',$this->person->person_record_number);
				set_redirect_header('person','person');
                return;
            }           
        }
		
		if(isset($_GET['pid'])){			
            $this->pid = $_GET['pid'];
            $this->person = new Person();
            $this->person->LoadFromRecordNumber($this->pid);
            $this->person->LoadRelationships();
            $this->person->LoadPicture();	
            popuate_formArray($person_form,$this->person);			
        	$this->person_form = $person_form;
			$this->fields = shn_form_get_html_fields($person_form);
		}
	}

    function act_person()
	{
		$this->biographics = Browse::getRelativeInfo($_GET['pid']);//loaded for contextual info			
    }


    function act_browse()
    {
		include_once APPROOT.'inc/lib_form.inc';

		$notIn = acl_list_person_permissons();
		 $notIn = 'allowed_records'; // passed to generateSql function to use the temporary table to find the allowed records
        require_once(APPROOT.'mod/analysis/analysisModule.class.php');
    	$analysisModule = new analysisModule();
    	$sqlStatement = $analysisModule->generateSqlforEntity('person',null,$_GET, 'browse' , $notIn);

 		$entity_type_form_results = generate_formarray('person' ,'browse');	
 		$entity_type_form_results['person_record_number']['type'] = 'text';
 		$field_list = array();
		foreach($entity_type_form_results as $field_name => $field){   
			// Generates the view's Label list
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
		$this->result_pager = Browse::getExecuteSql($sqlStatement);			
        $this->columnValues = $this->result_pager->get_page_data();
        $this->columnValues = set_links_in_recordset( $this->columnValues , 'person' );

        set_huriterms_in_record_array( $entity_type_form_results , $this->columnValues );
        
		//rendering the view
		$this->columnNames = $field_list;	
    	$this->htmlFields = $htmlFields;		
    }

    function act_delete_person()
    {
        if(isset($_POST['no'])){
            set_redirect_header('person','browse');
            return;
        }
				
        $this->del_confirm = true;
        if(isset($_GET['pid']) && isset($_POST['yes'])){
            $p = new Person();
            $p->DeleteFromRecordNumber($_GET['pid']);

            set_redirect_header('person','browse');
            return;
        }

		$this->person_victim = "Victim";		
		$this->person_victim_pager = Browse::getPersonVictimRoleList($this->pid);
		$this->further_victim_msg = "Type of Act" . " > ";
		$this->victim_records = $this->person_victim_pager->get_page_data();

		$this->person_perpetrator = "Perpetrator";		
		$this->person_perpetrator_pager = Browse::getPersonPerpetratorRoleList($this->pid);
		$this->further_perpetrator_msg = "Degree of Involvement" . " > ";
		$this->perpetrator_records = $this->person_perpetrator_pager->get_page_data();

		$this->person_source = "Source";		
		$this->person_source_pager = Browse::getPersonSourceRoleList($this->pid);
		$this->further_source_msg = "Source Connection to Information" . " > ";
		$this->source_records = $this->person_source_pager->get_page_data();

		$this->person_intervening_party = "Intervening Party";		
		$this->person_intervening_party_pager = Browse::getPersonInterveningPartyRoleList($this->pid);
		$this->further_intervening_party_msg = "Type of Intervention" . " > ";
		$this->intervening_party_records = $this->person_intervening_party_pager->get_page_data();		         
    }

	public function act_delete_biographic()
	{
		if(!isset($_POST['biographics']) || isset($_POST['no'])){
            set_redirect_header('person','biography_list');			
            return;
        }		

        $this->del_confirm = true;
        if(isset($_POST['yes'])){
            if(isset($_POST['biographic']))
                array_push($_POST['biographics'],$_POST['biographic']);
            //if multiplt events are selected
            if(is_array($_POST['biographics'])){
                foreach($_POST['biographics'] as $biographic){
                    $b = new BiographicDetail();
                    $b->DeleteFromRecordNumber($biographic);
                }
            }
            set_redirect_header('person','biography_list');
            return;
        }
		
		$this->biographics = Browse::getBiographyListArray($_POST['biographics']);		
	}

	function act_new_biography()
	{
		global $conf;

		include_once APPROOT.'inc/lib_form_util.inc';
        include_once APPROOT.'inc/lib_uuid.inc';
		include_once APPROOT.'inc/lib_form.inc';
        
		$biography_form = biographic_form('new');
		
		$this->pid = (isset($_GET['pid']) && $_GET['pid'] != null) ? $_GET['pid'] : $_SESSION['person']['pid'];
		if($this->pid != null){
		    $person_form = person_form('new');
			$this->person_form = $person_form;
			$this->person = $this->person_information($this->pid,$this->person_form);
			$_SESSION['person']['pid'] = $this->pid;
		}
		
		if(isset($_POST['save'])){
			$this->pid = $_SESSION['person']['pid'];
            $status = shn_form_validate($biography_form);
            if($status){
                if($_POST['biographic_details_record_number'] == ''){
                   $_POST['biographic_details_record_number'] = shn_create_uuid('biography');
                }
                $_GET['pid'] = $_SESSION['person']['pid'];
                $biography = new BiographicDetail();
                $biography->LoadfromRecordNumber($_POST['biographic_details_record_number']);
                $biography->biographic_details_record_number = $_POST['biographic_details_record_number'];				
                form_objects($biography_form, $biography); 
				$biography->person = $_SESSION['person']['pid'];
                if($biography->related_person == '') $biography->related_person = null;	
                $biography->SaveAll();

                $_GET['pid'] = null;            
				
                $_GET['bid'] = $_POST['biographic_details_record_number'];				
				$this->biography_list($_SESSION['person']['pid']);
				$_SESSION['pid'] = $_SESSION['person']['pid'];
				
				set_redirect_header('person','biography_list',null,array('biography_id'=>$biography->biographic_details_record_number,'type'=>'bd'));
            }			
        }
		else{
			$biography_form['related_person']['extra_opts']['readonly'] = true;
			$this->biography_form = $biography_form;
		}					
	}

	function act_edit_biography()
	{
		include_once APPROOT.'inc/lib_form_util.inc';
        include_once APPROOT.'inc/lib_uuid.inc';  
	
		$biography_form = biographic_form('edit');      

		$this->pid = (isset($_GET['pid']) && $_GET['pid'] != null) ? $_GET['pid'] : $_SESSION['pid'];
		if($this->pid != null){
		    $person_form = person_form('new');
			$this->person_form = $person_form;
			$this->person = $this->person_information($this->pid,$this->person_form);
			$_SESSION['person']['pid'] = $this->pid;
		}
		
		if(isset($_GET['biography_id'])){
            set_url_args('biography_id',$this->biographic_details->biographic_details_record_number);
			popuate_formArray($biography_form,$this->biographic_details);			
			$this->biography_form = $biography_form;
		}
		
		if(isset($_POST['save'])){
			$this->pid = $_SESSION['person']['pid'];
            $status = shn_form_validate($biography_form);
            if($status){
                if($_POST['biographic_details_record_number'] == ''){
                   $_POST['biographic_details_record_number'] = shn_create_uuid('biography');
                }
                $_GET['pid'] = $_SESSION['person']['pid'];
                $biography = new BiographicDetail();
                $biography->LoadfromRecordNumber($_POST['biographic_details_record_number']);
                $biography->biographic_details_record_number = $_POST['biographic_details_record_number'];				
                form_objects($biography_form, $biography); 
				$biography->person = $_SESSION['person']['pid'];
                if($biography->related_person == '') $biography->related_person = null;	
                $biography->SaveAll();

                $_GET['pid'] = null;
                $_GET['bid'] = $_POST['biographic_details_record_number'];
				
				$this->biography_list($_SESSION['person']['pid']);
				$_SESSION['pid'] = $_SESSION['person']['pid'];
				
				set_redirect_header('person','biography_list',null,array('biography_id'=>$biography->biographic_details_record_number,'type'=>'bd'));
            }			
        }
	}	

	function act_biography_list()
	{
		include_once APPROOT.'inc/lib_form_util.inc';
        include_once APPROOT.'inc/lib_uuid.inc';
        
        $biography_form = biographic_form('view');
		$this->person_form = person_form('view');
		
		$this->pid = $_SESSION['pid'];
		if($this->pid != null){			
			$this->person = $this->person_information($this->pid,$this->person_form);
			$_SESSION['person']['pid'] = $this->pid;
		}

        switch($_GET['type']){
            case 'bd':
			    popuate_formArray($biography_form,$this->biographic_details);			
    			$this->biography_form = $biography_form;
                break;
            case 'rp':
    			$this->related_person = new Person();
	    		$this->related_person->LoadFromRecordNumber($this->biographic_details->related_person);
		    	$this->related_person->LoadRelationships();
			    $person_form = person_form('view');			
    			popuate_formArray($person_form, $this->related_person);
                $this->related_person_form = $person_form;
                break;
            default:
        }
				
		$this->biography_list($this->pid);
	}

	function act_supporting_doc()
	{
		include_once APPROOT.'inc/lib_form_util.inc';
        include_once APPROOT.'inc/lib_uuid.inc';
		$this->supporting_doc_form = $supporting_doc_form;

		$this->pid = (isset($_GET['pid']) && $_GET['pid'] != null) ? $_GET['pid'] : $_SESSION['pid'];
		if($this->pid != null){
		    $person_form = person_form('new');   
			$this->person_form = $person_form;
			$this->person = $this->person_information($this->pid,$this->person_form);
			$_SESSION['person']['pid'] = $this->pid;
		}

		if(isset($_POST['save'])){	
            $status = shn_form_validate($supporting_doc_form);
            if($status){
				$supporting_doc = new SupportingDocs();                
				$supporting_doc->doc_id = shn_create_uuid('document');							
				$supporting_doc->uri = 'www.respere.com';
				$supporting_doc->Save();
				
				$supporting_docmeta = new SupportingDocsMeta();				
				$supporting_docmeta->doc_id = $supporting_doc->doc_id;
                form_objects($supporting_doc_form, $supporting_docmeta);
				$supporting_docmeta->format = $this->findexts($_FILES['document']['name']);
                $supporting_docmeta->Save();				
            }
        }
	}

	function act_role_list()
	{
		$this->person_victim = "Victim";
		$this->person_victim_type = 'victim';
		$this->person_victim_pager = Browse::getPersonVictimRoleList($this->pid);
		$this->further_victim_msg = "Type of Act" . " > ";
		$this->victim_records = $this->person_victim_pager->get_page_data();

		$this->person_perpetrator = "Perpetrator";
		$this->person_perpetrator_type = 'perpetrator';
		$this->person_perpetrator_pager = Browse::getPersonPerpetratorRoleList($this->pid);
		$this->further_perpetrator_msg = "Degree of Involvement" . " > ";
		$this->perpetrator_records = $this->person_perpetrator_pager->get_page_data();

		$this->person_source = "Source";
		$this->person_source_type = 'source';
		$this->person_source_pager = Browse::getPersonSourceRoleList($this->pid);
		$this->further_source_msg = "Source Connection to Information" . " > ";
		$this->source_records = $this->person_source_pager->get_page_data();

		$this->person_intervening_party = "Intervening Party";
		$this->person_intervening_party_type = 'intervening_party';
		$this->person_intervening_party_pager = Browse::getPersonInterveningPartyRoleList($this->pid);
		$this->further_intervening_party_msg = "Type of Intervention" . " > ";
		$this->intervening_party_records = $this->person_intervening_party_pager->get_page_data();       
	}

	function act_audit_log(){
		include_once APPROOT.'inc/lib_form_util.inc';
        
		$this->pid = (isset($_GET['pid']) && $_GET['pid'] != null) ? $_GET['pid'] : $_SESSION['pid'];
		if($this->pid != null){
            $person = new Person();
            $person->LoadfromRecordNumber($this->pid);
            $this->person = $person;
            
            $logs = Browse::getAuditLogForPerson($this->pid);
        
            $this->logs = $logs;
		}	
	}    

	public function person_information($person_id,$person_form)
	{		
		$person = new Person();
		$person->LoadFromRecordNumber($person_id);    
		$person->LoadRelationships();		
		//popuate_formArray($person_form, $person);
		//$this->fields = shn_form_get_html_labels($person_form);
		return $person;
	}

	public function related_person_name($person_id)
	{		
		$person = new Person();
		$person->LoadFromRecordNumber($person_id);		
		return $person->person_name;
	}
		
	public function biography_list($person_id)
	{		
		$this->biographics = Browse::getBiographyList($person_id);
	}	

    public function act_print()
    {
        $this->person->LoadRelationships();
		$this->biographics = Browse::getBiographyList($this->person->person_record_number);
    }


    public function act_permissions()
    {
        $gacl_api = acl_get_gacl_api();
        $this->roles = acl_get_roles();
        if(isset($_POST['update'])){
            foreach($this->roles as $role_val => $role_name){
                if($role_val=='admin')continue;
                $acl_id = $gacl_api->search_acl('access','access', FALSE, FALSE, $role_name,'person', $this->person->person_record_number, FALSE, FALSE);
                if( isset($_POST['roles'])&&in_array($role_val, $_POST['roles'])){
                    if(count($acl_id)==0){
                        $aro_grp =  $gacl_api->get_group_id($role_val, $role_name, 'ARO');
                        $return = $gacl_api->add_acl( array( 'access'=>array('access')), null , array($aro_grp), array( 'person'=>array($this->person->person_record_number)), null , 1 );
                    }
                }
                else{
                    $gacl_api->del_acl($acl_id[0]);
                }
            }
            set_redirect_header('person','permissions');
        }

        if(isset($_POST['add_user']) && $_POST['add_user']!=''){
            $username = $_POST['add_user'];
            if(UserHelper::isUser($username)){
                $return = $gacl_api->add_acl( array( 'access'=>array('access')), array("users"=>array($username)) , null, array( 'person'=>array($this->person->person_record_number)), null , 1 );
            }else{
                shnMessageQueue::addError(_t('USERID_DOES_NOT_EXISTS_'));
            }
        }

        if(isset($_POST['remove_user'])){
            $acl_id = $gacl_api->search_acl('access','access', 'users', $_POST['remove_user'], FALSE ,'person', $this->person->person_record_number, FALSE, FALSE);
            if(isset($acl_id[0]))
                $gacl_api->del_acl($acl_id[0]);
        }

        //populate checkboxes
        $this->value =array();
        foreach($this->roles as $role_val => $role_name){
            $acl_id = $gacl_api->search_acl('access','access', FALSE, FALSE, $role_name,'person', $this->person->person_record_number, FALSE, FALSE);
            if(count($acl_id)> 0){
                $this->value[$role_val]=$role_val;
            }
        }

        //get users with permissions
        $this->users = acl_get_allowed_users($this->person->person_record_number, $type = 'person');
    }
    
    public function act_update_person_perms(){

        $persons = Browse::getPersonConf();
        //var_dump($persons); exit(0);
        
        foreach($persons as $person){
            acl_add_person($person['person_record_number']);
                //if event is marked as confidential limit it to this user and admin.
            if($person['confidentiality'] == 'y')
                acl_set_person_permissions($person['person_record_number']);        
        }

    }
    
}
