<?php
/**
 * DataObject For Supporting Doc Links of OpenEvSys.
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

class SupportingDocsLinks extends ADODB_Active_Record{

public $doc_id;
public $entity_id;

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
    
 
    public function getLinks(){
        return $this->supporting_docs_links;
    }
    
    public function setLinks($supp_doc_links){
        $this->supporting_docs_links = $supp_doc_links;
    }
    
    public function LoadfromRecordNumber($doc_id){
        $this->Load("doc_id='$doc_id'");
    }
    

    public function Save(){
        parent::Save();

        if( $this->supporting_docs_links != null){
            $this->supporting_docs_links->Save();
        }
        
    }
        
    public function getAll(){        
        return $this->Find('');
    }


    

}
