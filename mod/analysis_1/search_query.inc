<?php
class SearchQuery{
	private $queryArray;
	
	public function initFromJson($queryJsonString){
		
		$queryArray = json_decode($queryJsonString,true);
		
		$this->queryArray = $queryArray;
	}
	
	public function getQueryArray(){
		return $this->queryArray;
	}
	
	public function getSelectArray(){
		return $this->queryArray['select'];
	}
	
	public function getConditionArray(){
		return $this->queryArray['condition'];
	}
	
	public function getGroupbyArray(){
		return $this->queryArray['group_by'];
	}
	
	public function getSortArray(){
		return $this->queryArray['sort'];
	}
	
	
}