<?php
/**
 * DataObject For supporting documents of OpenEvSys.
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

class SupportingDocEntity extends ADODB_Active_Record{
    public $record_number;
    public $doc_id;
    public $linked_by;
    public $linked_date;
    
    //protected $supporting_docs;
    //protected $supporting_docs_meta;    
    
    protected $pkey = array('doc_id' , 'record_number');
    
    
    public function __construct($table = false, $pkeyarr=false, $db=false, $options=array() ){
        parent::__construct($table, $pkey ,$db , $options);

        $this->belongsTo( 'supporting_docs' , 'doc_id' , 'doc_id' ) ;
        $this->belongsTo( 'supporting_docs_meta' , 'doc_id' , 'doc_id' ) ;
        
        //$this->_table = $table_name;// 'mlt_event_geographical_term' ;// $table_name;

        
   }
    
   public function getURI(){
       return $this->supporting_docs->uri;
   }
   
   public function getDocMeta(){
       return $this->supporting_docs_meta;
   }

    public function getDocsforEntity($record_number){
        global $global;
        $record_number = $global['db']->qstr($record_number);
        $records = $this->Find("record_number= $record_number " );        

        return $records; 
    }
    
    public static function generateTableName($entity){
        return $entity . '_doc';
    }
    
    protected function getSaveType(){
        if($this->_saved)
        return 'update';
        else
        return 'linked';
    }


	public function Save(){
    	$saveType = $this->getSaveType() ;
        parent::Save();        
        Log::saveLogDetails($this->_table , $this->doc_id ,  $saveType );
    }
    
    
}
?>
