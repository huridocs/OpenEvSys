<?php

include_once APPROOT . 'inc/lib_form.inc';
include_once APPROOT . 'inc/lib_form_util.inc';
require_once APPROOT . '3rd/yubico/Yubico.php';

class adminModule extends shnModule {

    public function section_modwrap_open() {
        return $data;
    }

    public function section_modwrap_close() {
        return $data;
    }

    public function section_mod_menu() {
        return $data;
    }

    public function act_import_log() {
        $browse = new Browse();
        $this->errorlist = $browse->getImportErrorLogList();
        $this->values = $this->errorlist->get_page_data();
    }

    public function act_import_log_show() {
        global $conf;
        $filename = $_GET['report_name'];
        $file_path = $conf['media_dir'] . "/Import_Error_log/" . $filename;
        $size = filesize($file_path);

        header("Content-Type: application/text");
        header("Content-Disposition: attachment; filename=" . $filename);
        header("Content-Length: " . $size);

        $fp = fopen($file_path, 'rb');
        fpassthru($fp);

        exit();
    }

    /* {{{ Form customization */

    public function act_field_customization() {
        global $conf;
        include_once APPROOT . 'mod/admin/lib_form_customization.inc';

        $this->entity_select = $_REQUEST['entity_select'];
        $this->sub_act = (isset($_REQUEST['sub_act'])) ? $_REQUEST['sub_act'] : 'label';
        $_REQUEST['sub_act'] = $this->sub_act;

        $this->browse_needed = false;
        if ($this->entity_select == 'event' || $this->entity_select == 'person' || $this->entity_select == 'supporting_docs_meta') {
            $this->browse_needed = true;
        }
        if ($this->entity_select == 'biographic_details' && $conf['menus']['biography_list']) {
            $this->browse_needed = true;
        }

        include_once APPROOT . 'mod/admin/customization_form.inc';
        //include select entity form        
        $this->customization_form = $customization_form;
        //if the locale is changed need to display extra column in label customization
        //if(is_locale_changed())
        $this->locale = $conf['locale'];

        include_once(APPROOT . 'inc/i18n/lib_l10n.inc');
        $this->locales = l10n_get_locals();

        if (isset($this->entity_select)) {
            if ($this->sub_act == 'help') {
                if ($_POST['save_help']) {
                    form_customization_update_help($_POST, $this->entity_select);
                }
                $this->help_texts = Browse::getHelpText($this->entity_select, $this->locales);
                return;
            } elseif ($this->sub_act == 'order' && isset($_POST['itemsorder'])) {
                form_customization_update_fields_order($this->entity_select);
            } else {
                //if update is sent save data
                //OES-28. By pressing enter forms submited. if there no reset nor update - entered data will be lost.
                //so better to save changes. update by default
                //if($_POST['update']){
                if ('POST' == $_SERVER['REQUEST_METHOD']) {
                    form_customization_process_entity_form($this->entity_select);
                }

                /* if (isset($_POST['reset'])) {
                  form_customization_reset_all($this->entity_select);
                  } */

                // OES-28
                /* $reset_fields = form_customization_get_reset_fields();
                  foreach ($reset_fields as $post_value => $table_field) {
                  if (isset($_POST[$post_value])) {
                  form_customization_reset_field($this->entity_select, $table_field);
                  }
                  } */
            }
            //include field form
            include_once APPROOT . 'mod/admin/entity_form.inc';

            $this->entity_form = $entity_form;
            if ($this->sub_act == 'label') {
                $this->res = Browse::getFieldsTranslations($this->entity_select, $this->locales);
            } else {
                $this->res = form_customization_get_field_table($this->entity_select);
            }
            if ($this->sub_act == 'visibility') {
                $fields_form = generate_formarray($this->entity_select, "new");
                $fields_form2 = array();
                foreach ($fields_form as $k => $f) {
                    if ($f["type"] == 'mt_tree' || $f['type'] == 'mt_select'  || $f['type'] == 'radio') {
                        $fields_form2[$k] = $f;
                    }
                }

                $this->fields_form = $fields_form2;
                $res = Browse::getFields($this->entity_select);
                $fields = array();

                $field_numbers = array();
                foreach ($res as $record) {
                    if ($record['enabled'] == 'y' && $record['visible_new'] == 'y' &&
                            ( $record['field_type'] == 'mt_tree' || $record['field_type'] == 'mt_select' || $record['field_type'] == 'radio')) {
                        $fields[$record['field_number']] = $record;
                    }
                    $field_numbers[] = $record['field_number'];
                }
                $this->fields_for_hide = $fields;

                $browse = new Browse();
                $sql = "SELECT * from data_dict_visibility where field_number in ('" . implode("','", $field_numbers) . "') order by field_number,field_number2";
                $this->visibility_fields = $browse->ExecuteQuery($sql);
            }
        }
    }

    public function act_new_mt() {
        include_once 'lib_mt_customization.inc';
        if (isset($_POST['add_new'])) {

            $return = mt_customization_add_new_taxonomy();
            if ($return) {
                shnMessageQueue::addInformation(_t('Micro-thesauri created successfully'));
            }
        }
    }

