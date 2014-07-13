<?php
include_once APPROOT . 'inc/lib_uuid.inc';

class homeModule extends shnModule {

    function act_default() {
        if (acl_is_mod_allowed('events'))
            set_redirect_header('events', 'browse');
        else if (acl_is_mod_allowed('person'))
            set_redirect_header('person', 'browse');
        else if (acl_is_mod_allowed('docu'))
            set_redirect_header('docu', 'browse');
        else if (acl_is_mod_allowed('analysis'))
            set_redirect_header('analysis', 'adv_search');
        else if (acl_is_mod_allowed('events'))
            set_redirect_header('admin', 'field_customization');
        else
            shnMessageQueue::addInformation(_t('IF_YOU_REACH_THIS_PAGE_IT_MEANS_YOU_DO_NOT_HAVE_ACCESS_TO_ANY_OF_THE_MODULES_IN_OPENEVSYS__PLEASE_CONTACT_THE_SYSTEM_ADMINISTRATOR_FOR_MORE_DETAILS_'));
    }

    function act_save() {
        
    }

    function act_my_pref() {
        
    }

    public function act_edit_user() {

        // var_dump($_POST);
        include_once APPROOT . 'inc/lib_form.inc';
        include_once APPROOT . 'inc/lib_form_util.inc';
        include_once APPROOT . 'inc/lib_validate.inc';
        include_once APPROOT . 'inc//security/lib_auth.inc';
        include_once APPROOT . 'mod/admin/lib_user.inc';

        $user_form = user_get_form();

        unset($user_form['username']);
        unset($user_form['role']);
        unset($user_form['status']);
        unset($user_form['password1']);
        unset($user_form['password2']);

        $this->user_form = $user_form;

        //$user_form['username']['type'] = 'hidden';
        //$user_form['username']['extra_opts']['value'] = $_SESSION['username'];

        if (isset($_POST['save'])) {

            $valide = true;

            $username = $_SESSION['username'];
            $firstName = $_POST['first_name'];
            $lastName = $_POST['last_name'];
            $organization = $_POST['organization'];
            $designation = $_POST['designation'];
            $email = $_POST['email'];
            $address = $_POST['address'];
            $locale = $_POST['locale'];



            if (isset($email) && $email != '' && !shn_valid_email($email)) {
                //email not valide
                $user_form['email']['extra_opts'] = array();
                $user_form['email']['extra_opts']['error'] = array();
                $user_form['email']['extra_opts']['error'][] = _t("INVALID_EMAIL");
                $valide = false;
            }


            $this->user_form = $user_form;


            if ($valide == true) {

                $user= new User();
                $user = UserHelper::loadFromUsername($username) ;
                //$user->loadUserProfile(); 
                //$user->role = $role;
                //$user->status =  $status;
                $cfg = array();
                if (!empty($user->config)) {
                    $cfg = @json_decode($user->config, true);
                }
                $cfg['locale'] = $locale;
                $user->config = json_encode($cfg);
                
                $user->Save();

                $userProfile = UserProfileHelper::loadFromUsername($username);

                $userProfile->username = $username;
                $userProfile->first_name = $firstName;
                $userProfile->last_name = $lastName;
                $userProfile->organization = $organization;
                $userProfile->designation = $designation;
                $userProfile->email = $email;
                $userProfile->address = $address;
                $userProfile->Save();
                set_redirect_header('home', 'edit_user');
            }
        }


        if (isset($_SESSION['username'])) {
            $user = new User();
            $userProfile = new UserProfile();
            $username = $_SESSION['username'];
            $user->Load("username='" . $username . "'");
            $userProfile->Load("username='" . $username . "'");

            //$user_form['username']['extra_opts']['value'] = $user->getUserName();
            //$user_form['password1'] = null;
            //$user_form['password2'] = null;
            $user_form['first_name']['extra_opts']['value'] = $userProfile->getFirstName();
            $user_form['last_name']['extra_opts']['value'] = $userProfile->getLastName();
            $user_form['organization']['extra_opts']['value'] = $userProfile->getOrganization();
            $user_form['designation']['extra_opts']['value'] = $userProfile->getDesignation();
            $user_form['email']['extra_opts']['value'] = $userProfile->getEmail();
            $user_form['address']['extra_opts']['value'] = $userProfile->getAddress();
            //$user_form['role']['extra_opts']['value'] = $user->getUserType();
            //$user_form['status']['extra_opts']['value'] = $user->status;
            if (!empty($user->config)) {
                $cfg = @json_decode($user->config, true);
                if ($cfg['locale']) {
                    $user_form['locale']['extra_opts']['value'] = $cfg['locale'];
                }
            }
            $this->user_form = $user_form;
        }

        $this->username = $username;
    }

