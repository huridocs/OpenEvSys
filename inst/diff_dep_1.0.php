<?php

define('APPROOT',realpath(dirname(__FILE__).'/../').'/');
$_SESSION['username']='admin';
require_once(APPROOT.'conf/sysconf.php');
global $global;
//load error and exception handlers
//require_once(APPROOT.'inc/handler_error.inc');
//require_once(APPROOT.'inc/handler_exception.inc');
require_once(APPROOT.'3rd/adodb5/adodb-active-record.inc.php');
require_once(APPROOT.'inc/lib_uuid.inc');
//load db handler
require_once(APPROOT.'inc/handler_db.inc');
ADOdb_Active_Record::SetDatabaseAdapter($global['db']);
//overide conf values from db
require_once(APPROOT.'inc/handler_config.inc');

include_once APPROOT.'inc/lib_entity_forms.inc';
//include_once APPROOT.'3rd/phpgacl/gacl.class.php';


include_once APPROOT.'inc/lib_form_util.inc';

include_once APPROOT.'inc/lib_files.inc';
include_once APPROOT.'inc/security/lib_acl.inc';
include_once APPROOT.'inc/security/handler_acl.inc';

class AutoLoadClass{
    public static function __autoload($class_name)
    {
        if (file_exists(APPROOT.'inc/'.$class_name.'.class.php') ){
            require_once(APPROOT.'inc/'.$class_name.'.class.php');
        }
        if(file_exists(APPROOT.'data/'.$class_name.'.php') ){
            require_once(APPROOT.'data/'.$class_name.'.php');
        }
        if(file_exists(APPROOT.'3rd/Zend/ '.$class_name.'.php') ){
            require_once(APPROOT.'3rd/Zend/ '.$class_name.'.php');
        }
        if(file_exists(APPROOT.'data/'.$class_name.'.php') ){
            require_once(APPROOT.'data/'.$class_name.'.php');
        }       
    }
}

spl_autoload_register(array('AutoLoadClass', '__autoload'));


//include(APPROOT.'3rd/phpgacl/gacl.class.php');
include(APPROOT.'3rd/phpgacl/gacl_api.class.php');
include(APPROOT.'inc/handler_db.inc');
#    	$gacl= new gacl(array('db_host'=> $conf['db_host'] , 'db_name'=> $conf['db_name'] , 'db_user'=> $conf['db_user'], 'db_password'=> $conf['db_pass'] , 'db_table_prefix'=>'gacl_' , 'db_type'=> 'mysql'  ));

#    	$gacl_api = new gacl_api(array('db_host'=> $conf['db_host'] , 'db_name'=> $conf['db_name'] , 'db_user'=> $conf['db_user'], 'db_password'=> $conf['db_pass'] , 'db_table_prefix'=>'gacl_' , 'db_type'=> 'mysql'  ));

$gacl = new gacl(array('db'=>$global['db'] , 'db_table_prefix'=>'gacl_'));
$gacl_api = new gacl_api(array('db'=>$global['db'] , 'db_table_prefix'=>'gacl_'));

$root_aro = $gacl_api->get_group_id('root' ,'OpenEvSysUser' , 'ARO');
//var_dump('root_aro' , $root_aro); exit(0);
$root_axo = $gacl_api->get_group_id('root' ,'root' , 'AXO');
//var_dump($root_axo);

$g_person = $gacl_api->add_group('person' , 'Person' , $root_axo , 'AXO');

$gacl_api->add_object_section('Person' , 'person' ,3, 0 , 'AXO');

$gacl_api->add_acl( 	array( 'access'=>array('access') ) ,
                        null ,
                        array( $root_aro ),
                        null,
                        array( $g_person  )
                );
                


        $persons = Browse::getPersonConf();
        //var_dump($persons); exit(0);
        
        foreach($persons as $person){
            acl_add_person($person['person_record_number']);
                //if event is marked as confidential limit it to this user and admin.
            if($person['confidentiality'] == 'y')
                acl_set_person_permissions($person['person_record_number']);        
        }
        
        
