<?php
require_once ( APPROOT . 'data/DataObject.php');

define(SALT_LENGTH, 9);

function shn_auth_generateHash($plainText, $salt ){
    return sha1($salt . $plainText);
}

function shn_auth_generateSalt(){
    return substr(md5(uniqid(rand(), true)), 0, SALT_LENGTH);
}

function generate_password($password, $salt){

        $user_password=substr($password, 0, 4).$salt.substr($password, 4);
        $stored_password = md5(trim($user_password));
        return $stored_password;
}

function generate_salt(){
        $salt1=shn_auth_generateSalt();
        $salt2=shn_auth_generateSalt();
        return $salt=$salt1.$salt2;
}

function shn_auth_getcode($user_id,$action){
    global $global;

    $userCode = UserCodeHelper::loadForUserNameForAction($user_id,$action);
    
    //$sql= "SELECT code , expiry FROM sys_user_codes WHERE user_id ='$user_id' AND action = '$action'";
    //$res = $global['db']->GetRow($sql);    
    $expiry = strtotime($userCode->getExpiry());
    
    // if the code is out of date delete it
    if($expiry !=0 && (int)$expiry < time()){
        //shn_auth_del_code($user_id , $action);
        return null;
    }
    else
        return $userCode->getCode();
        
}


function shn_auth_del_code($user_id , $action){
    
	UserCodeHelper::deleteFromUserNameFromAction($user_id , $action);

}



function shn_auth_add_user($username, $password,$role , UserProfile $userProfile=null,$status = 'disable',$userConfig = array())
{   
    global $global;
    //validate_new_user()
    if($username==null){
        return false;
    }
    if($role==null){
        $role=ANNONYMOUS;
    }
    if ( UserHelper::isUser($username) ){
        add_error("User Exists");
        
        return false;
    }

    $user = new User();
    $salt = generate_salt();
    
    $user->setUserName($username);
    $user->setSalt($salt);    
    $user->setPassword( $password  );
    $user->setCreatedDate(time());
//    $user->setRole($role);
    
    $user->user_profile = $userProfile;
    if(isset($status))
        $user->status = $status;

    if($userConfig){
        $user->config = json_encode($userConfig);
    }
    $user->Save();

   
    //add the user to acl list
    acl_add_user($username);
    //$gacl->add_group_object($role, 'users', $username,'ARO') ;
    acl_change_user_roles($username , $role);

    return true;
}






//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function shn_auth_user_list(){
    global $global;
    $db=$global['db'];
    $sql ="select u.p_uuid,full_name,user_name,sug.group_name from person_uuid as pu inner join users as u on u.p_uuid=pu.p_uuid inner join sys_user_to_group as sutg on u.p_uuid=sutg.p_uuid inner join sys_user_groups as sug on sug.group_id=sutg.group_id order by user_name";
    $fetchmode=$global['db']->SetFetchMode(ADODB_FETCH_ASSOC);
    $res = $global['db']->GetAll($sql);
    $fetchmode=$global['db']->SetFetchMode($fetchmode);
    return $res;
}




function _shn_auth_del_user_cr($user_id_array){
    
    $users = $user_id_array;
    
    for($i=0;$i<count($users);$i++){
        //if($users[$i]!=ADMINUSER){
        UserHelper::deleteUserId($users[$i]);
        UserProfileHelper::deleteUserId($users[$i]);    
        
        //  }
        }
}

function add_error($error){
    echo $error;
}

?>
