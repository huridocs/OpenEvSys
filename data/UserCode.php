<?php
/**
 * DataObject For Managing User Codes 
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

require_once ( APPROOT . 'data/DataObject.php' );



class UserCode extends ADODB_Active_Record{
    
    protected $username ;
    protected $action;
    protected $code;
    protected $expiry; 
    
    private $pkey = array('username','action');
    
    public function __construct($table = false, $pkeyarr=false, $db=false, $options=array()){
        
        $table = 'user_code';        
        parent::__construct($table, $pkey ,$db , $options);
        $this->belongsTo( 'user' , 'username' , 'username' ) ;  
        
    }
    
    public function getUserName(){
    	$this->username;    	
    }
    
    public function getAction(){
    	return $this->action;
    }
    
    public function getCode(){
    	$this->code;
    }
    
    public function getExpiry(){
    	$mysqldate = $this->expiry;
        $phpdate = strtotime( $mysqldate );
        return $phpdate;
    }
    
    ///SETTERS
    
    public function setUserName($username){
    	$this->username = $username;
    }
    
    public function setAction($action){
    	$this->action = $action;
    }
    
    public function setCode($code){
    	$this->code = $code;
    }
    
    public function setExpiry($expiry){
    	$this->expiry = $expiry;
    }
    

    
    
}

    
    



?>
