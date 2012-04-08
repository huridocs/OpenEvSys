<?php
/**
 * DataObject For Address Detail Entity of OpenEvSys.
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

class Address extends DomainEntity{
    
    public $address_record_number ;
    public $person;
    public $address_type;
   
    public $address;
    public $country;
    
    public $phone;
    public $cellular;
    public $fax;
    public $email;
    public $web;
    public $start_date;
    public $end_date;
    
    protected $mt =  array( );
    
    protected $managementFields = array( );
    

    private $pkey = array('address_record_number');
    
    public function __construct($table = false, $pkeyarr=false, $db=false, $options=array()){
        parent::__construct('address', $pkey ,$db , $options); 
        $table = 'address';

        
        //$this->belongsTo( 'user_profile' , 'username' , 'username' ) ;
        //$this->hasMany( 'mlt_event_geographical_term' , 'record_number' ) ;  
        
    }

	public function LoadfromRecordNumber($address_id){
        $this->Load("address_record_number='$address_id'");
    }

    public function LoadforPerson($person_record_number){
        return $this->Find("person='$person_record_number'");
        //$this->LoadManagementData();
    }

	public function getAddressforEntity($record_number){
        $records = $this->Find('person=' . "'" . $record_number . "'" );
        return $records; 
    }    
}
?>
