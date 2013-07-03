<?php
/**
 * DataObject For Person Entity of OpenEvSys.
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
 
 * @author	Nilushan Silva <nilushan@respere.com>
 * 
 * @package	OpenEvsys
 * @subpackage	DataModel
 *
 */

//require_once ( APPROOT . 'data/DataObject.php' );

class Person extends DomainEntity{
   /* 
    public $person_record_number;
    public $counting_unit;
    public $person_name;
    public $other_names;
    public $confidentiality;
    public $date_of_birth;
    public $place_of_birth;
    public $locality_of_birth;
    public $date_deceased;
    public $sex;
    public $sexual_orientation;
    public $identification_documents;
    public $civil_status;
    public $dependants;
    public $formal_education;
    public $other_training;
    public $health;
    public $medical_records;
    public $group_description;
    public $number_of_persons_in_group;
    public $religion;
    public $remarks;
    public $reliability_as_source;
    public $reliability_as_intervening_party;
    public $deceased;
    public $date_of_birth_type;
    public $date_deceased_type;
    
    public $occupation; 
    public $local_term_for_occupation;
    public $physical_description; 
    public $citizenship;     
    public $ethnic_background;
    public $other_background; 
    public $general_characteristics; 
    public $language    ;
    public $local_language; 
    public $national_origin;
    

    //Management Data
    public $date_received;
    public $date_of_entry;
    public $entered_by ;
    public $project_title;
    public $comments ;
    public $record_grouping;
    public $date_updated ;
    public $updated_by ;
    public $monitoring_status;
    */
    public $Management;
    public $picture;

	public $person_addresses = array();
    
    protected $managementFields = array( 'date_received', 'date_of_entry',
'entered_by' , 'project_title', 'comments', 'record_grouping',
'date_updated' , 'updated_by' , 'monitoring_status');
    
    
/*
    protected $mt =  array('occupation' , 'local_term_for_occupation' ,
                            'physical_description','citizenship','ethnic_background' , 'other_background' , 'general_characteristics',
                            'language' , 'local_language','national_origin'
                             );
 */
  
                                
    protected $supporting_doc = true;
    protected $isAddress = true;
    protected $picture_doc = true;
    protected $supporting_geometry = true;

    private $pkey = array('person_record_number');
    
    public function __construct($table = false, $pkeyarr=false, $db=false, $options=array()){
        parent::__construct('person', $pkey ,$db , $options); 
        $table = 'person';
    }
    
	public function LoadPicture(){		
    	if($this->picture_doc == true){	       	
	       	$pictureDoc = new SupportingDocEntity(SupportingDocEntity::generateTableName('picture') );
	       	$picture_data = $pictureDoc->getDocsforEntity($this->person_record_number);
	       	$this->picture = $picture_data[0]->doc_id;	       		       	
    	}
    }

	public function LoadAddresses(){		
    	if($this->isAddress == true){
			$address = new Address();
       		$addressEntityObjects = $address->getAddressforEntity($this->person_record_number);
       		foreach($addressEntityObjects as $add){				
       			$this->person_addresses[] = $add->address_record_number;
       		}	       	
    	}		
    }

	private function DeleteAddresses(){
        $address = new Address();
       	$addressEntityObjects = $address->getAddressforEntity($this->person_record_number);
       	       	 
       	foreach($addressEntityObjects as $add){
       		$add->Delete('person' , $this->person_record_number);
       	}
    }

	public function SaveAddresses($addresses){
    	if($this->isAddress == true){
	    	$this->DeleteAddresses();
	    	if( is_array($addresses)){				
	    		foreach($addresses as $key=>$add){					
					$address = new Address();		
					$address->person = $this->person_record_number;
					$address->address_record_number = shn_create_uuid('address');;
					$address->address_type = $addresses[$key]['address_type'];
					$address->address = $addresses[$key]['address'];
					$address->country = $addresses[$key]['country'];
					$address->phone = $addresses[$key]['phone'];
					$address->cellular = $addresses[$key]['cellular'];
					$address->fax = $addresses[$key]['fax'];
					$address->email = $addresses[$key]['email'];
					$address->web = $addresses[$key]['web'];
					$address->start_date = $addresses[$key]['start_date'];
					$address->end_date = $addresses[$key]['end_date'];
					$address->Save();					
	    		}
	    	}
    	}		
    }
    
	public function SavePicture(){
            
    	if($this->picture_doc == true && is_uploaded_file($_FILES['picture']['tmp_name'])){
            $type = null;
            $uri = shn_files_store('picture',null,$type);
            
            if($uri == null){
            	$uri = '';
            }
			
            $document_form = document_form('new');
            
            $supporting_docs = new SupportingDocs();
            $supporting_docs_meta = new SupportingDocsMeta();
            
            $pictureDoc = new SupportingDocEntity(SupportingDocEntity::generateTableName('picture') );			
	    	$pictureDoc->record_number = $this->person_record_number;
            
            if($_POST['picture_id'] != null){
            	$picture_id = $_POST['picture_id'];            	
            	if($uri != ''){            		
	            	$supporting_docs->doc_id = $picture_id;            
		            $supporting_docs->uri = $uri; 
					$supporting_docs->_saved = true;
		            form_objects($document_form, $supporting_docs);
					
		            //$supporting_docs->Delete();            
		            $supporting_docs->Save();            
		            		            
		            form_objects($document_form, $supporting_docs_meta);
		            $supporting_docs_meta->title = "Picture";
		            $supporting_docs_meta->doc_id = $picture_id;
		            $supporting_docs_meta->format = $type;  
					$supporting_docs_meta->_saved = true;
		            $supporting_docs_meta->Save();		    		
			    		    	
			    	$pictureDoc->doc_id = $picture_id;
			    	$pictureDoc->_saved = true;
			    	$pictureDoc->linked_by = $_SESSION['username'];    			
			    	$pictureDoc->Save();
            	}
            }
            else{
            	$picture_id = shn_create_uuid('picture');
            	
            	$supporting_docs->doc_id = $picture_id;            
	            $supporting_docs->uri = $uri; 
	            form_objects($document_form, $supporting_docs);            
	            $supporting_docs->Save();
	            
	            form_objects($document_form, $supporting_docs_meta);
	            $supporting_docs_meta->title = "Picture";
	            $supporting_docs_meta->doc_id = $picture_id;
	            $supporting_docs_meta->format = $type;            
	            $supporting_docs_meta->Save();	    		
		    		    	
		    	$pictureDoc->doc_id = $picture_id;
		    	$pictureDoc->linked_by = $_SESSION['username'];    			
		    	$pictureDoc->Save();
            }      
    	}
    }

   	public function SaveAll(){
        global $global;
		$saveType = $this->getSaveType();
   		
   		//var_dump('saving event type' ,  $saveType);
		if($saveType == 'create'){
            acl_add_person($this->person_record_number);
            //if event is marked as confidential limit it to this user and admin.
            if($this->confidentiality == 'y')
                acl_set_person_permissions($this->person_record_number);
		}

        if($saveType == 'update'){
            //if event is marked as confidential limit it to this user and admin.
            if($this->confidentiality == 'y')
                acl_set_person_permissions($this->person_record_number);
            if($this->confidentiality == 'n'){
                acl_unset_person_permissions($this->person_record_number);
            }
        }
		parent::SaveAll();
   	}

    public function DeleteFromRecordNumber($recordNumber){
        $return = parent::DeleteFromRecordNumber($recordNumber);
        
        if($return){
            //remove gacl objects for this perticuler event
           acl_delete_person($recordNumber); 
        }
    }
}
