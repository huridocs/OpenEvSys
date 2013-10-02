<?php
global $conf;
$module = get_module();
$action = get_action();


if ($module == "events") {
    $id = (isset($_GET['eid'])) ? $_GET['eid'] : $_SESSION['eid'];
} elseif ($module == "person") {
    $id = (isset($_GET['pid'])) ? $_GET['pid'] : $_SESSION['pid'];
} elseif ($module == "docu") {
    $id = (isset($_GET['doc_id'])) ? $_GET['doc_id'] : $_SESSION['doc_id'];
}

$menus = array("events" => array(), "person" => array(), "docu" => array(), "analysis" => array(), "home" => array());

$menus["events"]["get_event"] = array(
    "title" => _t('EVENT_DESCRIPTION'),
    "url" => get_url('events', 'get_event', null, array('eid' => $id), null, true));

$menus["events"]["new_event"] = array("alias" => "get_event");
$menus["events"]["edit_event"] = array("alias" => "get_event");
$menus["events"]["delete_event"] = array("alias" => "get_event");

$menus["events"]["vp_list"] = array(
    "title" => _t('VICTIMS_AND_PERPETRATORS'),
    "url" => get_url('events', 'vp_list', null, array('eid' => $id), null, true));

$menus["events"]["add_act"] = array("alias" => "vp_list");
$menus["events"]["add_ad"] = array("alias" => "vp_list");
$menus["events"]["add_involvement"] = array("alias" => "vp_list");
$menus["events"]["add_perpetrator"] = array("alias" => "vp_list");
$menus["events"]["add_victim"] = array("alias" => "vp_list");
$menus["events"]["delete_act"] = array("alias" => "vp_list");
$menus["events"]["delete_ad"] = array("alias" => "vp_list");
$menus["events"]["edit_act"] = array("alias" => "vp_list");
$menus["events"]["edit_ad"] = array("alias" => "vp_list");
$menus["events"]["edit_involvement"] = array("alias" => "vp_list");
$menus["events"]["edit_perpetrator"] = array("alias" => "vp_list");
$menus["events"]["edit_victim"] = array("alias" => "vp_list");

$menus["events"]["src_list"] = array(
    "title" => _t('SOURCES'),
    "url" => get_url('events', 'src_list', null, array('eid' => $id), null, true));
$menus["events"]["add_information"] = array("alias" => "src_list");
$menus["events"]["add_source"] = array("alias" => "src_list");
$menus["events"]["delete_information"] = array("alias" => "src_list");
$menus["events"]["edit_information"] = array("alias" => "src_list");
$menus["events"]["edit_source"] = array("alias" => "src_list");

$menus["events"]["intv_list"] = array(
    "title" => _t('INTERVENTIONS'),
    "url" => get_url('events', 'intv_list', null, array('eid' => $id), null, true));
$menus["events"]["add_intv"] = array("alias" => "intv_list");
$menus["events"]["add_intv_party"] = array("alias" => "intv_list");
$menus["events"]["delete_intervention"] = array("alias" => "intv_list");
$menus["events"]["edit_intv"] = array("alias" => "intv_list");
$menus["events"]["edit_intv_party"] = array("alias" => "intv_list");

$menus["events"]["coe_list"] = array(
    "title" => _t('CHAIN_OF_EVENTS'),
    "url" => get_url('events', 'coe_list', null, array('eid' => $id), null, true));
$menus["events"]["add_coe"] = array("alias" => "coe_list");
$menus["events"]["delete_coe"] = array("alias" => "coe_list");
$menus["events"]["edit_coe"] = array("alias" => "coe_list");

$menus["events"]["doc_list"] = array(
    "title" => _t('DOCUMENTS'),
    "url" => get_url('events', 'doc_list', null, array('eid' => $id), null, true));

$menus["events"]["audit"] = array(
    "title" => _t('AUDIT_LOG'),
    "url" => get_url('events', 'audit', null, array('eid' => $id), null, true));


global $event;
if ($event->confidentiality == 'y') {
    $menus["events"]["permissions"] = array(
        "title" => _t('PERMISSIONS'),
        "url" => get_url('events', 'permissions', null, array('eid' => $id), null, true));
}

//Person
$menus["person"]["person"] = array(
    "title" => _t('PERSON_RECORDS_S_'),
    "url" => get_url('person', 'person', null, array('pid' => $id), null, true));
$menus["person"]["new_person"] = array(
    "alias" => "person");
$menus["person"]["delete_person"] = array(
    "alias" => "person");
$menus["person"]["edit_person"] = array(
    "alias" => "person");

$menus["person"]["address_list"] = array(
    "title" => _t('PERSON_ADDRESS_ES_'),
    "url" => get_url('person', 'address_list', null, array('pid' => $id), null, true));
$menus["person"]["delete_address"] = array(
    "alias" => "address_list");
$menus["person"]["new_address"] = array(
    "alias" => "address_list");
$menus["person"]["edit_address"] = array(
    "alias" => "address_list");

$menus["person"]["biography_list"] = array(
    "title" => _t('BIOGRAPHIC_DETAIL_S_'),
    "url" => get_url('person', 'biography_list', null, array('pid' => $id), null, true));
$menus["person"]["delete_biographic"] = array(
    "alias" => "biography_list");
$menus["person"]["edit_biography"] = array(
    "alias" => "biography_list");
$menus["person"]["new_biography"] = array(
    "alias" => "biography_list");

$menus["person"]["role_list"] = array(
    "title" => _t('ROLE_LIST'),
    "url" => get_url('person', 'role_list', null, array('pid' => $id), null, true));

