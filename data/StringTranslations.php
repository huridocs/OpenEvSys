<?php
/**
 * DataObject For TranslateStrings of OpenEvSys.
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
 
 */

class StringTranslations extends ADODB_Active_Record{
    
     
    protected $pkey = array('context','name','language' );
    
    
    public function __construct($table = false, $pkeyarr=false, $db=false, $options=array() ){
         parent::__construct('string_translations', $pkey ,$db , $options);
    }
   
   
    public static function getMtTranslations($mt = null, $lang = null) {
        global $global,$conf;
       

        $sql = "SELECT 
                    language,
  context,
  name,
  value,
  status,
  no,term
                FROM string_translations st 
                LEFT JOIN mt_index as mt  ON st.name = CONCAT('mt-',  mt.no, '-label' )  
                  ";

        $where = array();
        if (is_numeric($mt)) {
            $where[] = "no='".(int)$mt."'";
        }
        if ($lang) {
            $where[] = "language='".$lang."'";
        }
        if($where){
            $sql .= " where ".implode(" and ",$where);
        }
        
       /* if ($langorder && is_array($langorder)) {
            $sql .= " ORDER BY FIELD(l.language,'" . implode("','", $langorder) . "')";
        }*/
         $res = $global['db']->GetAll($sql);
      
        
        $results = array();
        foreach ($res as $record) {
            $results[$record["no"]][$record["language"]] = $record;          
            
        }
        
        return $results;
    }

}
