<?php

global $conf;
global $global;

define('APPROOT',realpath(dirname(__FILE__).'/../').'/');

require_once(APPROOT.'conf/sysconf.php');

include(APPROOT.'3rd/phpgacl/gacl.class.php');
include(APPROOT.'3rd/phpgacl/gacl_api.class.php');
include(APPROOT.'inc/handler_db.inc');
#    	$gacl= new gacl(array('db_host'=> $conf['db_host'] , 'db_name'=> $conf['db_name'] , 'db_user'=> $conf['db_user'], 'db_password'=> $conf['db_pass'] , 'db_table_prefix'=>'gacl_' , 'db_type'=> 'mysql'  ));

#    	$gacl_api = new gacl_api(array('db_host'=> $conf['db_host'] , 'db_name'=> $conf['db_name'] , 'db_user'=> $conf['db_user'], 'db_password'=> $conf['db_pass'] , 'db_table_prefix'=>'gacl_' , 'db_type'=> 'mysql'  ));

$gacl = new gacl(array('db'=>$global['db'] , 'db_table_prefix'=>'gacl_'));
$gacl_api = new gacl_api(array('db'=>$global['db'] , 'db_table_prefix'=>'gacl_'));

$gacl_api->clear_database();

// ARO //
$root_aro =   $gacl_api->add_group(  'root' ,'OpenEvSysUser' , 0 , 'ARO');
$user_group = $gacl_api->add_group( 'users' , 'Users' , $root_aro , 'ARO');
    $g_user_admin =	$gacl_api->add_group('admin' , 'Admin' , $user_group , ' ARO');
    $g_user_analyst = $gacl_api->add_group('analyst' , 'Analyst' , $user_group , ' ARO');
    $g_user_data_entry = $gacl_api->add_group('data_entry' , 'Data Entry' , $user_group , ' ARO');

$ws_group =   $gacl_api->add_group( 'ws' , 'WS' , $root_aro , 'ARO');

// ARO sections

$gacl_api->add_object_section('Users' , 'users' ,1, 0 , 'ARO');

//ARO values

$gacl_api->add_object('users', 'Admin' , 'admin' ,1,0,'ARO');



//ACO //

//ACO sections

$gacl_api->add_object_section('CRUD' , 'crud' ,1, 0 , 'ACO');
$gacl_api->add_object_section('Access' , 'access' ,1, 0 , 'ACO');

//ACO values

$gacl_api->add_object('access', 'Access' , 'access' ,1,0,'ACO');
        
$gacl_api->add_object('crud', 'Create' , 'create' ,1,0,'ACO');
$gacl_api->add_object('crud', 'Read' , 'read' ,2 ,0,'ACO');
$gacl_api->add_object('crud', 'Update' , 'update' ,3,0,'ACO');
$gacl_api->add_object('crud', 'Delete' , 'delete' ,4,0,'ACO');

        

 // AXO //
$root_axo = $gacl_api->add_group('root' , 'root' , 0 , 'AXO');
$gacl_api->add_group('modules' , 'Modules' , $root_axo , 'AXO');
$entity_group = $gacl_api->add_group('entities' , 'Entities' , $root_axo , 'AXO');
    
    $g_entities_primary = $gacl_api->add_group('primary' , 'Primary' , $entity_group , 'AXO');
    $g_entities_linking = $gacl_api->add_group('linking' , 'Linking' , $entity_group , 'AXO');
    $g_entities_additional = $gacl_api->add_group('additional' , 'Additional Details' , $entity_group , 'AXO');

$g_events = $gacl_api->add_group('events' , 'Events' , $root_axo , 'AXO');    	
$g_person = $gacl_api->add_group('person' , 'Person' , $root_axo , 'AXO');    	

// AXO sections //

$gacl_api->add_object_section('Modules' , 'modules' ,1, 0 , 'AXO');

$gacl_api->add_object_section('Entities' , 'entities' ,2, 0 , 'AXO');

$gacl_api->add_object_section('Events' , 'events' ,3, 0 , 'AXO');

$gacl_api->add_object_section('Person' , 'person' ,3, 0 , 'AXO');

// AXO values

$gacl_api->add_object('modules', 'Event' , 'events' ,1,0,'AXO');
$gacl_api->add_object('modules', 'Person' , 'person' ,2 ,0,'AXO');
$gacl_api->add_object('modules', 'Documents' , 'docu' ,3,0,'AXO');
$gacl_api->add_object('modules', 'Home' , 'home' ,4,0,'AXO');
$gacl_api->add_object('modules', 'Help' , 'help' ,5,0,'AXO');
$gacl_api->add_object('modules', 'Admin' , 'admin' ,6,0,'AXO');
$gacl_api->add_object('modules', 'Analysis' , 'analysis' ,7,0,'AXO');
$gacl_api->add_object('modules', 'Dashboard' , 'dashboard' ,8,0,'AXO');

