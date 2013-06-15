<?php

/**
 * Functions for managing user fields.
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

class UserFieldWrapper {
        
    
    public static function getObject($entity_name,  $field_name){
    
        $entity_name = trim($entity_name);
        $field_name = trim($field_name);
        $table_name = 'mlt_' . $entity_name . '_' . $field_name ;

        $userField = new UserField($table_name);        
        return $userField;
    }
    
    public static function getUserTermsforEntity( $fieldName, $entityName , &$entityObject , $entityID ){
        $userField =  UserFieldWrapper::getObject($entityName , $fieldName);        
        $entityObject->$fieldName = $userField->getUsersforEntity($entityID);        
              
    }
    
    public static function getUserObject($fieldName, $entityName){
        return UserFieldWrapper::getObject($entityName , $fieldName);
    }
    
    public static function setUserTermsforEntity($fieldName, $entityName , $entityID , $multiValueTermArray){
        UserFieldWrapper::getObject($entityName , $fieldName )->DeleteUsersForEntity($entityID);
        foreach($multiValueTermArray as $term){
            $term->Save();
        }
    }
    

    
    
}
?>