    public function act_new_field() {
        global $conf;
        include_once APPROOT . 'mod/admin/lib_form_customization.inc';
        $entity_select_options = array_merge(array('' => ''),getActiveFormats());

        $field_type_options = array(
            'text' => _t('TEXT_FIELD_WITH_A_200_CHARACTER_LIMIT'),
            'textarea' => _t('TEXTAREA_WITH_UNLIMITED_TEXT'),
            'date' => _t('DATE_FIELD_'),
            'radio' => _t('Radio field'),
            'location' => _t('Geolocation'),
            'user_select' => _t('User Select'),
            'user_select_multi' => _t('Multivalue User Select'),
            'mt_tree' => _t('Tree'),
            'mt_tree_multi' => _t('Multivalue Tree'),
            'mt_select' => _t('SELECT'),
            'mt_select_multi' => _t('Multivalue Select'),
            'line' => _t('Line'),
        );

        $translations = StringTranslations::getMtTranslations(null, $conf['locale']);

        $mtIndex = new MtIndex();
        $index_terms = $mtIndex->Find('');
        $taxonomies = array();
        $taxonomies[''] = '';
        foreach ($index_terms as $index_term) {
            $label = $index_term->no . ' - ' . $index_term->term;

            if ($translations[$index_term->no][$conf['locale']]) {
                $label = $translations[$index_term->no][$conf['locale']]['value'];
            }
            $taxonomies[$index_term->no] = $label;
        }

        $this->taxonomies = $taxonomies;
        $this->entity_select_options = $entity_select_options;
        $this->field_type_options = $field_type_options;

        if (isset($_POST['add_new'])) {

            $return = form_customization_add_field($field_type_options);
            if ($return) {
                shnMessageQueue::addInformation(_t('FIELD_CREATED_SUCCESSFULLY_'));
            }
        }
    }

    /* }}} */

    /* {{{ User management */

    public function act_user_management() {

        $this->user_pager = Browse::getUserList();
        $this->users = $this->user_pager->get_page_data();
    }

    public function act_edit_user() {
        include_once 'lib_user.inc';

        $this->user = user_get_selected();
        $this->userProfile = user_get_profile($this->user);
        $this->user_form = user_get_form();

        include_once APPROOT . 'inc/lib_validate.inc';
        include_once APPROOT . 'inc//security/lib_auth.inc';
        
        if (isset($_POST['save'])) {

            $valide = true;

            $firstName = $_POST['first_name'];
            $lastName = $_POST['last_name'];
            $organization = $_POST['organization'];
            $designation = $_POST['designation'];
            $email = $_POST['email'];
            $address = $_POST['address'];
            $role = $_POST['role'];
            $status = $_POST['status'];
            $locale = $_POST['locale'];
            if ($email != '' && !shn_valid_email($email)) {
                //email not valide
                $this->user_form['email']['extra_opts'] = array();
                $this->user_form['email']['extra_opts']['error'] = array();
                $this->user_form['email']['extra_opts']['error'][] = _t("INVALID_EMAIL_ADDRESS");
                $valide = false;
            }

            if ($valide == true) {
                $user = $this->user;
                $userProfile = $this->userProfile;
                $username = $this->user->getUserName();
                acl_change_user_roles($username, $role);
                $user->status = $status;
                $cfg = array();
                if (!empty($user->config)) {
                    $cfg = @json_decode($user->config, true);
                }
                $cfg['locale'] = $locale;
                $user->config = json_encode($cfg);
                
                $user->Save();
                $userProfile->username = $username;
                $userProfile->first_name = $firstName;
                $userProfile->last_name = $lastName;
                $userProfile->organization = $organization;
                $userProfile->designation = $designation;
                $userProfile->email = $email;
                $userProfile->address = $address;
                
                $userProfile->Save();
                set_redirect_header('admin', 'user_management');
            }
        }
        $this->user_form = user_get_populated_form($this->user, $this->userProfile, $this->user_form);
    }

    public function act_edit_password() {
        include_once 'lib_user.inc';
        include_once APPROOT . 'inc/lib_validate.inc';
        include_once APPROOT . 'inc//security/lib_auth.inc';
        include_once APPROOT . 'mod/admin/change_password_form.inc';
        $user = user_get_selected();
        $this->user = $user;

        if (isset($_POST['save'])) {
            $password1 = $_POST['password1'];
            $password2 = $_POST['password2'];

            if ($password1 == $password2) {
                $user->setOldSalt($user->getSalt());
                $user->setOldPasswordHash($user->getPasswordHash());

                $salt = generate_salt();
                $user->setSalt($salt);
                $user->setPassword($password1);
                $user->Save();
            } else {

                $change_password_form['password1']['extra_opts'] = array();
                $change_password_form['password1']['extra_opts']['error'] = array();
                $change_password_form['password1']['extra_opts']['error'][] = _t("THE_TWO_PASSWORDS_ARE_DIFFERENT");

                $change_password_form['password2']['extra_opts'] = array();
                $change_password_form['password2']['extra_opts']['error'] = array();
                $change_password_form['password2']['extra_opts']['error'][] = _t("THE_TWO_PASSWORDS_ARE_DIFFERENT");
            }
        }
        $this->change_password_form = $change_password_form;
    }

