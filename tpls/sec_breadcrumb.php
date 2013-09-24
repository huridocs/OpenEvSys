<?php
$module = get_module();
$action = get_action();
$breadcrumbs = shnBreadcrumbs::getBreadcrumbs();

$title = "";
$title_pre = "";
if ($module == "events") {
    global $event;
    if ($event) {
        $title = htmlspecialchars($event->event_title);
        $title_pre = _t('EVENT_TITLE');
    }
    switch ($action) {
        case 'new_event':
            $breadcrumbs->pushCrumb(array('name' => _t('NEW_EVENT'), 'mod' => 'events', 'act' => 'new_event'), 1);
            break;
        case 'get_event':
            $breadcrumbs->pushCrumb(array('name' => _t('VIEW_EVENT'), 'mod' => 'events', 'act' => 'get_event'), 1);
            break;
        case 'vp_list':
            $breadcrumbs->pushCrumb(array('name' => _t('VIEW_EVENT'), 'mod' => 'events', 'act' => 'get_event'), 1);
            $breadcrumbs->pushCrumb(array('name' => _t('VICTIMS_AND_PERPETRATORS'), 'mod' => 'events', 'act' => 'vp_list'), 2);
            break;
        case 'src_list':
            $breadcrumbs->pushCrumb(array('name' => _t('VIEW_EVENT'), 'mod' => 'events', 'act' => 'get_event'), 1);
            $breadcrumbs->pushCrumb(array('name' => _t('SOURCES'), 'mod' => 'events', 'act' => 'src_list'), 2);
            break;
        case 'intv_list':
            $breadcrumbs->pushCrumb(array('name' => _t('VIEW_EVENT'), 'mod' => 'events', 'act' => 'get_event'), 1);
            $breadcrumbs->pushCrumb(array('name' => _t('INTERVENTIONS'), 'mod' => 'events', 'act' => 'intv_list'), 2);
            break;
        case 'coe_list':
            $breadcrumbs->pushCrumb(array('name' => _t('VIEW_EVENT'), 'mod' => 'events', 'act' => 'get_event'), 1);
            $breadcrumbs->pushCrumb(array('name' => _t('CHAIN_OF_EVENTS'), 'mod' => 'events', 'act' => 'coe_list'), 2);
            break;
        case 'doc_list':
            $breadcrumbs->pushCrumb(array('name' => _t('VIEW_EVENT'), 'mod' => 'events', 'act' => 'get_event'), 1);
            $breadcrumbs->pushCrumb(array('name' => _t('DOCUMENTS'), 'mod' => 'events', 'act' => 'doc_list'), 2);
            break;
        case 'audit':
            $breadcrumbs->pushCrumb(array('name' => _t('VIEW_EVENT'), 'mod' => 'events', 'act' => 'get_event'), 1);
            $breadcrumbs->pushCrumb(array('name' => _t('AUDIT_LOG'), 'mod' => 'events', 'act' => 'audit'), 2);
            break;
        case 'permissions':
            $breadcrumbs->pushCrumb(array('name' => _t('VIEW_EVENT'), 'mod' => 'events', 'act' => 'get_event'), 1);
            $breadcrumbs->pushCrumb(array('name' => _t('PERMISSIONS'), 'mod' => 'events', 'act' => 'permissions'), 2);
            break;
        case 'browse':
        default:
            //$breadcrumbs->pushCrumb(array('name' => _t('BROWSE_EVENT'), 'mod' => 'events', 'act' => 'browse'), 1);
            break;
    }
} elseif ($module == "person") {
    global $person;
    if ($person) {
        $title = htmlspecialchars($person->person_name);
        $title_pre = _t('PERSON_NAME');
    }
    switch ($action) {
        case 'new_person':
            $breadcrumbs->pushCrumb(array('name' => _t('NEW_PERSON'), 'mod' => 'person', 'act' => 'new_person'), 1);
            break;
        case 'person':
            $breadcrumbs->pushCrumb(array('name' => _t('VIEW_PERSON'), 'mod' => 'person', 'act' => 'person'), 1);
            break;
        case 'biography_list':
            $breadcrumbs->pushCrumb(array('name' => _t('VIEW_PERSON'), 'mod' => 'person', 'act' => 'person'), 1);
            $breadcrumbs->pushCrumb(array('name' => _t('BIOGRAPHIC_DETAIL_S_'), 'mod' => 'person', 'act' => 'biography_list'), 2);
            break;
        case 'role_list':
            $breadcrumbs->pushCrumb(array('name' => _t('VIEW_PERSON'), 'mod' => 'person', 'act' => 'person'), 1);
            $breadcrumbs->pushCrumb(array('name' => _t('ROLE_LIST'), 'mod' => 'person', 'act' => 'role_list'), 2);
            break;
        case 'audit_log':
            $breadcrumbs->pushCrumb(array('name' => _t('VIEW_PERSON'), 'mod' => 'person', 'act' => 'person'), 1);
            $breadcrumbs->pushCrumb(array('name' => _t('AUDIT_LOG'), 'mod' => 'person', 'act' => 'audit_log'), 2);
            break;
        case 'address_list':
            $breadcrumbs->pushCrumb(array('name' => _t('VIEW_PERSON'), 'mod' => 'person', 'act' => 'person'), 1);
            $breadcrumbs->pushCrumb(array('name' => _t('PERSON_ADDRESS_ES_'), 'mod' => 'person', 'act' => 'address_list'), 2);
            break;
        case 'permissions':
            $breadcrumbs->pushCrumb(array('name' => _t('VIEW_PERSON'), 'mod' => 'person', 'act' => 'person'), 1);
            $breadcrumbs->pushCrumb(array('name' => _t('PERMISSIONS'), 'mod' => 'person', 'act' => 'permissions'), 2);
            break;
        case 'browse':
        default:
            //$breadcrumbs->pushCrumb(array('name' => _t('BROWSE_PERSON'), 'mod' => 'person', 'act' => 'browse'), 1);
            break;
    }
} elseif ($module == "docu") {
    global $supporting_docs_meta;
    if ($supporting_docs_meta) {
        $title = htmlspecialchars($supporting_docs_meta->title);
        $title_pre = _t('DOCUMENT_TITLE');
    }
    switch ($action) {
        case 'new_document':
            $breadcrumbs->pushCrumb(array('name' => _t('NEW_DOCUMENT'), 'mod' => 'docu', 'act' => 'new_document'), 1);
            break;
        case 'view_document':
            $breadcrumbs->pushCrumb(array('name' => _t('VIEW_DOCUMENT'), 'mod' => 'docu', 'act' => 'view_document'), 1);
            break;
        case 'link':
            $breadcrumbs->pushCrumb(array('name' => _t('VIEW_DOCUMENT'), 'mod' => 'docu', 'act' => 'view_document'), 1);
            $breadcrumbs->pushCrumb(array('name' => _t('LINKS'), 'mod' => 'docu', 'act' => 'link'), 2);
            break;
        case 'audit':
            $breadcrumbs->pushCrumb(array('name' => _t('VIEW_DOCUMENT'), 'mod' => 'docu', 'act' => 'view_document'), 1);
            $breadcrumbs->pushCrumb(array('name' => _t('AUDIT_LOG'), 'mod' => 'docu', 'act' => 'audit'), 2);
            break;

        case 'browse':
        default:
            //$breadcrumbs->pushCrumb(array('name' => _t('BROWSE_DOCUMENT'), 'mod' => 'docu', 'act' => 'browse'), 1);
            break;
    }
}elseif($module == "analysis"){
     switch ($action) {
        case 'adv_search':
            $breadcrumbs->pushCrumb(array('name' => _t('ADVANCED_SEARCH'), 'mod' => 'analysis', 'act' => 'adv_search'), 1);
            break;
      
       case 'search_query':
            $breadcrumbs->pushCrumb(array('name' => _t('SAVED_QUERIES'), 'mod' => 'analysis', 'act' => 'search_query'), 1);
            break;
       case 'facetsearch':
            $breadcrumbs->pushCrumb(array('name' => _t('Charts and map'), 'mod' => 'analysis', 'act' => 'facetsearch'), 1);
            break;
      default:
            //$breadcrumbs->pushCrumb(array('name' => _t('BROWSE_DOCUMENT'), 'mod' => 'analysis', 'act' => 'browse'), 1);
            break;
    }
}elseif($module == "admin"){
    switch ($action) {
                    case 'field_customization':
                        $breadcrumbs->pushCrumb(array('name' => _t('EXISTING_FIELDS'), 'mod' => 'admin', 'act' => 'field_customization'), 1);
                        break;
                    case 'new_field':
                        $breadcrumbs->pushCrumb(array('name' => _t('ADD_NEW_FIELD'), 'mod' => 'admin', 'act' => 'new_field'), 1);
                        break;
                    case 'mt_customization':
                        $breadcrumbs->pushCrumb(array('name' => _t('MICRO_THESAURI'), 'mod' => 'admin', 'act' => 'mt_customization'), 1);
                        break;
                    case 'user_management':
                        $breadcrumbs->pushCrumb(array('name' => _t('USER_MANAGEMENT'), 'mod' => 'admin', 'act' => 'user_management'), 1);
                        break;
                    case 'add_user':
                        $breadcrumbs->pushCrumb(array('name' => _t('USER_MANAGEMENT'), 'mod' => 'admin', 'act' => 'user_management'), 1);
                        $breadcrumbs->pushCrumb(array('name' => _t('ADD_NEW_USER'), 'mod' => 'admin', 'act' => 'add_user'), 2);
                        break;
                    case 'acl':
                        $breadcrumbs->pushCrumb(array('name' => _t('USER_MANAGEMENT'), 'mod' => 'admin', 'act' => 'user_management'), 1);
                        $breadcrumbs->pushCrumb(array('name' => _t('ROLES___MODULE_ACCESS_CONTROL'), 'mod' => 'admin', 'act' => 'acl'), 2);
                        break;
                    case 'permissions':
                        $breadcrumbs->pushCrumb(array('name' => _t('USER_MANAGEMENT'), 'mod' => 'admin', 'act' => 'user_management'), 1);
                        $breadcrumbs->pushCrumb(array('name' => _t('PERMISSIONS'), 'mod' => 'admin', 'act' => 'permissions'), 2);
                        break;
                    case 'set_locale':
                        $breadcrumbs->pushCrumb(array('name' => _t('LOCALIZATION'), 'mod' => 'admin', 'act' => 'set_locale'), 1);
                        break;
                    case 'System_configuration':
                        $breadcrumbs->pushCrumb(array('name' => _t('SYSTEM_CONFIGURATION'), 'mod' => 'admin', 'act' => 'System_configuration'), 1);
                        break;
                    case 'Extensions':
                        $breadcrumbs->pushCrumb(array('name' => _t('EXTENSIONS'), 'mod' => 'admin', 'act' => 'Extensions'), 1);
                        break;

                    default:
                        //$breadcrumbs->pushCrumb(array('name' => _t('BROWSE_DOCUMENT'), 'mod' => 'admin', 'act' => 'browse'), 1);
                        break;
                }

}elseif ($module == "home") {

    switch ($action) {
        case 'edit_user':
            $breadcrumbs->pushCrumb(array('name' => _t('EDIT_PROFILE'), 'mod' => 'home', 'act' => 'edit_user'), 1);
            break;
        case 'edit_password':
            $breadcrumbs->pushCrumb(array('name' => _t('CHANGE_PASSWORD'), 'mod' => 'home', 'act' => 'edit_password'), 1);
            break;
        case 'edit_security':
            $breadcrumbs->pushCrumb(array('name' => _t('Security'), 'mod' => 'home', 'act' => 'edit_security'), 1);
            break;
        default:
            //$breadcrumbs->pushCrumb(array('name' => _t('BROWSE_DOCUMENT'), 'mod' => 'home', 'act' => 'browse'), 1);
            break;
    }
}
?>
<div class="row-fluid">
    <div class="span12">
        <?php
        $breadcrumbs->renderBreadcrumbs();
        ?>

    </div>
</div>
<?php
if ($title && !in_array($action,array("browse_act","browse_intervention"))) {
    ?>
    <h3 class="breadcrumb" style="padding-top:4px;padding-bottom: 4px"><?php echo '<span>' . $title_pre . '</span> : <span>' . $title . '</span>'; ?></h3>

    <?php
}
?>