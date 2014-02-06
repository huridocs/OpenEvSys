<?php

/**
 * DataObject implementing the common functionalities of all Entities. 
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

class DomainEntity extends ADODB_Active_Record {

    //Management

    public $date_received;
    public $date_of_entry;
    public $entered_by;
    public $project_title;
    public $comments;
    public $record_grouping;
    public $date_updated;
    public $updated_by;
    public $monitoring_status;
    public $supporting_documents = array();
    public $geometries = array();
    protected $managementFields = array('date_received', 'project_title', 'comments', 'record_grouping',
        'monitoring_status', 'entered_by', 'date_of_entry', 'updated_by', 'date_updated');
    protected $mt = array();
    protected $uf = array();
    private $entity;
    private $keyName;

    public function __construct($table = false, $pkeyarr = false, $db = false, $options = array()) {
        parent::__construct($table, $pkey, $db, $options);
        $this->entity = $table;
        $this->keyName = get_primary_key($this->entity);//$this->entity . '_record_number';
        $this->loadMTNames();
        $this->loadUserFieldNames();
    }

    private function loadMTNames() {
        $entityFields = Browse::getEntityFields($this->getEntityType());
        foreach ($entityFields as $entityField) {
            $mlt = ( (trim($entityField['is_repeat']) == 'Y' || trim($entityField['is_repeat']) == 'y' ) ? true : false);
            if ($mlt && $entityField['field_type'] != 'user_select') {
                $this->mt[] = $entityField['field_name'];
            }
        }
        $this->mt = array_unique($this->mt);
    }
    private function loadUserFieldNames() {
        $entityFields = Browse::getEntityFields($this->getEntityType());
        foreach ($entityFields as $entityField) {
            $mlt = ( (trim($entityField['is_repeat']) == 'Y' || trim($entityField['is_repeat']) == 'y' ) ? true : false);
            if ($mlt && $entityField['field_type'] == 'user_select') {
                $this->uf[] = $entityField['field_name'];
            }
        }
        $this->uf = array_unique($this->uf);
    }

    public function getEntityType() {
        return $this->entity;
    }

    public function SaveAll() {

        $saveType = $this->getSaveType();

        acl_is_entity_allowed($this->entity, $saveType, $this->{$this->keyName});

        $this->Save();
        $this->SaveManagementData();
      
        foreach ($this->mt as $mtField) {
            if (sizeof($this->$mtField) > 0) {
               
                MtFieldWrapper::setMTTermsforEntity($mtField, $this->entity, $this->{$this->keyName}, $this->$mtField);
            }
        }
        foreach ($this->uf as $userField) {
            if (sizeof($this->$userField) > 0) {
                UserFieldWrapper::setUserTermsforEntity($userField, $this->entity, $this->{$this->keyName}, $this->$userField);
            }
        }
        $this->saveClarifyingNotes();
        $this->SaveDocs();
        $this->SaveGeometries();

        Log::saveLogDetails($this->_table, $this->{$this->keyName}, $saveType);
    }

    public function LoadfromRecordNumber($record_number) {
        global $global;
        if ($record_number != null) {
            acl_is_entity_allowed($this->entity, 'read', $record_number);
            $record_number = $global['db']->qstr($record_number);
            $this->Load("$this->keyName=$record_number");
            $this->LoadManagementData();
        }
        //var_dump($this);
    }

    public function LoadRelationships() {
        foreach ($this->mt as $mtField) {
            MtFieldWrapper::getMTTermsforEntity($mtField, $this->entity, $this, $this->{$this->keyName});
        }
        foreach ($this->uf as $userField) {
            UserFieldWrapper::getUserTermsforEntity($userField, $this->entity, $this, $this->{$this->keyName});
        }
        $this->LoadDocs();
        $this->LoadGeometries();
    }

    public function getAll() {
        $entities = $this->Find('');
        return $entities;
    }

    public function FilterFromWhereClause($whereClause) {
        $entities = $this->Find($whereClause);
        return $entities;
    }

    public function LoadManagementData() {
        $this->Management = new Management();
        $ok = $this->Management->LoadfromEntityId($this->_table, $this->{$this->keyName});

        foreach ($this->managementFields as $mngField) {
            $this->$mngField = $this->Management->$mngField;
        }
    }

    public function getManagementData() {
        return $this->managementFields;
    }

    public function ClearManagementData() {
        foreach ($this->managementFields as $mngField) {
            $this->$mngField = null;
        }
        $this->Management = null;
        $this->_saved = false;
    }

    private function SetManagementData() {
        if ($this->Management == null) {
            $this->Management = new Management();
        }
        $this->Management->LoadfromEntityId($this->_table, $this->{$this->keyName});
        if ($this->Management->entity_type == null) {
            $new = true;
        }
        $this->Management->entity_type = $this->_table;
        $this->Management->entity_id = $this->{$this->keyName};
        foreach ($this->managementFields as $mngField) {
            $this->Management->$mngField = $this->$mngField;
        }
        if ($new == true) {
            //var_dump('new');
            $this->Management->entered_by = $_SESSION['username'];
            $this->Management->date_of_entry = date('Y-m-d H:i:s', time());
        } else {
            //var_dump('old');
            $this->Management->updated_by = $_SESSION['username'];
            $this->Management->date_updated = date('Y-m-d H:i:s', time());
        }
    }

    public function SaveManagementData() {

        $this->SetManagementData();
        $this->Management->Save();
    }

    private function saveClarifyingNotes() {
        ClariNotes::deleteExistingNotes($this->entity, $this->{$this->keyName});

        foreach ($this->clari_notes as $clari_note) {
            $clari_note->Save();
        }
    }

    public function getMtObject($field, $vocab_number) {
        $mtTerm = MtFieldWrapper::getMTObject($field, $this->_table);
        $mtTerm->vocab_number = $vocab_number;
        $mtTerm->record_number = $this->{$this->keyName};
        return $mtTerm;
    }

    protected function getSaveType() {
        if ($this->_saved)
            return 'update';
        else
            return 'create';
    }

    public function Delete($field, $value) {
        //check permissions
        acl_is_entity_allowed($this->entity, 'delete', $value);

        $db = $this->DB();
        if (!$db)
            return false;
        $table = $this->TableInfo();

        $where = "$field='" . $value . "'";
        $sql = 'DELETE FROM ' . $this->_table . ' WHERE ' . $where;
        $ok = $db->Execute($sql);

        $sql2 = "DELETE FROM management WHERE entity_type = '" . $this->_table . "' AND entity_id = '" . $this->{$this->keyName} . "'";
        $ok2 = $db->Execute($sql2);

        if (!$ok || !$ok2) {
            $err = $this->ErrorMsg();
            //throw new DbException($err);
            echo $err;
            return false;
        } else {
            Log::saveLogDetails($this->_table, $value, 'delete');
            return true;
        }
    }

    public function DeleteFromRecordNumber($recordNumber) {
        return $this->Delete($this->keyName, $recordNumber);
    }

    //----------------------------------------------------


    public function LoadDocs() {
        $this->supporting_documents = array();
        if ($this->supporting_doc == true) {
            $supportingDoc = new SupportingDocEntity(SupportingDocEntity::generateTableName($this->entity));
            $suppDocEntityObjects = $supportingDoc->getDocsforEntity($this->{$this->keyName});

            foreach ($suppDocEntityObjects as $doc) {
                $this->supporting_documents[] = $doc->doc_id;
            }
        }
    }

    private function DeleteDocs() {
        $supportingDoc = new SupportingDocEntity(SupportingDocEntity::generateTableName($this->entity));
        $suppDocEntityObjects = $supportingDoc->getDocsforEntity($this->{$this->keyName});

        foreach ($suppDocEntityObjects as $doc) {
            $doc->Delete();
        }
    }

    public function SaveDocs() {
        if ($this->supporting_doc == true) {

            $this->DeleteDocs();
            if (is_array($this->supporting_documents)) {
                $this->supporting_documents = array_unique($this->supporting_documents);
                foreach ($this->supporting_documents as $doc) {
                    $supportingDocEntity = new SupportingDocEntity(SupportingDocEntity::generateTableName($this->entity));
                    $supportingDocEntity->record_number = $this->{$this->keyName};
                    $supportingDocEntity->doc_id = $doc;
                    $supportingDocEntity->linked_by = $_SESSION['username'];

                    $supportingDocEntity->Save();
                }
            }
        }
    }

    public function LoadGeometries() {
        $this->geometries = array();
        if ($this->supporting_geometry == true) {
            $geometry = new Geometry($this->entity);
            $geometryObjects = $geometry->getFromEntityId($this->{$this->keyName});
            $this->geometries = $geometryObjects;
        }
    }

    private function DeleteGeometries() {
        $geometry = new Geometry($this->entity);
        $geometryObjects = $geometry->DeleteByEntityId($this->{$this->keyName});
    }

    public function SaveGeometries() {
        if ($this->supporting_geometry == true) {

            $this->DeleteGeometries();
            $this->loadGeometriesFromPost();

            if (is_array($this->geometries)) {
                //$this->geometries = array_unique($this->geometries);
                foreach ($this->geometries as $key => $geometries) {
                    $geometries = array_unique($geometries);

                    foreach ($geometries as $geometry) {
                        $geometryObject = new Geometry($this->entity);
                        $geometryObject->geometry_record_number = shn_create_uuid('mlt_geometry');

                        $geometryObject->entity_id = $this->{$this->keyName};
                        //$field = Browse::getFieldByName($this->entity,$key);
                        $geometryObject->field_name = $key;
                        $geometryObject->geometry = $geometry;

                        $geometryObject->Save();
                    }
                }
            }
        }
    }

    public function loadGeometriesFromPost() {
        global $global;
        if ($this->supporting_geometry) {
            $geometriesA = array();
            foreach ($_POST as $key => $val) {
                if (strpos($key, "_geometry")) {

                    if ($_POST[$key]) {
                        $geometries = $_POST[$key];
                    }
                    if (!is_array($geometries)) {
                        return;
                    }

                    $geometriesJson = array();
                    foreach ($geometries as $item) {
                        if (!empty($item)) {
                            //Decode JSON
                            $item = json_decode($item);
                            //++ TODO - validate geometry
                            $geometry = (isset($item->geometry)) ? $global['db']->qstr($item->geometry) : "";
                            if ($geometry && strpos( $geometry,"NaN") === false) {
                                $geometriesJson[] = $item->geometry;
                            }
                        }
                    }

                    if ($geometriesJson) {
                        $fieldname = explode("_geometry", $key);
                        $geometriesA[$fieldname[0]] = $geometriesJson;
                    }
                }
            }
            if ($geometriesA) {
                $this->geometries = $geometriesA;
            }
        }
    }

}

?>
