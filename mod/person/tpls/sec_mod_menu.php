<div id="browse_view">
<?php
    switch($active){ 
        case 'browse':
?>
            <a href="<?php get_url('person','browse',null,array('pid'=>null))?>" class='active'><?php echo _t('BROWSE_PERSONS') ?></a>
            <a href="<?php get_url('person','person')?>"><?php echo _t('VIEW_PERSON') ?></a>
<?php 
            $breadcrumbs->pushCrumb(array('name'=>_t('BROWSE_PERSONS'),'mod'=>'person','act'=>'browse'),1);
            break;
        case 'new':
?>
            <a href="<?php get_url('person','browse',null,array('pid'=>null))?>" ><?php echo _t('BROWSE_PERSONS') ?></a>
            <a href="<?php get_url('person','person',null)?>"><?php echo _t('VIEW_PERSON') ?></a>
            <a href="<?php get_url('person','new_person',null,array('pid'=>null))?>" class='active'><?php echo _t('NEW_PERSON') ?></a>
<?php 
            $breadcrumbs->pushCrumb(array('name'=>_t('NEW_PERSON'),'mod'=>'person','act'=>'new_person'),1);
            break;    
        default: 
?>
            <a href="<?php get_url('person','browse',null,array('pid'=>null))?>" ><?php echo _t('BROWSE_PERSONS') ?></a>
            <a href="<?php get_url('person','person',null)?>"  class="active"><?php echo _t('VIEW_PERSON') ?></a>
<?php 
            $breadcrumbs->pushCrumb(array('name'=>_t('VIEW_PERSON'),'mod'=>'person','act'=>'person'),1);
    }
?>
</div>
