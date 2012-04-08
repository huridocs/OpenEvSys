<?php
/**
 * DataObject For system's userprofile
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

class UserProfile extends ADODB_Active_Record{
	

	protected $username;
	protected $first_name ;
	protected $last_name ;
	protected $organization;
	protected $designation ;
	protected $email ; 
	protected $address ;	

	private $pkey = array('username');
	
	public function __construct($table = false, $pkeyarr=false, $db=false, $options=array()){
		$table = 'user_profile';		
		parent::__construct($table, $pkey ,$db , $options);
		
	}
	
	//Getter Methods
	
    public function getUserName(){
        return $this->username;
    }
	
	public function getFirstName(){
		return $this->first_name;
	}
	
	public function getLastName(){
		return $this->last_name;
	}
	
	public function getOrganization(){
		return $this->organization;
	}
	
	public function getDesignation(){
		return $this->designation;		
	}
	
	public function getEmail(){
		return $this->email;		
	}
	
	public function getAddress(){
		return $this->address;		
	}
	
	//Setters
	
    public function setUserName($username){
        $this->username = $username;
    }
	
	public function setFirstName($first_name){
		$this->first_name = $first_name;
	}
	
	public function setLastName( $last_name ){
		$this->last_name = $last_name;
	}
	
	public function setOrganization($organization){
		$this->organization = $organization;
	}
	
	public function setDesignation($designation){
		$this->designation = $designation;
	}
	
	public function setEmail($email){
		$this->email = $email;
		
	}
	
	public function setAddress($address){
		$this->address = $address;
	}
	
	
	public function Save(){
		$ok = parent::Save();
		if (!$ok){
			$err = $this->ErrorMsg();
			throw new DbException($err);
		}
	}
	
	function Delete( $field , $value )  //also common in other Database classes. Should be put to commond place
	{
		$db = $this->DB(); if (!$db) return false;
		$table = $this->TableInfo();
		
		$where = "$field='" . $value . "'";
		$sql = 'DELETE FROM '.$this->_table.' WHERE '.$where;
		$ok = $db->Execute($sql);
		
		if (!$ok){
			$err = $this->ErrorMsg();
			throw new DbException($err);
		}else
		return true;
	}
	
	public function toString(){
		echo 'Username      - ' . $this->getUserName() . '<br />';
		echo 'First Name   - ' . $this->getFirstName()  . '<br />';
		echo 'Last Name    - ' . $this->getLastName() . '<br />';
		echo 'Organization - ' . $this->getOrganization() . '<br />';
		echo 'Designation  - ' . $this->getDesignation() . '<br />';
		echo 'Email		   - ' . $this->getEmail() . '<br />';
		echo 'Address	   - ' . $this->getAddress() . '<br />';
	}
}
?>