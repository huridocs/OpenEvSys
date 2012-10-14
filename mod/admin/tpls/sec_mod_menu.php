<div id='admin_menu'>
    <div id='admin_menu_wrap'>
    <ul>
        <?php 
        	global $conf;
        	if(!isset($conf['extension'])){ 
        ?>
        <li><a href='<?php get_url('admin','field_customization' ) ?>'><?php echo _t('FORM_CUSTOMIZATION')?></a>
        <ul>
        		<li><a href="<?php get_url('admin','field_customization')?>"><?php echo _t('EXISTING_FIELDS')?></a></li>
		        <li><a href="<?php get_url('admin','new_field')?>"><?php echo _t('ADD_NEW_FIELD')?></a></li>
		    <!--
		        <li><a href="<?php get_url('admin','shuffel_result')?>"><?php echo _t('COMBINED_SEARCH_FORMS')?></a></li>
            --></ul>
        
        </li>
        
        <li><a href="<?php get_url('admin','mt_customization')?>"><?php echo _t('MICRO_THESAURI')?></a></li>
		<?php } ?>
		<li>
		    <a href="<?php get_url('admin','user_management')?>"><?php echo _t('USER_MANAGEMENT')?></a>
            <ul>
		        <li><a href="<?php get_url('admin','user_management')?>"><?php echo _t('USERS')?></a></li>
		        <li><a href="<?php get_url('admin','acl')?>"><?php echo _t('ROLES___MODULE_ACCESS_CONTROL')?></a></li> 
		        <li><a href="<?php get_url('admin','permissions')?>"><?php echo _t('PERMISSIONS')?></a></li> 
		        <!-- 
		        <li><a href="<?php get_url('admin','acl_mode')?>"><?php echo _t('ACL_MODE')?></a></li> 
		         -->
            </ul>
        </li>
		<li>
            <a href="<?php get_url('admin','set_locale')?>"><?php echo _t('LOCALIZATION')?></a>
            <!-- 
            <ul>
		        <li><a href="<?php get_url('admin','set_locale')?>"><?php echo _t('SET_LANGUAGE')?></a></li>
		        <li><a href="<?php get_url('admin','manage_locale')?>"><?php echo _t('MANAGE_LANGUAGES')?></a></li> 
		        <li><a href="<?php get_url('admin','translate')?>"><?php echo _t('TRANSLATE')?></a></li>
            </ul>
             -->
        </li>
        <!-- 
		<li>
		    <a href="<?php get_url('admin','import')?>"><?php echo _t('DATA_EXCHANGE')?></a>
            <ul>
		        <li><a href="<?php get_url('admin','import')?>"><?php echo _t('IMPORT')?></a></li>
		        <li><a href="<?php get_url('admin','import_log')?>"><?php echo _t('IMPORT_LOG')?></a></li>
		        <li><a href="<?php get_url('admin','export_ui')?>"><?php echo _t('EXPORT')?></a></li> 		        
            </ul>
        </li>
        -->
        <li><a href="<?php get_url('admin','System_configuration')?>"><?php echo _t('SYSTEM_CONFIGURATION')?></a></li>
        <?php 
        	global $conf;
        	if(isset($conf['extension'])){ 
        ?>
        <li><a href="<?php get_url('admin','Extensions')?>"><?php echo _t('EXTENSIONS')?></a></li>
        <?php } ?>
    </ul>
    </div>
</div>
