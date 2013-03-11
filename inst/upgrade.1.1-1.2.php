<?php

global $conf;
global $global;

define('APPROOT',realpath(dirname(__FILE__).'/../').'/');
$_SESSION['username']='admin';

require_once(APPROOT.'conf/sysconf.php');

include(APPROOT.'3rd/phpgacl/gacl.class.php');
include(APPROOT.'3rd/phpgacl/gacl_api.class.php');
include(APPROOT.'inc/handler_db.inc');
#    	$gacl= new gacl(array('db_host'=> $conf['db_host'] , 'db_name'=> $conf['db_name'] , 'db_user'=> $conf['db_user'], 'db_password'=> $conf['db_pass'] , 'db_table_prefix'=>'gacl_' , 'db_type'=> 'mysql'  ));

#    	$gacl_api = new gacl_api(array('db_host'=> $conf['db_host'] , 'db_name'=> $conf['db_name'] , 'db_user'=> $conf['db_user'], 'db_password'=> $conf['db_pass'] , 'db_table_prefix'=>'gacl_' , 'db_type'=> 'mysql'  ));

$gacl = new gacl(array('db'=>$global['db'] , 'db_table_prefix'=>'gacl_'));
$gacl_api = new gacl_api(array('db'=>$global['db'] , 'db_table_prefix'=>'gacl_'));

$g_user_admin =	$gacl_api->get_group_id('admin' , 'Admin' , ' ARO');

$gacl_api->add_object('modules', 'Dashboard' , 'dashboard' ,8,0,'AXO');


$gacl_api->add_acl( 	array( 'access'=>array('access') ) ,
                        null ,
                        array( $g_user_admin ),
                        array( 'modules' => array('dashboard'  ) ) 															
                );

