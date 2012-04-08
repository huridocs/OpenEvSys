<div id="browse_view">
<?php
    switch($active){ 
        case 'browse':
?>
            <a href="<?php get_url('events','browse',null,array('eid'=>null))?>" class='active'><?php echo _t('BROWSE_EVENTS') ?></a>
            <a href="<?php get_url('events','get_event')?>"><?php echo _t('VIEW_EVENT') ?></a>
<?php
            $breadcrumbs->pushCrumb(array('name'=>_t('BROWSE_EVENT'),'mod'=>'events','act'=>'browse'),1);        
            break;
      case 'new':
?>
            <a href="<?php get_url('events','browse',null,array('eid'=>null))?>" ><?php echo _t('BROWSE_EVENTS') ?></a>
            <a href="<?php get_url('events','get_event',null)?>"><?php echo _t('VIEW_EVENT') ?></a>
            <a href="<?php get_url('events','new_event',null,array('eid'=>null)) ?>" class='active'><?php echo _t('NEW_EVENT') ?></a>
<?php 
            $breadcrumbs->pushCrumb(array('name'=>_t('NEW_EVENT'),'mod'=>'events','act'=>'new_event'),1);        
            break;
        default: 
?>
            <a href="<?php get_url('events','browse',null,array('eid'=>null))?>" ><?php echo _t('BROWSE_EVENTS') ?></a>
            <a href="<?php get_url('events','get_event',null)?>"  class="active"><?php echo _t('VIEW_EVENT') ?></a>
<?php 
            $breadcrumbs->pushCrumb(array('name'=>_t('VIEW_EVENT'),'mod'=>'events','act'=>'get_event'),1);        
    }
?>
</div>