$menus["person"]["audit_log"] = array(
    "title" => _t('AUDIT_LOG'),
    "url" => get_url('person', 'audit_log', null, array('pid' => $id), null, true));


global $person;
if ($person->confidentiality == 'y') {
    $menus["person"]["permissions"] = array(
        "title" => _t('PERMISSONS'),
        "url" => get_url('person', 'permissions', null, array('pid' => $id), null, true));
}

//Documents
$menus["docu"]["view_document"] = array(
    "title" => _t('DOCUMENT_DETAILS'),
    "url" => get_url('docu', 'view_document', null, array('doc_id' => $id), null, true));
$menus["docu"]["new_document"] = array(
    "alias" => "view_document");
$menus["docu"]["delete_document"] = array(
    "alias" => "view_document");
$menus["docu"]["edit_document"] = array(
    "alias" => "view_document");

$menus["docu"]["link"] = array(
    "title" => _t('LINKS'),
    "url" => get_url('docu', 'link', null, array('doc_id' => $id), null, true));
$menus["docu"]["audit"] = array(
    "title" => _t('AUDIT_LOG'),
    "url" => get_url('docu', 'audit', null, array('doc_id' => $id), null, true));


//Analysis
$menus["analysis"]["adv_search"] = array(
    "title" => _t('ADVANCED_SEARCH'),
    "url" => get_url('analysis', 'adv_search', null, null, null, true));

$menus["analysis"]["search_query"] = array(
    "title" => _t('SAVED_QUERIES'),
    "url" => get_url('analysis', 'search_query', null, null, null, true));

$menus["analysis"]["facetsearch"] = array(
    "title" => _t('Charts and map'),
    "url" => get_url('analysis', 'facetsearch', null, null, null, true));


$menus["analysis"]["adv_report"] = array(
    "alias" => "adv_search");


$menus["dashboard"]["dashboard"] = array(
    "title" => _t('Dashboard'),
    "url" => get_url('dashboard', 'dashboard', null, null, null, true));


//Home
$menus["home"]["edit_user"] = array(
    "title" => _t('EDIT_PROFILE'),
    "url" => get_url('home', 'edit_user', null, null, null, true));

$menus["home"]["edit_password"] = array(
    "title" => _t('CHANGE_PASSWORD'),
    "url" => get_url('home', 'edit_password', null, null, null, true));


$menus["home"]["edit_security"] = array(
    "title" => _t('Security'),
    "url" => get_url('home', 'edit_security', null, null, null, true));



if (in_array($module, array("events", "person", "docu", "analysis", "home"))
        &&  !in_array($action, array("browse", "browse_act", "browse_intervention", "browse_biography","add_act_full"))) {
    $defaultMenuItems = getDefaultMenuItems();
    $activemenu = $module . "_menu";
    $topMenuItems = getMenu($activemenu);
    if ($conf[$activemenu]) {
        $acMenu = @unserialize($conf[$activemenu]);
        if ($acMenu) {
            $topMenuItems = $acMenu;
        }
    }

    $menuItems = $topMenuItems;
    $level = 0;
    if ($menuItems) {
        ?>
        <ul class="nav nav-tabs tabnav"> 
            <?php
            foreach ($menuItems as $key => $menu) {
                $id = $menu['id'];
                $element1 = $menu;
                $element2 = $menuItems[$key + 1];
                //$level = $element1['level'];
                $url = $defaultMenuItems[$menu['slug']]['url'];
                $title = $menu['title'];
                $prefix = '';
                if ($defaultMenuItems[$menu['slug']]['prefix']) {
                    $prefix = $defaultMenuItems[$menu['slug']]['prefix'];
                }
                $mod = $defaultMenuItems[$menu['slug']]['module'];
                $act = $defaultMenuItems[$menu['slug']]['action'];
                $aclmod = $defaultMenuItems[$menu['slug']]['aclmod'];

                if ($aclmod) {
                    if (!acl_is_mod_allowed($aclmod)) {
                        continue;
                    }
                }
                $check = $defaultMenuItems[$menu['slug']]['check'];
                if ($check) {
                    if (!$check()) {
                        continue;
                    }
                }
                $aliases = array();
                if ($defaultMenuItems[$menu['slug']]['aliases']) {
                    $aliases = $defaultMenuItems[$menu['slug']]['aliases'];
                }

                $checkActive = $defaultMenuItems[$menu['slug']]['checkactive'];

                if (!$checkActive) {
                   $checkActive = "checkMenuActiveDefault";
                }
                
                $active = '';                
                if($checkActive($menu)){
                    $active = 'active';
                }
                ?>
                <li class="<?php if ($active) echo $active ?>"><a  href="<?php echo $url ?>"><?php echo $title ?></a>
                </li>
                <?php
            }
            ?>

        </ul>
        <?php
    }
}
/*
  if ($module != "admin" && $action != "browse" && !in_array($action, array("browse_act", "browse_intervention", "browse_biography"))) {
  ?>
  <ul class="nav nav-tabs tabnav">
  <?php
  foreach ($menus[$module] as $menuSec => $menu) {

  $active = false;
  $title = $menu["title"];
  $url = $menu["url"];
  if ($action == $menuSec || (isset($menus[$module][$action]) && isset($menus[$module][$action]["alias"]) && $menus[$module][$action]["alias"] == $menuSec)) {
  if ($url) {
  $active = true;
  } else {
  continue;
  }
  }
  if (!$url) {
  continue;
  }
  ?>
  <li class="<?php if ($active) echo "active" ?>"><a  href="<?php echo $url ?>"><?php echo $title ?></a>
  </li>
  <?php
  }
  ?>

  </ul>
  <?php
  }
 */
?>


</ul>
