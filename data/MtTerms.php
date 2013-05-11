<?php
/**
 * DataObject For Microthesauri Terms of OpenEvSys.
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

class MtTerms extends ADODB_Active_Record{
    
    public $vocab_number;
    public $english;
    public $label;
    
    protected $pkey = array('vocab_number' );
    
    
    public function __construct($table = false, $pkeyarr=false, $db=false, $options=array() ){
         parent::__construct('mt_vocab', $pkey ,$db , $options);
    }
   
   
    public function getAllHuriTerms($fieldName){
       
        if(  is_numeric($fieldName) ){
            $listCode = (int)$fieldName;
            $fieldName = $this->getFieldNameforListCode($listCode);
        }else{
            $listCode = $this->getListCodeforMTField($fieldName);
        }
        
        if($listCode != null || trim($listCode)!=''){
        	//var_dump($listCode);
            $mtTable = "mt_{$listCode}_{$fieldName}";        
            $consistancyJoin = "INNER JOIN $mtTable ON m.vocab_number = $mtTable.vocab_number";
        }
        
        global $conf;
        $sql = "SELECT m.vocab_number , IFNULL(l.msgstr , english) as 'label',
                term_order,parent_vocab_number ,term_level 
                FROM mt_vocab m 
                LEFT JOIN mt_vocab_l10n l ON ( l.msgid = m.vocab_number AND l.locale = '{$conf['locale']}' )
                $consistancyJoin
                WHERE TRIM(list_code)='$listCode' AND visible = 'y' ORDER BY term_order";
        //echo $sql;
        $browse = new Browse();
        $res = $browse->ExecuteQuery($sql);
        //print_r($res);
        return $res;        
    }

    public function Save(){
        
    }

    private function getListCodeforMTField($fieldName){
        $db = $this->DB(); if (!$db) return false;        
        $save = $db->SetFetchMode(ADODB_FETCH_NUM);
        $qry = "select no from mt_index WHERE trim(term)='$fieldName'";
        $row = $db->GetRow($qry);       
        //$db->SetFetchMode($save);        
        //return $this->Set($row);
        if(sizeof($row) > 0)
            return $row[0];
            else
            return 0;
    }
    
    private function getFieldNameforListCode($listCode){
        $db = $this->DB(); if (!$db) return false;        
        $save = $db->SetFetchMode(ADODB_FETCH_NUM);
        $qry = "select term from mt_index WHERE trim(no)='$listCode'";
        $row = $db->GetRow($qry);
        //$db->SetFetchMode($save);        
        //return $this->Set($row);
        if(sizeof($row) > 0)
            return $row[0];
            else
            return 0;
    }
    
    public function DeleteFromRecordNumber($mt_select,$vocab_number){    	
        $db = $this->DB(); if (!$db) return false;        
        $save = $db->SetFetchMode(ADODB_FETCH_NUM);
        $sqlarray = array();
        if(!is_array($vocab_number)){
            $vocab_number = array($vocab_number);
        }
        $sqlarray[] = "DELETE from mt_vocab WHERE vocab_number in ('".implode("','",$vocab_number)."')";
        $sqlarray[] = "DELETE from mt_vocab_l10n WHERE msgid in ('".implode("','",$vocab_number)."')";
        
        $mtIndex = new MtIndex();
        $mt_table = 'mt_' . $mt_select . '_' . $mtIndex->getTermforCode($mt_select);
        $sqlarray[] = "DELETE from $mt_table WHERE vocab_number in ('".implode("','",$vocab_number)."')";
        
       
        foreach($sqlarray as $sql){
            $db->Execute($sql);       
        }
    }

    public function LoadfromVocabNumber($vocab_number){
        $this->Load("vocab_number = '$vocab_number'");
        $db = $this->DB(); if (!$db) return false;
        global $conf;
        $sql = "SELECT IFNULL(l.msgstr , m.english) as 'label' FROM mt_vocab m 
                LEFT JOIN mt_vocab_l10n l ON ( l.msgid = m.vocab_number AND l.locale = '{$conf['locale']}' )
                WHERE m.vocab_number = '{$this->vocab_number}'";
        $this->label = $db->GetOne($sql);
    }
}
