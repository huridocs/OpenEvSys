<?php
include_once APPROOT.'inc/lib_form.inc';
include_once APPROOT.'inc/lib_form_util.inc';

function user_get_selected(){
    if(isset($_POST['username']))$_GET['uid']=$_POST['username'];
    if(isset($_GET['uid']) && trim($_GET['uid'])!=''){
        $username = trim($_GET['uid']);
        $user = UserHelper::loadFromUsername($username) ;
        $uname = $user->getUserName();
    }
    if(!isset($user) || !isset($uname) ){
        set_redirect_header('admin','user_management');
        shnMessageQueue::addError(_t('PLEASE_SELECT_A_VALID_USER_'));
        exit();
    }
    //ristrict for admin users
    if($_SESSION['username']!='admin' && $_SESSION['username']!=$uname && key(acl_get_user_roles($uname)) == 'admin'){
        set_redirect_header('admin','user_management');
        shnMessageQueue::addError(_t("YOU_DO_NOT_HAVE_PERMISSION_TO_EDIT_OTHER_ADMIN_USERS_DETAILS_"));
        exit();
    }
    if($_SESSION['username']==$uname){
        set_redirect_header('admin','user_management');
        shnMessageQueue::addInformation(_t("PLEASE_USE_MY_PREFERENCE_OPTION_AT_TOP_RIGHT_CORNER_TO_EDIT_YOUR_DETAILS_"));
        exit();
    }
    return $user;
}


function user_get_profile($user)
{
    $username = $user->getUserName();
    $userProfile = UserProfileHelper::loadFromUsername($username);               
    return $userProfile;
}




function user_get_form()
{
    global $global;
    //add the user to acl list
    $options = acl_get_roles();
    
    $user_form = array(
        'username' => array('type'=>'text','label'=>_t('USERNAME'),'extra_opts'=>array('required'=>true)),
        'password1'=>array('type'=>'password','label'=>_t('PASSWORD')  ,'extra_opts'=>array('required'=>true)  ),
        'password2'=>array('type'=>'password','label'=>_t('CONFIRM_PASSWORD')  ,'extra_opts'=>array('required'=>true)  ),

        'first_name' => array('type'=>'text','label'=>_t('FIRST_NAME')),
        'last_name'=>array('type'=>'text','label'=>_t('LAST_NAME')    ),
        'organization'=>array('type'=>'text','label'=>_t('ORGANIZATION')    ),
        'designation'=>array('type'=>'text','label'=>_t('DESIGNATION')           ),// please put correct field
        'email'=>array('type'=>'text','label'=>_t('EMAIL'),'extra_opts'=>array()),
        'address'=>array('type'=>'textarea','label'=>_t('ADDRESS')      ),
        'role'=> array('type'=>'select','label'=>_t('ROLE') , 'extra_opts'=>array('options'=>$options)),
        'status' => array('type'=>'select' , 'label' => _t('STATUS') , 
                                    'extra_opts'=>array('options' => array('active'=>_t('ACTIVATED'),'disable'=>_t('DISABLED')))),
        
        'save' => array('type'=>'submit','label'=>_t('SAVE')),
        'cancel' => array('type'=>'submit','label'=>_t('CANCEL'))                     
    );
    return $user_form;
}

function user_get_populated_form($user , $userProfile , $user_form = null)
{
    if(!isset($user_form))
        $user_form = user_get_form();
    $username = $user->getUserName();
    $user_form['username']['extra_opts']['value'] = $username; 
    $user_form['username']['type'] = 'hidden';
    //$user_form['password1'] = null;
    //$user_form['password2'] = null;
    $user_form['first_name']['extra_opts']['value'] = $userProfile->getFirstName();
    $user_form['last_name']['extra_opts']['value'] = $userProfile->getLastName();
    $user_form['organization']['extra_opts']['value'] = $userProfile->getOrganization();
    $user_form['designation']['extra_opts']['value'] = $userProfile->getDesignation();
    $user_form['email']['extra_opts']['value'] = $userProfile->getEmail();
    $user_form['address']['extra_opts']['value'] = $userProfile->getAddress();
    $user_form['role']['extra_opts']['value'] = key(acl_get_user_roles($username));
    $user_form['status']['extra_opts']['value'] = $user->status;
    return $user_form; 
}


function user_get_acl_modes(){
    return array(
        'user'  => _('RESTRICT_FOR_THE_USER'),
        'role' => _('RESTRICT_FOR_THE_ROLE'),
    );
}