    public function act_edit_security() {
        include_once 'lib_user.inc';

        $user = user_get_selected();
        $this->user = $user;
        $result = $user->getGASk();
        $this->url = $result['url'];
        $this->secret = $result['secret'];
        $this->isYubikeyAPIConfigured = $this->isYubikeyAPIConfigured();

        if($_POST['desiredMethod'] == "none") {
            $user->disableTSV();

            return true;
        } 

        if($_POST['desiredMethod'] == "MGA" && isset($_POST['GACode'])) {
            $resp = $user->TSVSaveMGA($_POST['GACode']);
            if (!$resp) {
                $this->wrongcode = true;
            }

            return true;
        }

        if($_POST['desiredMethod'] == "yubikey" && $this->isYubikeyAPIConfigured()) {
            if(isset($_POST['YubiKeyCode'])) {
                $verifyCode = $this->getYubiClient()->verify($_POST['YubiKeyCode']);

                if(PEAR::isError($verifyCode)) {
                    $this->wrongcode = $verifyCode->message;
                    return false;
                }

                $parsedCode = $this->parseYubicoCode($_POST['YubiKeyCode']);
                $user->TSVSaveYubiKey($parsedCode['prefix']);
                return true;
            }
        }
    }

    protected function getYubiClient() {
        global $conf;

        return new Auth_Yubico($conf['YubiKeyClientId'], $conf['YubiKeyClientKey'], true, true);
    }

    protected function parseYubicoCode($code) {
        $result = $this->getYubiClient()->parsePasswordOTP($code);

        return $result;
    }

    protected function isYubikeyAPIConfigured() {
        global $conf;

        $hasClientKey = (isset($conf["YubiKeyClientKey"]) && $conf["YubiKeyClientKey"] != "");
        $hasClientId = (isset($conf["YubiKeyClientId"]) && $conf["YubiKeyClientId"] != "");

        if($hasClientId && $hasClientKey)
            return true;

        return false;
    }

    public function act_add_user() {

        // var_dump($_POST);
        include_once APPROOT . 'inc/lib_form.inc';
        include_once APPROOT . 'inc/lib_form_util.inc';
        include_once APPROOT . 'inc/lib_validate.inc';
        include_once APPROOT . 'inc//security/lib_auth.inc';
        include_once 'lib_user.inc';

        $this->user_form = user_get_form();

        if (isset($_POST['save'])) {

            $valide = true;

            $username = $_POST['username'];
            $password1 = $_POST['password1'];
            $password2 = $_POST['password2'];
            $firstName = $_POST['first_name'];
            $lastName = $_POST['last_name'];
            $organization = $_POST['organization'];
            $designation = $_POST['designation'];
            $email = $_POST['email'];
            $address = $_POST['address'];
            $role = $_POST['role'];
            $status = $_POST['status'];
            $locale = $_POST['locale'];
            $user_form = $this->user_form;

            if (trim($username) == '') {
                $user_form['username']['extra_opts'] = array();
                $user_form['username']['extra_opts']['error'] = array();
                $user_form['username']['extra_opts']['error'][] = _t("USERNAME_CANNOT_BE_EMPTY");
                $user_form['username']['extra_opts']['required'][] = true;
                $valide = false;
            }
            if (UserHelper::isUser($username)) {
                $user_form['username']['extra_opts'] = array();
                $user_form['username']['extra_opts']['error'] = array();
                $user_form['username']['extra_opts']['error'][] = _t("USERNAME_ALREADY_EXISTS__USE_A_DIFFERENT_USERNAME");
                $user_form['username']['extra_opts']['required'][] = true;
                $valide = false;
            }
            if (trim($password1) == '') {
                $user_form['password1']['extra_opts'] = array();
                $user_form['password1']['extra_opts']['error'] = array();
                $user_form['password1']['extra_opts']['error'][] = _t("PASSWORD_REQUIRED");
                $user_form['password1']['extra_opts']['required'][] = true;
                $valide = false;
            }
            if (trim($password2) == '') {
                $user_form['password2']['extra_opts'] = array();
                $user_form['password2']['extra_opts']['error'] = array();
                $user_form['password2']['extra_opts']['error'][] = _t("PASSWORD_REQUIRED");
                $user_form['password2']['extra_opts']['required'][] = true;
                $valide = false;
            }
            if ($password1 != $password2) {
                $user_form['password2']['extra_opts'] = array();
                $user_form['password2']['extra_opts']['error'] = array();
                $user_form['password2']['extra_opts']['error'][] = _t("PASSWORD_MISMATCH");
                $user_form['password2']['extra_opts']['required'][] = true;
                $valide = false;
            }
            if (true) { //password match policy
            }
            if ($email != '' && !shn_valid_email($email)) {
                //email not valide
                $user_form['email']['extra_opts'] = array();
                $user_form['email']['extra_opts']['error'] = array();
                $user_form['email']['extra_opts']['error'][] = _t("INVALID_EMAIL_ADDRESS");
                $valide = false;
            }
            $status = ($status == 'active' || $status == 'disable') ? $status : 'disable';


            $this->user_form = $user_form;


            if ($valide == true) {

                $userProfile = new UserProfile();
                $userProfile->username = $username;
                $userProfile->first_name = $firstName;
                $userProfile->last_name = $lastName;
                $userProfile->organization = $organization;
                $userProfile->designation = $designation;
                $userProfile->email = $email;
                $userProfile->address = $address;
                //$userProfile->Save();
                $userConfig = array();
                $userConfig['locale'] = $locale;
                
                
                shn_auth_add_user($username, $password1, $role, $userProfile, $status,$userConfig);
                set_redirect_header('admin', 'user_management');
            }
        }
    }

