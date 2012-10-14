		<div id="menu">
            <ul>
                <?php if(acl_is_mod_allowed('events')){ ?>
                <?php 
                    if('events' == $module ){ 
                        $active = 'class="active"';
                        $breadcrumbs->pushCrumb(array('name'=>_t('EVENTS'),'mod'=>'events','act'=>'browse'),0);        
                    }
                    else $active = '';  
                ?>
                <li <?php echo $active ?>><a href="<?php get_url('events','browse') ?>"><?php echo _t('EVENTS') ?></a></li>
                <?php } ?>
                <?php if(acl_is_mod_allowed('person')){ ?>
                <?php 
                    if('person' == $module ){ 
                        $active = 'class="active"';
                        $breadcrumbs->pushCrumb(array('name'=>_t('PERSONS'),'mod'=>'person','act'=>'browse'),0);        
                    }
                    else $active = '';  
                ?>
                <li <?php echo $active ?>><a href="<?php get_url('person','browse') ?>"><?php echo _t('PERSONS')?></a></li>
                <?php } ?>
                <?php if(acl_is_mod_allowed('docu')){ ?>
                <?php 
                    if('docu' == $module ){ 
                        $active = 'class="active"';
                        $breadcrumbs->pushCrumb(array('name'=>_t('DOCUMENTS'),'mod'=>'docu','act'=>'browse'),0);        
                    }
                    else $active = '';  
                ?>
                <li <?php echo $active ?>><a href="<?php get_url('docu','browse') ?>"><?php echo _t('DOCUMENTS')?></a></li>
                <?php } ?>
                <?php if(acl_is_mod_allowed('analysis')){ ?>
                <?php 
                    if('analysis' == $module ){ 
                        $active = 'class="active"';
                        $breadcrumbs->pushCrumb(array('name'=>_t('ANALYSIS'),'mod'=>'analysis','act'=>'search'),0);        
                    }
                    else $active = '';  
                ?>
                <li <?php echo $active ?>><a href="<?php get_url('analysis','adv_search') ?>"><?php echo _t('ANALYSIS')?></a></li>
                <?php } ?>
                <?php if(acl_is_mod_allowed('admin')){ ?>
                <?php 
                    if('admin' == $module ){ 
                        $active = 'class="active"';
                        $breadcrumbs->pushCrumb(array('name'=>_t('ADMIN'),'mod'=>'admin','act'=>'user_management'),0);        
                    }
                    else $active = '';  
                ?>
                <li <?php echo $active ?>><a href="<?php get_url('admin','user_management') ?>"><?php echo _t('ADMIN') ?></a></li>
                <?php } ?>
			</ul>
			<div style="clear:both"></div>	
		</div>