    public function act_edit_password() {

        include_once APPROOT . 'inc/lib_form.inc';
        include_once APPROOT . 'inc/lib_form_util.inc';
        include_once APPROOT . 'inc/lib_validate.inc';
        include_once APPROOT . 'inc//security/lib_auth.inc';
        include_once APPROOT . 'mod/admin/change_password_form.inc';
        $this->username = $_SESSION['username'];

        if (isset($_POST['save'])) {

            $password_current = $_POST['password_current'];

            $password1 = $_POST['password1'];
            $password2 = $_POST['password2'];

            $user = UserHelper::loadFromUsername($this->username);


            if ($user->isPasswordMatch($password_current)) {

                if ($password1 == $password2) {

                    $user->setOldSalt($user->getSalt());
                    $user->setOldPasswordHash($user->getPasswordHash());

                    $salt = generate_salt();
                    $user->setSalt($salt);
                    $user->setPassword($password1);
                    $user->Save();


                    set_redirect_header('home', 'edit_user');
                } else {

                    $change_password_form['password1']['extra_opts'] = array();
                    $change_password_form['password1']['extra_opts']['error'] = array();
                    $change_password_form['password1']['extra_opts']['error'][] = _t("THE_TWO_PASSWORDS_ARE_DIFFERENT");

                    $change_password_form['password2']['extra_opts'] = array();
                    $change_password_form['password2']['extra_opts']['error'] = array();
                    $change_password_form['password2']['extra_opts']['error'][] = _t("THE_TWO_PASSWORDS_ARE_DIFFERENT");
                }
            } else {
                $change_password_form['password_current']['extra_opts'] = array();
                $change_password_form['password_current']['extra_opts']['error'] = array();
                $change_password_form['password_current']['extra_opts']['error'][] = _t("PASSWORD_INVALID");
            }
        }


        $this->change_password_form = $change_password_form;
    }

    public function act_edit_security() {
        include_once 'lib_user.inc';

        $user = UserHelper::loadFromUsername($_SESSION['username']);
        $this->user = $user;
        $result = $user->getGASk();
        $this->url = $result['url'];
        $this->secret = $result['secret'];

        if($_POST['desiredMethod'] == "none") {
            $user->disableTSV();

            return true;
        } 

        if($_POST['desiredMethod'] == "MGA" && isset($_POST['code'])) {
            $resp = $user->TSVSaveMGA($_POST['code']);
            if (!$resp) {
                $this->wrongcode = true;
            }

            return true;
        }

        if($_POST['desiredMethod'] == "yubikey" && $this->isYubikeyAPIConfigured()) {
            $user->TSVSaveYubiKey();

            return true;
        }
    }