    public function act_delete_user() {

        //$this->set_event();
        if (isset($_POST['no'])) {
            set_redirect_header('admin', 'user_management');
            return;
        }

        if (!isset($_POST['users'])) {
            shnMessageQueue::addInformation(_t('PLEASE_SELECT_A_USER_TO_DELETE'));
            set_redirect_header('admin', 'user_management');
            exit;
        }

        $this->del_confirm = true;
        if (isset($_POST['yes'])) {
            if (isset($_POST['user']))
                array_push($_POST['users'], $_POST['user']);
            //if multiplt users are selected
            if (is_array($_POST['users'])) {
                foreach ($_POST['users'] as $user) {
                    if ($user == 'admin') {
                        shnMessageQueue::addInformation(_t('YOU_CANNOT_DELETE_THE_ADMINISTRATOR_ACCOUNT_'));
                        continue;
                    }
                    if ($user == $_SESSION['username']) {
                        shnMessageQueue::addInformation(_t('YOU_CANNOT_DELETE_YOURSELF_FROM_THE_LIST_OF_USERS_'));
                        continue;
                    }
                    if (key(acl_get_user_roles($user)) == 'admin' && $_SESSION['username'] != 'admin') {
                        shnMessageQueue::addInformation(_t('YOU_CANNOT_DELETE_OTHER_ADMIN_USERS_'));
                        continue;
                    }
                    $u = new User();
                    $up = new UserProfile();
                    $up->Delete('username', $user);
                    $u->Delete('username', $user);
                    acl_delete_user($user);
                }
            }
            set_redirect_header('admin', 'user_management');

            return;
        }

        //if there are multiple evets show table 
        $this->users = Browse::getUserListArray($_POST['users']);
    }

    /* }}} */

    /* {{{ Micro thesaauri management */

    public function act_mt_customization() {
        global $conf;
        include_once APPROOT . 'inc/lib_form.inc';
        include_once APPROOT . 'inc/lib_form_util.inc';
        //if the locale is changed need to display extra column in label customization
        $this->locale = $conf['locale'];

        include_once(APPROOT . 'inc/i18n/lib_l10n.inc');
        $this->locales = l10n_get_locals();

        $translations = StringTranslations::getMtTranslations(null, $conf['locale']);

        //display the mt select from
        $mtIndex = new MtIndex();
        $index_terms = $mtIndex->Find('');
        $options = array();
        $options[''] = '';
        foreach ($index_terms as $index_term) {
            $label = $index_term->no . ' - ' . $index_term->term;

            if ($translations[$index_term->no][$conf['locale']]) {
                $label = $translations[$index_term->no][$conf['locale']]['value'];
            }
            $options[$index_term->no] = $label;
        }

        $this->mt_select = $_GET['mt_select'];
        $this->sub_act = (isset($_REQUEST['sub_act'])) ? $_REQUEST['sub_act'] : 'label';
        $_REQUEST['sub_act'] = $this->sub_act;


        //include select entity form        
        include_once 'lib_mt_customization.inc';
        $this->customization_form = $customization_form;

        if (isset($this->mt_select)) {
            //handle delete requests
            if ($_POST['bulkaction'] && !$_POST['vocab_number_list']) {
                shnMessageQueue::addError(_t('Please select items to perform action'));
            }
            if (isset($_POST['bulkaction']) && $_POST['bulkaction'] == "deleteselected") {
                $this->has_children = false;
                foreach ($_POST['vocab_number_list'] as $vocab_number => $v) {
                    $this->has_children = $this->has_children || mt_customization_has_children($vocab_number);
                }

                if (!$this->has_children) {
                    mt_customization_delete($this->mt_select, $_POST['vocab_number_list']);
                } else {
                    $this->haschildren = true;
                }
            } else if (isset($_POST['delete_yes'])) {
                mt_customization_delete($this->mt_select, $_POST['vocab_number_list']);
            } elseif ($this->sub_act == 'order' && isset($_POST['itemsorder'])) {
                $itemorders = @json_decode(stripslashes($_POST['itemsorder']), true);
                if (is_array($itemorders)) {
                    mt_customization_update_terms_order($itemorders);
                }
            } elseif ($this->sub_act == 'label' && ( isset($_POST['update']) || $_POST['bulkaction'] == "updateselected")) {
                mt_customization_add_terms($this->mt_select);
                mt_customization_update();
            } elseif ($this->sub_act == 'label' && $_POST['bulkaction'] == "visible") {
                mt_customization_add_terms($this->mt_select);
                mt_visibility_update($_POST['vocab_number_list'], "y");
            } elseif ($this->sub_act == 'label' && $_POST['bulkaction'] == "disable") {
                mt_customization_add_terms($this->mt_select);
                mt_visibility_update($_POST['vocab_number_list'], "n");
            }

            $mtTerms = new MtTerms();

            if ($this->sub_act == 'label') {
                $this->res = Browse::getHuriTermsTranslations($this->mt_select, $this->locales);
            } elseif ($this->sub_act == 'order') {
                $this->res = MtFieldWrapper::getMTList($this->mt_select);
            }
        }
    }

