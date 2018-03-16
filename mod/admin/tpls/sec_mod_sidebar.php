<div class="span2">

    <div class="sidebar-nav">
        <div class="well" style="padding: 8px 0;">
            <ul class="nav nav-list"> 

                <?php
                global $conf;
                $action = $_GET['act'];
                ?>
                <li><a href='<?php get_url('admin', 'field_customization') ?>'><?php echo _t('FORM_CUSTOMIZATION') ?></a>
                </li>
                <li class="subnav <?php if ($action == "field_customization") echo "active" ?>"><a href="<?php get_url('admin', 'field_customization') ?>"><?php echo _t('EXISTING_FIELDS') ?></a></li>
                <li class="subnav <?php if ($action == "new_field") echo "active" ?>"><a href="<?php get_url('admin', 'new_field') ?>"><?php echo _t('ADD_NEW_FIELD') ?></a></li>
                <!--
                    <li><a href="<?php get_url('admin', 'shuffel_result') ?>"><?php echo _t('COMBINED_SEARCH_FORMS') ?></a></li>
                -->



                <li class="<?php if ($action == "mt_customization") echo "active" ?>"><a href="<?php get_url('admin', 'mt_customization') ?>"><?php echo _t('MICRO_THESAURI') ?></a></li>
                <li class="subnav <?php if ($action == "new_mt") echo "active" ?>"><a href="<?php get_url('admin', 'new_mt') ?>"><?php echo _t('Add new Micro-thesauri') ?></a></li>
                <li class="subnav <?php if ($action == "mt_translate") echo "active" ?>"><a href="<?php get_url('admin', 'mt_translate') ?>"><?php echo _t('Translate Micro-thesauri') ?></a></li>
                <li class="<?php if ($action == "menu") echo "active" ?>">
                    <a href="<?php get_url('admin', 'menu') ?>"><?php echo _t('Menu') ?></a>
                </li>
                <li>
                    <a href="<?php get_url('admin', 'user_management') ?>"><?php echo _t('USER_MANAGEMENT') ?></a>
                </li>
                <li class="subnav <?php if ($action == "user_management") echo "active" ?>"><a href="<?php get_url('admin', 'user_management') ?>"><?php echo _t('USERS') ?></a></li>
                <li class="subnav <?php if ($action == "add_user") echo "active" ?>"><a href="<?php get_url('admin', 'add_user') ?>"><?php echo _t('ADD_NEW_USER') ?></a></li> 
                <li class="subnav <?php if ($action == "acl") echo "active" ?>"><a href="<?php get_url('admin', 'acl') ?>"><?php echo _t('ROLES___MODULE_ACCESS_CONTROL') ?></a></li> 
                <li class="subnav <?php if ($action == "permissions") echo "active" ?>"><a href="<?php get_url('admin', 'permissions') ?>"><?php echo _t('PERMISSIONS') ?></a></li> 
                <!-- 
                <li><a href="<?php get_url('admin', 'acl_mode') ?>"><?php echo _t('ACL_MODE') ?></a></li> 
                -->


                <li class="<?php if ($action == "set_locale") echo "active" ?>">
                    <a href="<?php get_url('admin', 'set_locale') ?>"><?php echo _t('Language') ?></a>
                </li><!-- 
                <ul>
                            <li><a href="<?php get_url('admin', 'set_locale') ?>"><?php echo _t('SET_LANGUAGE') ?></a></li>
                            <li><a href="<?php get_url('admin', 'manage_locale') ?>"><?php echo _t('MANAGE_LANGUAGES') ?></a></li> 
                            <li><a href="<?php get_url('admin', 'translate') ?>"><?php echo _t('TRANSLATE') ?></a></li>
                </ul>
                -->

                <!-- 
                        <li>
                            <a href="<?php get_url('admin', 'import') ?>"><?php echo _t('DATA_EXCHANGE') ?></a>
                    <ul>
                                <li><a href="<?php get_url('admin', 'import') ?>"><?php echo _t('IMPORT') ?></a></li>
                                <li><a href="<?php get_url('admin', 'import_log') ?>"><?php echo _t('IMPORT_LOG') ?></a></li>
                                <li><a href="<?php get_url('admin', 'export_ui') ?>"><?php echo _t('EXPORT') ?></a></li> 		        
                    </ul>
                </li>
                -->
                <li class="<?php if ($action == "System_configuration") echo "active" ?>"><a href="<?php get_url('admin', 'System_configuration') ?>"><?php echo _t('SYSTEM_CONFIGURATION') ?></a></li>
                <li class="<?php if ($action == "change_print") echo "active" ?>">
                    <a href="<?php get_url('admin', 'change_print') ?>"><?php echo _t('Print configuration') ?></a>
                </li>
                <li class="<?php if ($action == "dashboard_configuration") echo "active" ?>"><a href="<?php get_url('admin', 'dashboard_configuration') ?>"><?php echo _t('Dashboard Configuration') ?></a></li>


                <li><a href="<?php get_url('admin', 'database_backup') ?>"><?php echo _t('Database Backup') ?></a></li>
                <li class="subnav <?php if ($action == "database_backup") echo "active" ?>"><a href="<?php get_url('admin', 'database_backup') ?>"><?php echo _t('Database Backup') ?></a></li>
                <li class="subnav <?php if ($action == "database_restore") echo "active" ?>"><a href="<?php get_url('admin', 'database_restore') ?>"><?php echo _t('Database Restore') ?></a></li>

                <?php
                global $conf;
                if (isset($conf['extension'])) {
                    ?>
                    <li class="<?php if ($action == "Extensions") echo "active" ?>"><a href="<?php get_url('admin', 'Extensions') ?>"><?php echo _t('EXTENSIONS') ?></a></li>
                <?php } ?>
            </ul>


        </div></div></div>