    public function act_test() {
        include(APPROOT . '3rd/phpgacl/gacl_api.class.php');
//    	$gacl_api = new gacl_api(array('db'=>$global['db'] , 'db_table_prefix'=>'gacl_'));

        $gacl_api = new gacl_api();

        $gacl_api->clear_database();

        // ARO //
        $root_aro = $gacl_api->add_group('root', 'OpenEvSysUser', 0, 'ARO');
        $user_group = $gacl_api->add_group('users', 'Users', $root_aro, 'ARO');
        $g_user_admin = $gacl_api->add_group('admin', 'Admin', $user_group, ' ARO');
        $g_user_analyst = $gacl_api->add_group('analyst', 'Analyst', $user_group, ' ARO');
        $g_user_data_entry = $gacl_api->add_group('data_entry', 'Data Entry', $user_group, ' ARO');

        $ws_group = $gacl_api->add_group('ws', 'WS', $root_aro, 'ARO');

        // ARO sections

        $gacl_api->add_object_section('Users', 'users', 1, 0, 'ARO');

        //ARO values

        $gacl_api->add_object('users', 'Admin', 'admin', 1, 0, 'ARO');
        $gacl_api->add_object('users', 'User1', 'user1', 2, 0, 'ARO');
        $gacl_api->add_object('users', 'User2', 'user2', 3, 0, 'ARO');
        $gacl_api->add_object('users', 'User3', 'user3', 4, 0, 'ARO');


        //ACO //
        //ACO sections

        $gacl_api->add_object_section('CRUD', 'crud', 1, 0, 'ACO');
        $gacl_api->add_object_section('Access', 'access', 1, 0, 'ACO');

        //ACO values

        $gacl_api->add_object('access', 'Access', 'access', 1, 0, 'ACO');

        $gacl_api->add_object('crud', 'Create', 'create', 1, 0, 'ACO');
        $gacl_api->add_object('crud', 'Read', 'read', 2, 0, 'ACO');
        $gacl_api->add_object('crud', 'Update', 'update', 3, 0, 'ACO');
        $gacl_api->add_object('crud', 'Delete', 'delete', 4, 0, 'ACO');



        // AXO //
        $root_axo = $gacl_api->add_group('root', 'root', 0, 'AXO');
        $gacl_api->add_group('modules', 'Modules', $root_axo, 'AXO');
        $entity_group = $gacl_api->add_group('entities', 'Entities', $root_axo, 'AXO');

        $g_entities_primary = $gacl_api->add_group('primary', 'Primary', $entity_group, 'AXO');
        $g_entities_linking = $gacl_api->add_group('linking', 'Linking', $entity_group, 'AXO');
        $g_entities_additional = $gacl_api->add_group('additional', 'Additional Details', $entity_group, 'AXO');

        $g_events = $gacl_api->add_group('events', 'Events', $root_axo, 'AXO');

        // AXO sections //

        $gacl_api->add_object_section('Modules', 'modules', 1, 0, 'AXO');

        $gacl_api->add_object_section('Entities', 'entities', 2, 0, 'AXO');

        $gacl_api->add_object_section('Events', 'events', 3, 0, 'AXO');

        // AXO values

        $gacl_api->add_object('modules', 'Event', 'events', 1, 0, 'AXO');
        $gacl_api->add_object('modules', 'Person', 'person', 2, 0, 'AXO');
        $gacl_api->add_object('modules', 'Documents', 'docu', 3, 0, 'AXO');
        $gacl_api->add_object('modules', 'Home', 'home', 4, 0, 'AXO');
        $gacl_api->add_object('modules', 'Help', 'help', 5, 0, 'AXO');
        $gacl_api->add_object('modules', 'Admin', 'admin', 6, 0, 'AXO');
        $gacl_api->add_object('modules', 'Analysis', 'analysis', 7, 0, 'AXO');

        $gacl_api->add_object('entities', 'Event', 'event', 1, 0, 'AXO');
        $gacl_api->add_object('entities', 'Person', 'person', 2, 0, 'AXO');
        $gacl_api->add_object('entities', 'Document', 'document', 3, 0, 'AXO');
        $gacl_api->add_object('entities', 'Information', 'information', 4, 0, 'AXO');
        $gacl_api->add_object('entities', 'Involvement', 'involvement', 5, 0, 'AXO');
        $gacl_api->add_object('entities', 'Intervention', 'intervention', 6, 0, 'AXO');
        $gacl_api->add_object('entities', 'Act', 'act', 7, 0, 'AXO');
        $gacl_api->add_object('entities', 'Chain Of Events', 'chain_of_events', 8, 0, 'AXO');
        $gacl_api->add_object('entities', 'Biographic Details', 'biographic_details', 9, 0, 'AXO');

        // Add Groups 

        $gacl_api->add_group_object($g_entities_primary, 'entities', 'event', 'AXO');
        $gacl_api->add_group_object($g_entities_primary, 'entities', 'person', 'AXO');
        $gacl_api->add_group_object($g_entities_primary, 'entities', 'document', 'AXO');

        $gacl_api->add_group_object($g_entities_linking, 'entities', 'act', 'AXO');
        $gacl_api->add_group_object($g_entities_linking, 'entities', 'information', 'AXO');
        $gacl_api->add_group_object($g_entities_linking, 'entities', 'intervention', 'AXO');
        $gacl_api->add_group_object($g_entities_linking, 'entities', 'involvement', 'AXO');
        $gacl_api->add_group_object($g_entities_linking, 'entities', 'chain_of_events', 'AXO');

        $gacl_api->add_group_object($g_entities_additional, 'entities', 'biographic_details', 'AXO');

        $gacl_api->add_group_object($g_user_admin, 'users', 'admin', 'ARO');
        $gacl_api->add_group_object($g_user_data_entry, 'users', 'user1', 'ARO');
        $gacl_api->add_group_object($g_user_analyst, 'users', 'user2', 'ARO');
        $gacl_api->add_group_object($g_user_data_entry, 'users', 'user3', 'ARO');

        // permissions

        $gacl_api->add_acl(array('access' => array('access')), null, array($root_aro), array('modules' => array('home', 'help'))
        );
        $gacl_api->add_acl(array('access' => array('access')), null, array($g_user_admin), array('modules' => array('events', 'person', 'docu', 'analysis', 'admin'))
        );
        $gacl_api->add_acl(array('access' => array('access')), null, array($g_user_analyst), array('modules' => array('analysis'))
        );
        $gacl_api->add_acl(array('access' => array('access')), null, array($g_user_data_entry), array('modules' => array('person', 'events', 'docu'))
        );


        $gacl_api->add_acl(array('crud' => array('create', 'read', 'update', 'delete')), null, array($root_aro), array('entities' => array('person', 'event', 'act', 'information', 'intervention', 'involvement', 'chain_of_events', 'biographic_details'))
        );

        $gacl_api->add_acl(array('crud' => array('create', 'read', 'update', 'delete')), null, array($root_aro), null, array($g_events)
        );
    }