    public function act_mt_translate() {
        global $conf, $global;
        include_once APPROOT . 'inc/lib_form.inc';
        include_once APPROOT . 'inc/lib_form_util.inc';
        //if the locale is changed need to display extra column in label customization
        $this->locale = $conf['locale'];

        include_once(APPROOT . 'inc/i18n/lib_l10n.inc');
        $locales = l10n_get_locals();
        $this->locales = $locales;
        $mtIndex = new MtIndex();
        $index_terms = $mtIndex->Find('');
        $mts = array();
        foreach ($index_terms as $index_term) {
            $mts[$index_term->no] = $index_term->term;
        }
        $this->mts = $mts;
        if ($_POST['save']) {
            foreach ($index_terms as $index_term) {

                $no = $index_term->no;
                $term = $index_term->term;
                if (isset($_POST['label_' . $no]) && is_array($_POST['label_' . $no])) {
                    $labels = $_POST['label_' . $no];
                    foreach ($locales as $code => $loc) {
                        if (!trim($labels[$code])) {
                            continue;
                        }
                        $l10nValues = array();
                        $l10nValues['value'] = $global['db']->qstr($labels[$code]);
                        $l10nValues['name'] = "'mt-" . $no . "-label'";
                        $l10nValues['context'] = "'mt'";
                        $l10nValues['language'] = "'{$code}'";
                        $l10nValues['status'] = "1";
                        $global['db']->Replace('string_translations', $l10nValues, array('context', 'name', 'language'));
                    }
                }
            }
        }



        $this->translations = StringTranslations::getMtTranslations();
    }

    private function normalise_menu_order($itemorders, &$newresult, $parent = 0, $term_level = 0, &$term_order = 0) {
        if (is_array($itemorders)) {
            foreach ($itemorders as &$itemorder) {
                $id = $itemorder['id'];
                $slug = $itemorder['slug'];
                $title = $itemorder['title'];
                $itemorder['title'] = $_POST['menu-item-title'][$id];

                $term_order++;
                $itemorder['order'] = (int) $term_order;
                $itemorder['level'] = (int) $term_level;
                $itemorder['parent'] = $parent;
                $children = $itemorder["children"];
                unset($itemorder["children"]);
                $newresult[$id] = $itemorder;
                if (is_array($children)) {
                    $term_order = $this->normalise_menu_order($children, $newresult, $itemorder['id'], $term_level + 1, $term_order);
                }
            }
        }
    }

    public function act_menu() {
        global $conf;
        $activemenu = $_REQUEST['activemenu'];

        $defaultMenuItems = getDefaultMenuItems();
        $menuNames = getMenus();

        if (!$activemenu || !isset($menuNames[$activemenu])) {
            $activemenu = "top_menu";
        }
        $this->activemenu = $activemenu;
        $this->menuNames = $menuNames;



        $defaulMenuItemsOrdered = array();
        $order = 0;
        $slugToID = array();

        foreach ($defaultMenuItems as $key => $value) {
            $value['slug'] = $key;
            $defaulMenuItemsOrdered[] = $value;
        }

        $activeMenuItems = getMenu($activemenu);

        if (isset($_POST["save"])) {
            $itemorders = @json_decode(stripslashes($_POST['itemsorder']), true);
            if (is_array($itemorders)) {
                $newresult = array();
                $this->normalise_menu_order($itemorders, $newresult);
                //var_dump($newresult,'<br/><br/><br/><br/><br/>',$itemorders);exit;

                shn_config_database_update($activemenu, serialize($newresult));
                $conf[$activemenu] = serialize($newresult);
                shnMessageQueue::addInformation(_t('Menu was saved successfully.'));
            }
        }
        $this->activeMenuItems = $activeMenuItems;
        if ($conf[$activemenu]) {
            $acMenu = @unserialize($conf[$activemenu]);
            if ($acMenu) {
                $this->activeMenuItems = $acMenu;
            }
        }
        $this->defaultMenuItems = $defaultMenuItems;
        $this->defaulMenuItemsOrdered = $defaulMenuItemsOrdered;
    }

    /* }}} */

    /* {{{ Acl functions */

