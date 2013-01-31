<?php
/**
 * DataObject For Supporting Documents of OpenEvSys.
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

class SupportingDocs extends ADODB_Active_Record{

public $doc_id;
public $uri;

//public $supporting_docs_meta;
/*
public $creator;
public $description;
public $dateCreated;
public $dateSubmitted;
public $format;
public $language;
public $subject;
  */  
    


    private $pkey = array('doc_id');
    
    public function __construct($table = false, $pkeyarr=false, $db=false, $options=array()){
        parent::__construct('supporting_docs', $pkey ,$db , $options); 
        $table = 'supporting_docs';
                
        //$this->belongsTo( 'supporting_docs_meta' , 'doc_id' , 'doc_id' ) ;
        
    }
    
 
    public function getMeta(){
        return $this->supporting_docs_meta;
    }
    
    public function setMeta($supp_doc_meta){
        $this->supporting_docs_meta = $supp_doc_meta;
    }
    
    public function LoadfromRecordNumber($doc_id){
        $this->Load("doc_id='$doc_id'");
    }
    

    public function Save(){
    	$saveType = $this->getSaveType() ;
        parent::Save();
         
        if( $this->supporting_docs_meta != null){
            $this->supporting_docs_meta->Save();
            Log::saveLogDetails($this->_table , $this->doc_id,  $saveType );
        }
        
    }
        
    public function getAll(){        
        return $this->Find('');
    }


	protected function getSaveType(){
        if($this->_saved)
        return 'update';
        else
        return 'create';
    }

}
