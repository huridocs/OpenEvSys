<?php

/**
 * Functions for managing Microthesauri fields.
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

class MtFieldWrapper {
    
    
    public static function getObject($entity_name,  $field_name){
    
        $entity_name = trim($entity_name);
        $field_name = trim($field_name);
        $table_name = 'mlt_' . $entity_name . '_' . $field_name ;

        $mtField = new MtField($table_name);        
        return $mtField;
    }
    
    public static function getMTTermsforEntity( $fieldName, $entityName , &$entityObject , $entityID ){
        $mt_mlt_terms =  MtFieldWrapper::getObject($entityName , $fieldName);        
        $entityObject->$fieldName = $mt_mlt_terms->getCodesforEntity($entityID);        
              
    }
    
    public static function getMTObject($fieldName, $entityName){
        return MtFieldWrapper::getObject($entityName , $fieldName);
    }
    
    public static function setMTTermsforEntity($fieldName, $entityName , $entityID , $multiValueTermArray){
        MtFieldWrapper::getObject($entityName , $fieldName )->DeleteCodesForEntity($entityID);
        foreach($multiValueTermArray as $term){
            $term->Save();
        }
    }
    
    public static function getMTList($fieldName){
        $mt_terms = new MtTerms();
        $mt_values = $mt_terms->getAllHuriTerms($fieldName);
        return $mt_values;
    }

    
    
}
?>
