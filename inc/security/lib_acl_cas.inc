<?php
global $global;
include_once(APPROOT."3rd/cas/CAS.php");     

if(isCasAuth()){
    initCas();
    //var_dump('casStop');
	casAuthenticate();
	//var_dump('casUser',casGetUser());
	$_SESSION['username'] = casGetUser();
	$global['username']=$_SESSION['username'];
}


function initCas(){
    global $conf;
    if( isCasAuth()){   
    //var_dump(phpCAS);
    phpCAS::setDebug();   
    
    // initialize phpCAS
    phpCAS::client(CAS_VERSION_2_0, $conf['auth']['host'] ,$conf['auth']['port'],$conf['auth']['app']);
    
    // no SSL validation for the CAS server
    phpCAS::setNoCasServerValidation();
    }
        
}

function isCasAuth(){
    global $conf;
    if( is_array( $conf['auth'] )){
        if($conf['auth']['type']=='cas'){
            return true;            
        }
    }
    return false;
    
}


function casAuthenticate(){
    if( isCasAuth()){
    //    force CAS authentication
        phpCAS::forceAuthentication();
    }    
}

 
function casLogout(){
    if( isCasAuth()){
        phpCAS::logout();
    }
    
}

function casGetUser(){
    
    if( isCasAuth()){
    return phpCAS::getUser();
    }
}