    public function act_acl() {
        global $global;
        include_once(APPROOT . '3rd/phpgacl/gacl_api.class.php');
        $gacl = new gacl_api(array('db' => $global['db'], 'db_table_prefix' => 'gacl_'));

        $parent_id = $gacl->get_group_id('users', 'Users', 'ARO');
        if (isset($_POST['add_role']) && isset($_POST['role_name'])) {
            $value = str_ireplace(" ", "_", $_POST['role_name']);
            $gacl->add_group($value, $_POST['role_name'], $parent_id, 'ARO');
        }

        $this->modules = array('admin' => _t('ADMIN'), 'analysis' => _t('ANALYSIS'),
            'events' => _t('EVENTS'), 'person' => _t('PERSON'),
            'docu' => _t('DOCUMENTS'), 'dashboard' => _t('Dashboard')/* ,'help'=>'Help','home'=>'Home' */);
        $modules = $gacl->get_objects('modules', '0', 'AXO');

        //add the user to acl list
        $group_id = $gacl->get_group_id('users', 'Users', 'ARO');
        $roles = $gacl->get_group_children($group_id, 'ARO', 'NO_RECURSE');
        foreach ($roles as $role_id) {
            $role = $gacl->get_group_data($role_id, 'ARO');
            $groups[$role_id] = $role[3];

            $roles_user = $gacl->get_group_children($role_id, 'ARO', 'NO_RECURSE');
            foreach ($roles_user as $role_id_user) {
                $role_user = $gacl->get_group_data($role_id_user, 'ARO');
                $groups[$role_id_user] = $role_user[3];
            }
        }
        $this->roles = $groups;

        if (isset($_POST['submit'])) {
            foreach ($groups as $id => $role) {
                if ($role == 'Admin')
                    continue;
                $axo_array['modules'] = array();
                foreach ($this->modules as $key => $module) {
                    if (isset($_POST[$key . '_' . $id])) {
                        array_push($axo_array['modules'], $key);
                    }
                }

                $acl_ids = $gacl->search_acl(FALSE, FALSE, FALSE, FALSE, $role, FALSE, FALSE, FALSE, FALSE);
                $gacl->add_acl(array('access' => array('access')), NULL, array($id), $axo_array);

                //delete other acl
                foreach ($acl_ids as $acl_id)
                    $gacl->del_acl($acl_id);
            }
        }

        foreach ($groups as $id => $role) {
            $acl_ids = $gacl->search_acl(FALSE, FALSE, FALSE, FALSE, $role, FALSE, 'modules', FALSE, FALSE);
            foreach ($acl_ids as $acl_id) {
                $acl = $gacl->get_acl($acl_id);
                $select = $acl['axo']['modules'];
                if (is_array($select)) {
                    foreach ($select as $mod) {
                        $this->selected[$mod . '_' . $id] = 'checked="true"';
                    }
                }
            }
        }
    }

    public function act_permissions() {
        global $global;
        include_once(APPROOT . '3rd/phpgacl/gacl_api.class.php');
        $gacl = new gacl_api(array('db' => $global['db'], 'db_table_prefix' => 'gacl_'));
        //select role
        $this->roles = acl_get_roles();

        if (isset($_REQUEST['role']))
            $this->role = $_REQUEST['role'];

        //change role if requested
        if (isset($_POST['change_role']))
            $this->role = $_POST['role'];

        if (!array_key_exists($this->role, $this->roles))
            $this->role = key($this->roles);

        $role_id = $gacl->get_group_id($this->role, NULL, 'ARO');
        $role_name = $gacl->get_group_data($role_id, 'ARO');
        $role_name = $role_name[3];
        //list accessible modules
        $options = $gacl->get_objects('crud', 0, 'ACO');
        $this->crud = $options['crud'];
        $group_id = $gacl->get_group_id('entities', 'Entities', 'AXO');
        $entity_groups = $gacl->get_group_children($group_id, 'AXO', 'NO_RECURSE');
        $this->entity_groups = array();
        foreach ($entity_groups as $id) {
            $group = $gacl->get_group_data($id, 'AXO');
            $this->entity_groups[$group[2]] = _t($group[3]);
        }
        //get the deny list
        $acl_list = array();
        $this->select = array();
        foreach ($this->entity_groups as $key => $group) {
            $acl_id = $gacl->search_acl('crud', FALSE, FALSE, FALSE, $role_name, FALSE, FALSE, $group, FALSE);
            if ($acl_id)
                $acl_list = array_merge($acl_list, $acl_id);
            $acl = $gacl->get_acl($acl_id[0]);
            if ($acl['allow'] == 0) {
                $crud = $acl['aco']['crud'];
            }
            foreach ($this->crud as $opt) {
                $this->select[$key . "_" . $opt] = true;
                if (is_array($crud) && in_array($opt, $crud))
                    $this->select[$key . "_" . $opt] = false;
            }
        }
        //list accessible entities
        if ($_POST['change_permissions']) {
            if ($this->role == 'admin') {
                shnMessageQueue::addInformation(_t('YOU_CANNOT_CHANGE_THE_ADMINISTRATOR_PERMISSIONS_'));
            } else {
                $this->select = array();
                foreach ($this->entity_groups as $key => $group) {
                    $crud = array();
                    foreach ($this->crud as $opt) {
                        if (!isset($_POST[$key . '_' . $opt]))
                            array_push($crud, $opt);
                        $this->select[$key . "_" . $opt] = true;
                        if (is_array($crud) && in_array($opt, $crud))
                            $this->select[$key . "_" . $opt] = false;
                    }
                    $axo_id = $gacl->get_group_id($key, $group, 'AXO');
                    if (count($crud) > 0)
                        $gacl->add_acl(array('crud' => $crud), NULL, array($role_id), NULL, array($axo_id), 0, 1);
                }

                //delete previous acls
                foreach ($acl_list as $acl_id)
                    $gacl->del_acl($acl_id);
            }
        }
    }

