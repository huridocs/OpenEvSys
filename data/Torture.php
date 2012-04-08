<?php
/**
 * DataObject Tourture sub Entity of OpenEvSys.
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

class Torture extends DomainEntity{

    public $torture_record_number ;    

    //relasionships
    public $torture_intent;
    public $torture_medical_attention;   
    public $torture_statement_signed;       

    
    
    protected $mt =  array( 'intent' , 'medical_attention' , 'statements_signed' );
    
    protected $managementFields = array(  );
    

    private $pkey = array('torture_record_number');
    
    public function __construct($table = false, $pkeyarr=false, $db=false, $options=array()){
        parent::__construct('torture', $pkey ,$db , $options); 
        $table = 'torture';

        //$this->belongsTo( 'user_profile' , 'username' , 'username' ) ;
        //$this->hasMany( 'mlt_event_geographical_term' , 'record_number' ) ;          
    }


    
    
}


?>