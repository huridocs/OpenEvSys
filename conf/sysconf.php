<?php
/**
* The main Openevsys configuration file
*
* PHP version 4 and 5
*
* LICENSE: This source file is subject to LGPL license
* that is available through the world-wide-web at the following URI:
* http://www.gnu.org/copyleft/lesser.html
*
* @package    Sahana - http://sahana.sourceforge.net
* @author     
* @copyright  Lanka Software Foundation - http://www.opensource.lk
*/

######################################################################
#                 Openevsys Configuration Settings                   #
######################################################################
#
#

# Specify the name of this Openevsys instance. This should be a unique identifier
# of this instance of Openevsys. 
# It has to be a 4 character alphanumeric 
$conf['base_uuid'] = 'pnqQrb';

# Disable the access control system
$conf['acl_base'] = false;

# ACL mode refer documentation for more information
$conf['acl_mode'] = 'user';

# Root Name :The owner of the machine
$conf['root_name'] = '';

# Root Email :The email address of the admin
$conf['root_email'] = '';

# Root Telephone :The telephone of the admin
$conf['root_tel'] = '';


##########################
# Database Configuration #
########################## 

# specify the host ip address of the database reside.
# if it's the same server that Sahana reside then put 'localhost'
#
$conf['db_host'] = 'localhost';

# port that data base talks. leave blank for default.
#
$conf['db_port'] = '';

# theme that sahana will use todo
#
$conf['theme'] = 'default';

# specify the database name.
#
$conf['db_name'] = 'openevsys';
$conf['db_name'] = 'oe_gamze2';
//$conf['db_name'] =  'oedemo';
//$conf['db_name'] =  'oe_slot28_aae';
//$conf['db_name'] =  'oe_slot51';
# specify user name that Sahana can use to connect.
#
$conf['db_user'] = 'root';

# And password for that user.
#
$conf['db_pass'] = 'root';
#debug variable
# true/false
$conf['debug'] = false;


# Session writer 
# enter your database name here.
#
$conf['session_writer'] = 'adodb' ; 
$conf['session_name']='OESSESS';

# Sahana uses data base abstraction layer for connecting to data base.
# specify the Database Abstraction Layer Library Name here.
# Database Abstraction Layer Libraries are reside in 
# /inc/lib_database/db_libs/
# The name should be same as the library folder
#
$conf['dbal_lib_name'] = 'adodb' ;

# mention the database engine name
# @todo Find supported engine list
# for the moment, Sahana supported and tested on PostgreSQL and MySql
#
# $conf['db_engine'] = 'postgres'; 
$conf['db_engine'] = 'mysql';

#specify the mysql engine to be used
$conf['storage_engine'] = '';


# @todo Look into the database caching directories etc
# This is a testing feature.
#
$conf['enable_cache'] = false;
$conf['cache_dir'] = 'cache/db_cache';

# Default locale
#
$conf['locale'] = 'en';
#options gettext & php-gettext
$conf['locale_lib'] = 'gettext';
#don't change this unless you know what you are doing 
$conf['fb_locale'] = 'en';


$conf['custom_form']=true;
$conf['related_search'] = false;


# File upload configuration
#
$conf['media_dir'] = APPROOT.'media'.DIRECTORY_SEPARATOR;
# mode should be a number not a string
$conf['media_mode'] = 0777;


# Authentication
#$conf['auth']['type'] = 'cas';
#$conf['auth']['host'] = 'localhost';
#$conf['auth']['port'] = 8080;
#$conf['auth']['app'] = 'cas-server-webapp-3.3.5';

//session timeout in minuts
$conf['session_timeout']= 60;

$conf['fieldstohide'] = array("event"=>array("publishable","public_title","public_title_eng", "summary_spa", "summary_eng"));

$conf['browsefields'] = array("supporting_docs_meta"=>array("eventslinks"));
$conf['fieldsvalidation'] = array("event"=>array("final_date"=>array("field"=>"initial_date","validation"=>">","error"=>'Final date must be after initial date')),
        "act"=>array("final_date"=>array("field"=>"initial_date","validation"=>">","error"=>'Final date must be after initial date')));

$conf["debug"] = true;

$conf['menus'] = array("biography_list"=>true);