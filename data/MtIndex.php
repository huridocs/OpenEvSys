<?php
/**
 * DataObject For obtaining all micothesauri indexes of OpenEvSys.
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

class MtIndex extends ADODB_Active_Record{
    
    public $no;
    public $term;
        
    private $pkey = array('no') ;
     
    
    public function __construct($table = false, $pkeyarr=false, $db=false, $options=array()){
        $table = 'mt_index';
        parent::__construct($table, $pkey ,$db , $options);   
    }
    
    public function getTermforCode($code){
        $ok = $this->Load("no='$code'");
        if(!$ok )
            return null;
        else
            return trim($this->term);
    }
    public function getCodeforTerm($term){
        $ok = $this->Load("term='$term'");
        if(!$ok )
            return null;
        else
            return $this->no;
    }
    
}
?>