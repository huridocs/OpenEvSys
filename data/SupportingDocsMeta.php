<?php
class SupportingDocsMeta extends DomainEntity{

/**
 * DataObject For Supporting Doc Meta 
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

public $doc_id;

public $title; 
public $creator;
public $description;
public $datecreated;
public $datesubmitted;
public $format;
public $type;
public $language;
public $subject;
public $url;
  
    


    private $pkey = array('doc_id');
    
    public function __construct($table = false, $pkeyarr=false, $db=false, $options=array()){
        parent::__construct('supporting_docs_meta', $pkey ,$db , $options); 
        $table = 'supporting_docs_meta';
                
        $this->belongsTo( 'supporting_docs' , 'doc_id' , 'doc_id' ) ;
        
    }
    /*
    public function LoadfromRecordNumber($doc_id){
        $this->Load("doc_id='$doc_id'");
    }
        
    public function getAll(){
        $events = $this->Find('');
        return $events;
    }
    
	protected function getSaveType(){
        if($this->_saved)
        return 'update';
        else
        return 'create';
    }


	public function Save(){
    	$saveType = $this->getSaveType() ;
        parent::Save();        
        Log::saveLogDetails($this->_table , $this->doc_id ,  $saveType );
    }*/

}