    public function act_acl_mode() {
        global $conf;
        include_once('lib_user.inc');
        $this->current_acl_mode = $conf['acl_mode'];
        $this->modes = user_get_acl_modes();
        if (isset($_POST['update_acl_mode'])) {
            $conf['acl_mode'] = $_POST['acl_mode'];
            shn_config_database_update('acl_mode', $_POST['acl_mode']);
            //redirect to same page to refresh the strings
            set_redirect_header('admin', 'acl_mode');
        }
    }

    /* }}} */

    /* {{{ Localization functions */

    public function act_set_locale() {
        global $conf,$global;
        include_once(APPROOT . 'inc/i18n/lib_l10n.inc');
        $this->locales = l10n_get_locals();
        
	
        if($results = $global['db']->GetOne("SELECT value FROM config WHERE confkey = 'locale'")) {
            $this->current_locale = $results;
        }else{
            $this->current_locale = $conf['locale'];
        }
        if (isset($_POST['update_locale'])) {
            $conf['locale'] = $_POST['locale'];
            shn_config_database_update('locale', $_POST['locale']);
            //redirect to same page to refresh the strings
            set_redirect_header('admin', 'set_locale');
        }
    }

    public function act_change_print() {
        global $conf;
        if (isset($_POST["save"])) {
            $keys = array('print_report_header', 'print_event_header', 'print_person_header'); // 'print_event_sidebar', , 'print_person_sidebar');
            foreach ($keys as $key) {
                $value = $_POST[$key];
                $conf[$key] = $value;
                shn_config_database_update($key, $value);
            }
        }
    }

    public function act_manage_locale() {
        global $conf;
        include_once(APPROOT . 'inc/i18n/lib_l10n.inc');

        if (isset($_POST['add_locale'])) {
            if (isset($_POST['new_locale']) && isset($_POST['locale_folder'])) {
                $locale_folder = str_replace(array('\\', '/'), array('_', '_'), $_POST['locale_folder']);
                l10n_new_locale($_POST['new_locale'], $locale_folder);
            }
        }
        if (isset($_POST['remove_locale'])) {
            if ($conf['locale'] == $_POST['select_locale']) {
                shnMessageQueue::addInformation(_t('YOU_CANNOT_DELETE_THE_DEFAULT_LANGUAGE'));
            } else {
                l10n_remove_locale($_POST['select_locale']);
            }
        }

        $this->locales = l10n_get_locals();
    }

    public function act_translate() {
        global $conf;
        include_once(APPROOT . 'inc/i18n/lib_l10n.inc');
        $this->current_locale = $conf['locale'];
        $this->locales = l10n_get_locals();
        $this->messages = l10n_get_messages($conf['locale']);

        if (isset($_POST['save_messages'])) {
            l10n_set_messages($conf['locale'], $_POST['messages']);
            $this->messages = $_POST['messages'];
        }
        unset($_POST['messages']);

        if (isset($_POST['enable_translator'])) {
            $_SESSION['translator'] = true;
            set_redirect_header('admin', 'translate');
        }

        if (isset($_REQUEST['disable_translator'])) {
            $_SESSION['translator'] = false;
            set_redirect_header('admin', 'translate');
        }

        if (isset($_REQUEST['compile'])) {
            l10n_compile($conf['locale']);
        }
    }

    public function act_update_po() {
        global $conf;
        include_once(APPROOT . 'inc/i18n/lib_l10n.inc');
        $return = l10n_update_po($_POST['msgid'], $_POST['msgstr'], $conf['locale']);
        if ($return)
            echo "true";
        else
            echo "false";
    }

    /* }}} */

    /* {{{ Data Import/Export */

