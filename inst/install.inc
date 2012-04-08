<?php
    set_time_limit(300);
    global $global;
    ob_start();
//1. check if the directories are writable
    $continue = true;
    $conf_dir = '<span class="green">writable</span>';
    $media = '<span class="green">writable</span>';
	if (!is_writable(APPROOT.'conf')){
        $continue = false;
        $conf_dir = '<span class="red">not writable</span>';
        $file_check=true;
    }
	if (!is_writable(APPROOT.'media')){
        $continue = false;
        $media = '<span class="red">not writable</span>';
        $file_check=true;
    }

    //check for password match


    if(isset($_POST['install']) && $continue){
        require_once(APPROOT.'3rd/adodb5/adodb.inc.php');
        if(!preg_match( "/^[A-Za-z][A-Za-z0-9_]+$/", $_POST['db_name'])){
            add_inst_error('Please enter a valid database name. Name should only contain alphanumeric characters.');
        }
        else{
        //check if the database exists
        $db = _create_db($_POST['db_host'],$_POST['db_user'],$_POST['db_password'],$_POST['db_name']);
        if(!isset($db)){
            add_inst_error('Unable to connect to database, please check the values.');
        }
        else if($_POST['password']==''){
            add_inst_error('Password can not be blank.');
        }
        else if($_POST['password']!=$_POST['password_c']){
            add_inst_error('Password and Confirm Password doesn\'t match');
        }
        else{
            //load the tables
            _loadsql($db,'mysql-dbcreate.sql');
            _loadsql($db,'mysql-dbcreate-system.sql');            
            _loadsql($db,'mysql-dbcreate-gacl.sql');
            _loadsql($db,'mysql-dbpopulate-datadict.sql');
            _loadsql($db,'mysql-dbpopulate-mt.sql');
            _loadsql($db,'mysql-dbpopulate-gacl.sql');
            _loadsql($db,'mysql-dbpopulate-help.sql');
            //iterate through language scripts
            if ($handle = opendir(APPROOT.'res/locale')) {
                while (false !== ($file = readdir($handle))) {
                    if ($file != "." && $file != "..") {
                        _loadsql($db,'data_dict_l10n.sql',"res/locale/$file/");
                        _loadsql($db,'mt_vocab_l10n.sql',"res/locale/$file/");
                        _loadsql($db,'help_l10n.sql',"res/locale/$file/");
                    }
                }
                closedir($handle);
            }


            //create the sysconf
            $sysconf = file_get_contents(APPROOT.'conf/sysconf.tpl.php');
            $sysconf = str_replace("HURI", $_POST['base_uuid'], $sysconf);
            $sysconf = str_replace("VAR_HOSTNAME", $_POST['db_host'], $sysconf);
            $sysconf = str_replace("VAR_PORT", $_POST[''], $sysconf);
            $sysconf = str_replace("VAR_DATABASE", $_POST['db_name'], $sysconf);
            $sysconf = str_replace("VAR_USER", $_POST['db_user'], $sysconf);
            $sysconf = str_replace("VAR_PASSWORD", $_POST['db_password'], $sysconf);
            // Write the contents back sysconf file
            file_put_contents(APPROOT.'conf/sysconf.php', $sysconf);

            //create the admin user
            include_once(APPROOT.'inc/security/lib_auth.inc');
            $salt = generate_salt();
            $pass = shn_auth_generateHash($_POST['password'],$salt);
            $sql =  "UPDATE user SET password='$pass' , salt='$salt' WHERE username='admin'";
            $db->Execute($sql);

            include_once('install.gacl.php');
        
            $sucess = true;
        }
        }
    }
    ob_end_clean();
//include the template
require_once('install.tpl.php');



//utility functions
function _loadsql($db, $script_name, $path = 'schema/')
{
	global $global;
	$script = APPROOT.$path.$script_name;

	if(file_exists($script)){
		if ($fh = fopen($script,'r')) {
			$sql=''; // clear the bucket

			while (! feof($fh) ) { // a fix as Execute gets confused over carriage returns
				$buffer = fgets($fh,1024); // read the script and fill the buffer
				$sql=$sql.$buffer; // fill the buffer until ';' appear
				if(preg_match('/;/',$buffer)) { // look after ';'
					$res = $db->Execute($sql);
//                    if(!$res){echo $db->ErrorMsg();ob_flush();echo "==== $script_name ===\n";}
					$sql=''; // clear the bucket again
				}
			}
			//$sql = fread($fh,filesize($script));
			fclose($fh);
		}
	}
	// @todo error handler if file does not exist
}

function _create_db($host_port,$user,$pass,$dbname='openevsys'){
	global $global;

	// another separate connection to create users and databases.
	$db = &NewADOConnection('mysql');
	$db->Connect($host_port,$user,$pass,$dbname);
	if($db->IsConnected()){
		return $db;
	}

	$db = &NewADOConnection('mysql');
	$db->Connect($host_port,$user,$pass,'mysql');
	if($db->IsConnected()){
	    $db->Execute("CREATE DATABASE IF NOT EXISTS ".$dbname." DEFAULT CHARACTER SET utf8");
        $db->Close();
	    $db->Connect($host_port,$user,$pass,$dbname);
		return $db;
	}
    return null;
}

function add_inst_error($msg)
{
    global $global;
    if(!is_array($global['error']))$global['error']=array();
    array_push($global['error'],$msg);
}



function getUniqueCode($length = 5)
{
    $base='ABCDEFGHKLMNOPQRSTWXYZabcdefghjkmnpqrstwxyz123456789';
    $max=strlen($base)-1;
    $activatecode='';
    mt_srand((double)microtime()*1000000);
    while (strlen($activatecode)<$length+1)
      $activatecode.=$base{mt_rand(0,$max)};
      
    return $activatecode;
}