    function act_mt_tree() {
        $data_array = MtFieldWrapper::getMTList($_GET['list_code']);

        $count = count($data_array);
        for ($i = 0; $i < $count;) {
            $element1 = $data_array[$i];

            if (is_array($value)) {
                $sel = ( in_array($element1['vocab_number'], $value) ) ? 'selected="selected"' : null;
            } else {
                $sel = ( $element1['vocab_number'] == $value ) ? 'selected="selected"' : null;
            }
            ?>
            <option value="<?php echo $element1['vocab_number'] ?>" <?php echo $sel ?> data-level="<?php echo $element1["term_level"] ?>"><?php echo $element1['label'] ?></option>

            <?php
            $i++;
        }
    }

    function act_mt_select() {
        $micro_thesauri = new MicroThesauri($_GET['list_code']);
        $this->terms = $micro_thesauri->getTerms();
    }

    function act_filemanager() {
        global $conf;
        $imagesfolder = $conf['media_dir'] . "filemanager" . DS; //WWWWROOT . "images" . DS . "uploads" . DS;
        if (isset($_GET['del_file'])) {
            @unlink($imagesfolder . $_GET['del_file']);
        }
    }

    function act_filemanagerupload() {
        include_once APPROOT . 'inc/lib_uuid.inc';
        include_once APPROOT . 'inc/lib_files.inc';
        $type = null;
        global $conf;
        $imagesfolder = $conf['media_dir'] . "filemanager" . DS; //WWWWROOT . "images" . DS . "uploads" . DS;
        $uri = shn_files_store('file', null, $type, $imagesfolder);
        exit;
    }

    public function act_download() {
        global $conf;
        $imagesfolder = $conf['media_dir'] . "filemanager" . DS; //WWWWROOT . "images" . DS . "uploads" . DS;

        $file = $_REQUEST['file'];
        $file = str_replace("/", "", $file);
        $file = str_replace("\\", "", $file);
        if ($file && filesize($imagesfolder . $file)) {
            header('Pragma: private');
            header('Cache-control: private, must-revalidate');
            header("Content-Type: application/octet-stream");
            header("Content-Length: " . (string) (filesize($imagesfolder . $file)));
            header('Content-Disposition: attachment; filename="' . ($file) . '"');
            readfile($imagesfolder . $file);
            exit;
        } else {
            //shnMessageQueue::addInformation('No attachment found to this document.');
            //set_redirect_header('docu', 'view_document', null, null);
        }
        exit();
    }

    public function act_oe2wp() {
        global $conf;
        $imagesfolder = $conf['media_dir'] . "filemanager" . DS; //WWWWROOT . "images" . DS . "uploads" . DS;

        $file = $_REQUEST['file'];
        $file = str_replace("/", "", $file);
        $file = str_replace("\\", "", $file);
        if ($file && filesize($imagesfolder . $file)) {
            header('Pragma: private');
            header('Cache-control: private, must-revalidate');
            header("Content-Type: application/octet-stream");
            header("Content-Length: " . (string) (filesize($imagesfolder . $file)));
            header('Content-Disposition: attachment; filename="' . ($file) . '"');
            readfile($imagesfolder . $file);
            exit;
        } else {
            //shnMessageQueue::addInformation('No attachment found to this document.');
            //set_redirect_header('docu', 'view_document', null, null);
        }
        exit();
    }

}