    public function act_import() {
        global $conf;
        global $global;
        global $errors;
        //if a file is uploadded process that file 
        if (is_uploaded_file($_FILES['xml']['tmp_name'])) {
            //move upload file to a temporary location
            $error = include_once('import_xml.inc');
            if ($error)
                return;

            $global['errorindex'];
            $global['export_instance'];
            $global['export_date'];
            $global['$export_time'];
            $global['errorindex'] = new ImportLog();
            $this->index_terms = $global['errorindex']->Find('');
            if (count($errors) > 0) {
                $string;
                shnMessageQueue::addError(_t('SOME_ERRORS_OCCURED_WHILE_IMPORTING_DATA_'));

                mkdir($conf["media_dir"] . "Import_Error_log", 0755);
                $filename = 'error_report_' . date('Ymd-His') . '.txt';
                $file_path = $conf['media_dir'] . "/Import_Error_log/" . $filename;
                $filehandler = fopen($file_path, 'w') or die("Can't open the file");

                foreach ($errors as $error) {
                    $data = "{$error['key']}  {$error['msg']}\n";
                    fwrite($filehandler, $data);
                }
                $_SESSION['report_filename'] = $filename;
                $_SESSION['file_path'] = $file_path;


                $global['errorindex']->file_name = $filename;
                $global['errorindex']->file_path = $file_path;
                $global['errorindex']->date = date("Y/m/d");
                $global['errorindex']->time = date("H:i:s");
                $global['errorindex']->status = "Error";
                $global['errorindex']->export_instance = $global['export_instance'];
                $global['errorindex']->export_date = $global['export_date'];
                $global['errorindex']->export_time = $global['$export_time'];
                $global['errorindex']->Save();

                fclose($filehandler);
                $this->errors = $errors;
            } else {
                $global['errorindex']->date = date("Y/m/d");
                $global['errorindex']->time = date("H:i:s");
                $global['errorindex']->status = "Successful";
                $global['errorindex']->export_instance = $global['export_instance'];
                $global['errorindex']->export_date = $global['export_date'];
                $global['errorindex']->export_time = $global['$export_time'];
                $global['errorindex']->Save();
                shnMessageQueue::addInformation(_t('OPENEVSYS_HAS_SUCCESSFULLY_IMPORTED_THE_DATA_'));
            }
        } else if (isset($_POST['upload'])) {
            shnMessageQueue::addError(_t("PLEASE_UPLOAD_A_VALID_OPENEVSYS_XML_FILE_"));
        }
        if ($_GET['message'] == 'error') {
            $filename = $_SESSION['report_filename'];
            $file_path = $_SESSION['file_path'];
            $size = filesize($file_path);

            header("Content-Type: text/plane");
            header("Content-Disposition: attachment; filename=" . $filename);
            header("Content-Length: " . $size);

            $fp = fopen($file_path, 'rb');
            fpassthru($fp);

            exit();
        }
    }

    public function act_downloadErroReport() {
        //get file id
        //load document detaild
        $supporting_docs_meta = new SupportingDocsMeta();
        $supporting_docs_meta->LoadfromRecordNumber($_GET['doc_id']);
        $supporting_docs_meta->LoadRelationships();

        $supporting_docs = new SupportingDocs();
        $supporting_docs->LoadfromRecordNumber($_GET['doc_id']);
        //set headers

        if ($supporting_docs->uri != null) {
            $ext = shn_file_findexts($supporting_docs->uri);
            //fetch document
            //stream document
            $title = $supporting_docs_meta->title;
            $file_name = str_replace(" ", "_", $title);
            header("Content-Type: application/$ext");
            header("Content-Disposition: attachment; filename=" . urlencode("$file_name.$ext"));
            header("Content-Length: " . filesize($supporting_docs->uri));
            $fp = fopen($supporting_docs->uri, 'rb');
            fpassthru($fp);
            //inthis case we dont need to go to the templates so exit from the script
        } else {
            shnMessageQueue::addInformation('No attachment found to this document.');
            set_redirect_header('docu', 'view_document', null, null);
        }
        exit();
    }

    public function act_export_ui() {
        
    }

    public function act_export() {
        include_once('export_xml.inc');
    }

    /* }}} */

    public function act_System_configuration() {
        global $alt_conf, $alt_conf_check, $conf;
        require_once(APPROOT . 'conf/conf_meta.php');

        if (isset($_POST["submit"])) {

            $this->conf = $conf;
            unset($_POST["submit"]);

            $this->alt_conf_check = $alt_conf_check;
            foreach ($alt_conf_check as $key => $value) {
                if (!isset($_POST[$key])) {
                    $_POST[$key] = false;
                }
            }

            foreach ($_POST as $key => $value) {
                $conf[$key] = $value;
                shn_config_database_update($key, $value);
            }
        }
    }
    public function act_dashboard_configuration() {
        global  $conf;


        if (isset($_POST["submit"])) {

            $this->conf = $conf;
            unset($_POST["submit"]);
            $formats = getActiveFormats();

            foreach ($formats as $format => $value) {

                if (!isset($_POST['dashboard_format_counts_'.$format])) {
                    $_POST['dashboard_format_counts_'.$format] = false;
                }
            }
            if (!isset($_POST['dashboard_select_counts'])) {
                $_POST['dashboard_select_counts'] = array();
            }
            if (!isset($_POST['dashboard_date_counts'])) {
                $_POST['dashboard_date_counts'] = array();
            }
            foreach ($_POST as $key => $value) {
                if($key == 'dashboard_select_counts'){
                    $value = json_encode($value);
                }
                if($key == 'dashboard_date_counts'){
                    $value = json_encode($value);
                }
                $conf[$key] = $value;

                shn_config_database_update($key, $value);
            }
        }
    }

    public function act_Extensions() {
        global $conf;

        if (isset($conf['extension'])) {
            $ext_url = $conf['extension'];
        }
    }

}
