<div class="navbar navbar-fixed-top navbar-inverse"  >
    <div class="navbar-inner">
        <div class="container">
            <a class="brand" href="<?php get_url(); ?>" title="OpenEvSys">OpenEvSys</a>
            <ul class="nav">
                <?php
                global $conf;
                $module = get_module();
                $action = get_action();

                $defaultMenuItems = getDefaultMenuItems();
                $activemenu = "top_menu";
                $topMenuItems = getMenu($activemenu);
                if ($conf[$activemenu]) {
                    $acMenu = @unserialize($conf[$activemenu]);
                    if ($acMenu) {
                        $topMenuItems = $acMenu;
                    }
                }

                $menuItems = $topMenuItems;
                $level = 0;
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
                    if ($checkActive($menu)) {
                        $active = 'active';
                        $breadcrumbs->pushCrumb(array('name' => $title, 'mod' => $mod, 'act' => $act), 0);
                    }


                    if ($level > $element1['level']) {
                        $level = $element1['level'];
                        ?>
                    </ul>
                    </li>
                    <?php
                }

                if ($element2['parent'] == $id) {
                    $level++;
                    ?>
                    <li class="dropdown <?php echo $active ?>">
                        <a href="<?php echo $url ?>" class="dropdown-toggle" data-toggle="dropdown">
                            <?php echo $title ?>
                            <span class="caret"></span></a>

                        <ul class="dropdown-menu">

                            <?php
                        } else {
                            ?>
                            <li class="<?php echo $active ?>">
                                <a href="<?php echo $url ?>"><?php if ($prefix) echo $prefix . " "; ?><?php echo $title ?></a>

                            </li>
                            <?php
                        }
                    }
                    echo str_repeat("</ul></li>", $level);
                    
                    /*?>

                    <?php if (acl_is_mod_allowed('events')) { ?>
                        <?php
                        $active = '';
                        if ('events' == $module && !in_array($action, array("browse_act", "browse_intervention"))) {
                            $active = 'active';
                            $breadcrumbs->pushCrumb(array('name' => _t('EVENTS'), 'mod' => 'events', 'act' => 'browse'), 0);
                        }
                        ?>
                        <li class="<?php echo $active ?>"><a href="<?php get_url('events', 'browse') ?>"><?php echo _t('EVENTS') ?></a>

                        </li>
                    <?php } ?>
                    <?php if (acl_is_mod_allowed('person')) { ?>
                        <?php
                        $active = '';
                        if ('person' == $module && $action != "browse_biography") {
                            $active = 'active';
                            $breadcrumbs->pushCrumb(array('name' => _t('PERSONS'), 'mod' => 'person', 'act' => 'browse'), 0);
                        }
                        ?>
                        <li class="<?php echo $active ?>">
                            <a href="<?php get_url('person', 'browse') ?>"><?php echo _t('PERSONS') ?></a></li>

                    <?php } ?>
                    <?php if (acl_is_mod_allowed('docu')) { ?>
                        <?php
                        $active = '';
                        if ('docu' == $module) {
                            $active = 'active';
                            $breadcrumbs->pushCrumb(array('name' => _t('DOCUMENTS'), 'mod' => 'docu', 'act' => 'browse'), 0);
                        }
                        ?>
                        <li class="<?php echo $active ?>">
                            <a href="<?php get_url('docu', 'browse') ?>"><?php echo _t('DOCUMENTS') ?></a></li>

                        <?php
                    }

                    if ($conf['menus']['biography_list']) {
                        if (acl_is_mod_allowed('person')) {
                            ?>
                            <?php
                            $active = '';
                            if ('person' == $module && $action == "browse_biography") {
                                $active = 'active';
                                $breadcrumbs->pushCrumb(array('name' => _t('BIOGRAPHIC_DETAILS'), 'mod' => 'person', 'act' => 'browse'), 0);
                            }
                            ?>
                            <li class="<?php echo $active ?>">
                                <a href="<?php get_url('person', 'browse_biography') ?>"><?php echo _t('BIOGRAPHIC_DETAILS') ?></a></li>

                            <?php
                        }
                    }
                    ?>
                    <?php /* if (acl_is_mod_allowed('events')) { ?>
                      <?php
                      $active = '';
                      if ('events' == $module && $action =="browse_act") {
                      $active = 'active';
                      $breadcrumbs->pushCrumb(array('name' => _t('ACTS'), 'mod' => 'events', 'act' => 'browse_act'), 0);
                      }
                      ?>
                      <li class="<?php echo $active ?>"><a href="<?php get_url('events', 'browse_act') ?>"><?php echo _t('ACTS') ?></a>

                      </li>
                      <?php } ?>
                      <?php if (acl_is_mod_allowed('events')) { ?>
                      <?php
                      $active = '';
                      if ('events' == $module && $action =="browse_intervention") {
                      $active = 'active';
                      $breadcrumbs->pushCrumb(array('name' => _t('INTERVENTIONS'), 'mod' => 'events', 'act' => 'browse_intervention'), 0);
                      }
                      ?>
                      <li class="<?php echo $active ?>"><a href="<?php get_url('events', 'browse_intervention') ?>"><?php echo _t('INTERVENTIONS') ?></a>

                      </li>
                      <?php } */ 
                    /*
                    $active = '';
                    $new_actions_array = array("new_event", "new_person", "new_document", "add_user");
                    if (in_array($action, $new_actions_array)) {
                        // $active = 'active';
                    }
                    ?>
                    <li class="dropdown <?php echo $active ?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <?php echo _t('ADD_NEW') ?>
                            <span class="caret"></span></a>

                        <ul class="dropdown-menu">
                            <?php if (acl_is_mod_allowed('events')) { ?>
                                <li><a href="<?php get_url('events', 'new_event', null, array('eid' => null)) ?>" >
                                        <i class="icon-plus"></i> <?php echo _t('ADD_NEW_EVENT') ?></a></li>
                            <?php } ?>
                            <?php if (acl_is_mod_allowed('person')) { ?>
                                <li><a  href="<?php get_url('person', 'new_person', null, array('pid' => null)) ?>">
                                        <i class="icon-plus"></i>  <?php echo _t('ADD_NEW_PERSON') ?></a></li>

                            <?php } ?>
                            <?php if (acl_is_mod_allowed('docu')) { ?>
                                <li><a href="<?php get_url('docu', 'new_document', null, null) ?>">
                                        <i class="icon-plus"></i> <?php echo _t('ADD_NEW_DOCUMENT') ?></a></li>
                            <?php } ?>
                            <?php if (acl_is_mod_allowed('admin')) { ?>
                                <li><a  href="<?php get_url('admin', 'add_user') ?> " >
                                        <i class="icon-plus"></i> <?php echo _t('ADD_NEW_USER') ?></a>
                                </li>
                            <?php } ?>

                        </ul>
                    </li>
*/
                    ?>
                </ul>
                <div class="btn-group pull-right">
                    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-user"></i> <?php echo htmlspecialchars($_SESSION['username']) ?>
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">

                        <li><a href="<?php get_url('home', 'edit_user') ?>"><i class="icon-pencil"></i> <?php echo _t('MY_PREFERENCES') ?></a></li>
                        <li><a href="?act=logout"><i class="icon-off"></i> <?php echo _t('SIGN_OUT') ?></a></li>

                    </ul>
                </div>
                        <ul class="nav" style="float:right">
                    <?php
                    $activemenu = "top_right_menu";
                $topMenuItems = getMenu($activemenu);
                if ($conf[$activemenu]) {
                    $acMenu = @unserialize($conf[$activemenu]);
                    if ($acMenu) {
                        $topMenuItems = $acMenu;
                    }
                }

                $menuItems = $topMenuItems;
                $level = 0;
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
                    if ($checkActive($menu)) {
                        $active = 'active';
                        $breadcrumbs->pushCrumb(array('name' => $title, 'mod' => $mod, 'act' => $act), 0);
                    }


                    if ($level > $element1['level']) {
                        $level = $element1['level'];
                        ?>
                    </ul>
                    </li>
                    <?php
                }

                if ($element2['parent'] == $id) {
                    $level++;
                    ?>
                    <li class="dropdown <?php echo $active ?>">
                        <a href="<?php echo $url ?>" class="dropdown-toggle" data-toggle="dropdown">
                            <?php echo $title ?>
                            <span class="caret"></span></a>

                        <ul class="dropdown-menu">

                            <?php
                        } else {
                            ?>
                            <li class="<?php echo $active ?>">
                                <a href="<?php echo $url ?>"><?php if ($prefix) echo $prefix . " "; ?><?php echo $title ?></a>

                            </li>
                            <?php
                        }
                    }
                    echo str_repeat("</ul></li>", $level);
                    
                    /*if (acl_is_mod_allowed('dashboard')) { ?>
                        <?php
                        $active = '';
                        if ('dashboard' == $module) {
                            $active = 'active';
                            $breadcrumbs->pushCrumb(array('name' => _t('Dashboard'), 'mod' => 'dashboard', 'act' => 'dashboard'), 0);
                        }
                        ?>
                        <li class="<?php echo $active ?>"><a href="<?php get_url('dashboard', 'dashboard') ?>"><?php echo _t('Dashboard') ?></a>

                        </li>
                    <?php } ?>
                    <?php if (acl_is_mod_allowed('analysis')) { ?>
                        <?php
                        $active = '';
                        if ('analysis' == $module) {
                            $active = 'active';
                            $breadcrumbs->pushCrumb(array('name' => _t('ANALYSIS'), 'mod' => 'analysis', 'act' => 'search'), 0);
                        }
                        ?>
                        <li class="<?php echo $active ?>">
                            <a href="<?php get_url('analysis', 'adv_search') ?>" ><?php echo _t('ANALYSIS') ?></a></li>

                    <?php }*/ ?>
                    <?php if (acl_is_mod_allowed('admin')) { ?>
                        <?php
                        $active = '';
                        if ('admin' == $module) {
                            $active = 'active';
                            $breadcrumbs->pushCrumb(array('name' => _t('ADMIN'), 'mod' => 'admin', 'act' => 'user_management'), 0);
                        }
                        ?>
                        <li class="<?php echo $active ?>">
                            <a href="<?php get_url('admin', 'user_management') ?>">
                                <?php echo _t('ADMIN') ?></a></li>
               
            <?php } ?>



            </ul>

        </div>	
    </div>
</div>