$gacl_api->add_object('entities', 'Event' , 'event' ,1,0,'AXO');
$gacl_api->add_object('entities', 'Person' , 'person' ,2 ,0,'AXO');
$gacl_api->add_object('entities', 'Document' , 'document' ,3,0,'AXO');
$gacl_api->add_object('entities', 'Document Meta' , 'supporting_docs_meta' ,3,0,'AXO');
$gacl_api->add_object('entities', 'Information' , 'information' ,4,0,'AXO');
$gacl_api->add_object('entities', 'Involvement' , 'involvement' ,5,0,'AXO');
$gacl_api->add_object('entities', 'Intervention' , 'intervention' ,6,0,'AXO');
$gacl_api->add_object('entities', 'Act' , 'act' ,7,0,'AXO');
$gacl_api->add_object('entities', 'Chain Of Events' , 'chain_of_events' ,8,0,'AXO');
$gacl_api->add_object('entities', 'Biographic Details' , 'biographic_details' ,9,0,'AXO');
$gacl_api->add_object('entities', 'Arrest' , 'arrest' ,10,0,'AXO');
$gacl_api->add_object('entities', 'Destruction' , 'destruction' ,11,0,'AXO');    	
$gacl_api->add_object('entities', 'Killing' , 'killing' ,12,0,'AXO');    	
$gacl_api->add_object('entities', 'Torture' , 'torture' ,13,0,'AXO');
$gacl_api->add_object('entities', 'Address' , 'address' ,14,0,'AXO');

        

// Add Groups 

$gacl_api->add_group_object($g_entities_primary , 'entities' , 'event', 'AXO' );
$gacl_api->add_group_object($g_entities_primary , 'entities' , 'person', 'AXO' );
$gacl_api->add_group_object($g_entities_primary , 'entities' , 'document', 'AXO' );
$gacl_api->add_group_object($g_entities_primary , 'entities' , 'supporting_docs_meta', 'AXO' );
$gacl_api->add_group_object($g_entities_primary , 'entities' , 'address', 'AXO' );



$gacl_api->add_group_object($g_entities_linking , 'entities' , 'act', 'AXO' );
$gacl_api->add_group_object($g_entities_linking , 'entities' , 'information', 'AXO' );
$gacl_api->add_group_object($g_entities_linking , 'entities' , 'intervention', 'AXO' );
$gacl_api->add_group_object($g_entities_linking , 'entities' , 'involvement', 'AXO' );
$gacl_api->add_group_object($g_entities_linking , 'entities' , 'chain_of_events', 'AXO' );

$gacl_api->add_group_object($g_entities_additional , 'entities' , 'biographic_details', 'AXO' );

$gacl_api->add_group_object($g_entities_additional , 'entities' , 'arrest', 'AXO' );    	   	
$gacl_api->add_group_object($g_entities_additional , 'entities' , 'destruction', 'AXO' );
$gacl_api->add_group_object($g_entities_additional , 'entities' , 'killing', 'AXO' );
$gacl_api->add_group_object($g_entities_additional , 'entities' , 'torture', 'AXO' );    	


$gacl_api->add_group_object($g_user_admin , 'users' , 'admin' , 'ARO' );
$gacl_api->add_group_object($g_user_data_entry , 'users' , 'user1' , 'ARO' );
$gacl_api->add_group_object($g_user_analyst , 'users' , 'user2' , 'ARO' );
$gacl_api->add_group_object($g_user_data_entry , 'users' , 'user3' , 'ARO' );

// permissions

$gacl_api->add_acl( 	array( 'access'=>array('access') ) ,
                        null ,
                        array( $root_aro ),
                        array( 'modules' => array('home' , 'help' ) ) 															
                );
$gacl_api->add_acl( 	array( 'access'=>array('access') ) ,
                        null ,
                        array( $g_user_admin ),
                        array( 'modules' => array('events' , 'person' , 'docu'  , 'analysis'  , 'admin' ) ) 															
                );
$gacl_api->add_acl( 	array( 'access'=>array('access') ) ,
                        null ,
                        array( $g_user_analyst ),
                        array( 'modules' => array(  'analysis'  ) ) 															
                );
$gacl_api->add_acl( 	array( 'access'=>array('access') ) ,
                        null ,
                        array( $g_user_data_entry ),
                        array( 'modules' => array(  'person' , 'events' , 'docu' ) ) 															
                );    	 				
$gacl_api->add_acl( 	array( 'access'=>array('access') ) ,
                        null ,
                        array( $g_user_admin ),
                        array( 'modules' => array('dashboard'  ) ) 															
                );                

$gacl_api->add_acl( 	array( 'crud'=>array('create', 'read' , 'update' , 'delete') ) ,
                        null ,
                        array( $root_aro ),
                        array( 'entities' => array(  'person' ,'event' , 'act' , 'information' , 'intervention' , 'involvement' , 'chain_of_events' , 'biographic_details' , 'arrest' , 'destruction' , 'killing' , 'torture','supporting_docs_meta' , 'address'   ) ) 															
                );

$gacl_api->add_acl( 	array( 'access'=>array('access') ) ,
                        null ,
                        array( $root_aro ),
                        null,
                        array( $g_events  )
                );

$gacl_api->add_acl( 	array( 'access'=>array('access') ) ,
                        null ,
                        array( $root_aro ),
                        null,
                        array( $g_person  )
                );
                
echo 'Installed the default GACL access control List '			;

