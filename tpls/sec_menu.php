<div class="navbar navbar-fixed-top navbar-inverse"  >
    <div class="navbar-inner">
        <div class="container">
            <a class="brand" href="<?php get_url(); ?>" title="OpenEvSys">OpenEvSys</a>
            <ul class="nav">
                <?php if (acl_is_mod_allowed('events')) { ?>
                    <?php
                    $active = '';
                    if ('events' == $module) {
                        $active = 'active';
                        $breadcrumbs->pushCrumb(array('name' => _t('EVENTS'), 'mod' => 'events', 'act' => 'browse'), 0);
                    }
                    ?>
                    <li class="dropdown <?php echo $active ?>">
                        <a href="<?php get_url('events', 'browse') ?>" class="dropdown-toggle" data-toggle="dropdown">
                            <?php echo _t('EVENTS') ?>
                            <span class="caret"></span></a>

                        <ul class="dropdown-menu">

                            <li><a href="<?php get_url('events', 'browse') ?>"><?php echo _t('BROWSE_EVENTS') ?></a></li>
                            <li><a href="<?php get_url('events', 'new_event', null, array('eid' => null)) ?>" >
                                    <?php echo _t('ADD_NEW_EVENT') ?></a></li>

                        </ul>
                    </li>
                <?php } ?>
                <?php if (acl_is_mod_allowed('person')) { ?>
                    <?php
                    $active = '';
                    if ('person' == $module) {
                        $active = 'active';
                        $breadcrumbs->pushCrumb(array('name' => _t('PERSONS'), 'mod' => 'person', 'act' => 'browse'), 0);
                    }
                    ?>
                    <li class="dropdown <?php echo $active ?>">
                        <a href="<?php get_url('person', 'browse') ?>" class="dropdown-toggle" data-toggle="dropdown">
                            <?php echo _t('PERSONS') ?>
                            <span class="caret"></span></a>
                        <ul class="dropdown-menu">

                            <li><a href="<?php get_url('person', 'browse') ?>"><?php echo _t('BROWSE_PERSONS') ?></a></li>
                            <li><a  href="<?php get_url('person', 'new_person', null, array('pid' => null)) ?>">
                                    <?php echo _t('ADD_NEW_PERSON') ?></a></li>

                        </ul>
                    </li>
                <?php } ?>
                <?php if (acl_is_mod_allowed('docu')) { ?>
                    <?php
                    $active = '';
                    if ('docu' == $module) {
                        $active = 'active';
                        $breadcrumbs->pushCrumb(array('name' => _t('DOCUMENTS'), 'mod' => 'docu', 'act' => 'browse'), 0);
                    }
                    ?>
                    <li class="dropdown <?php echo $active ?>">
                        <a href="<?php get_url('docu', 'browse') ?>" class="dropdown-toggle" data-toggle="dropdown">
                            <?php echo _t('DOCUMENTS') ?>
                            <span class="caret"></span></a>
                        <ul class="dropdown-menu">

                            <li><a href="<?php get_url('docu', 'browse') ?>"><?php echo _t('BROWSE_DOCUMENTS') ?></a></li>
                            <li><a href="<?php get_url('docu', 'new_document', null, null) ?>">
                                    <?php echo _t('ADD_NEW_DOCUMENT') ?></a></li>

                        </ul></li>
                <?php } ?>
                <?php if (acl_is_mod_allowed('analysis')) { ?>
                    <?php
                    $active = '';
                    if ('analysis' == $module) {
                        $active = 'active';
                        $breadcrumbs->pushCrumb(array('name' => _t('ANALYSIS'), 'mod' => 'analysis', 'act' => 'search'), 0);
                    }
                    ?>
                    <li class="dropdown <?php echo $active ?>">
                        <a href="<?php get_url('analysis', 'adv_search') ?>" class="dropdown-toggle" data-toggle="dropdown">
                            <?php echo _t('ANALYSIS') ?>
                            <span class="caret"></span></a>
                        <ul class="dropdown-menu">


                            <li> <a href="<?php get_url('analysis', 'adv_search') ?>" ><?php echo _t('ADVANCED_SEARCH') ?></a></li>
                            <li><a href="<?php get_url('analysis', 'search_query') ?>" ><?php echo _t('SAVED_QUERIES') ?></a>
                            </li>

                        </ul></li>
                <?php } ?>
                <?php if (acl_is_mod_allowed('admin')) { ?>
                    <?php
                    $active = '';
                    if ('admin' == $module) {
                        $active = 'active';
                        $breadcrumbs->pushCrumb(array('name' => _t('ADMIN'), 'mod' => 'admin', 'act' => 'user_management'), 0);
                    }
                    ?>
                         <li class="dropdown <?php echo $active ?>">
                        <a href="<?php get_url('admin', 'user_management') ?>" class="dropdown-toggle" data-toggle="dropdown">
                            <?php echo _t('ADMIN') ?>
                            <span class="caret"></span></a>
                  
                      <ul class="dropdown-menu">


                            <li> <a href="<?php get_url('admin', 'user_management') ?>">
                        <?php echo _t('ADMIN') ?></a></li>
                           <li><a  href="<?php get_url('admin','add_user'   ) ?> " >
                        <?php echo _t('ADD_NEW_USER') ?></a>
                    </li>

                        </ul>
                    </li>
                    
 <?php } ?>
            </ul>
            <div class="btn-group pull-right">
                <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-user icon-white"></i> <?php echo htmlspecialchars($_SESSION['username']) ?></a>
                <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#" style="height: 20px;"><span class="caret"></span></a>
                <ul class="dropdown-menu">

                    <li><a href="<?php get_url('home', 'edit_user') ?>"><i class="icon-pencil"></i> <?php echo _t('MY_PREFERENCES') ?></a></li>
                    <li><a href="?act=logout"><i class="icon-off"></i> <?php echo _t('SIGN_OUT') ?></a></li>

                </ul>
            </div>
        </div>	
    </div>
</div>
