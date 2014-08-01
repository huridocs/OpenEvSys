<?php

class SearchQuery {

    private $queryArray;

    public static function entitySort($a, $b) {
        $enord = array('event', 'act', 'intervention', 'arrest', 'torture', 'killing', 'destruction',
            'victim', 'involvement', 'perpetrator', 'information', 'source', 'chain_of_events',
            'intervening_party', 'address', 'biographic_details', 'supporting_docs_meta');
        $aorder = array_search($a->entity, $enord);
        $border = array_search($b->entity, $enord);
        return $border - $aorder;
    }

    public function initFromJson($queryJsonString) {

        $queryArray = json_decode($queryJsonString, true);

        if($queryArray === NULL) {
            throw new Exception("Error Processing Request", 1);
        }
        /*usort($queryArray['select'], array("SearchQuery","entitySort"));
        usort($queryArray['condition'], array("SearchQuery","entitySort"));*/
        $this->queryArray = $queryArray;
    }

    public function getQueryArray() {
        return $this->queryArray;
    }

    public function getSelectArray() {
        
        return usort($this->queryArray['select'], array("SearchQuery","entitySort"));
    }

    public function getConditionArray() {
        return usort($this->queryArray['condition'], array("SearchQuery","entitySort"));
    }

    public function getGroupbyArray() {
        return $this->queryArray['group_by'];
    }

    public function getSortArray() {
        return $this->queryArray['sort'];
    }

}