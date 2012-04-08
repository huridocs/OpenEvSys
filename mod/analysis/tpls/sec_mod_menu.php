<div id="browse_view">
<?php
    switch($active){ 
        case 'query':
?>
<!--            <a href="<?php get_url('analysis','search',null,null)?>"><?php echo _t('BASIC_SEARCH') ?></a>            -->
            <a href="<?php get_url('analysis','adv_search')?>" ><?php echo _t('ADVANCED_SEARCH') ?></a>
            <a href="<?php get_url('analysis','search_query')?>" class='active'><?php echo _t('SAVED_QUERIES') ?></a>
<?php 
            $breadcrumbs->pushCrumb(array('name'=>_t('SAVED_QUERIES'),'mod'=>'analysis','act'=>'search_query'),1);
            break;
        case 'adv_search':
?>
<!--            <a href="<?php get_url('analysis','search',null,null)?>"><?php echo _t('BASIC_SEARCH') ?></a>            -->
            <a href="<?php get_url('analysis','adv_search')?>" class='active'><?php echo _t('ADVANCED_SEARCH') ?></a>
            <a href="<?php get_url('analysis','search_query')?>" ><?php echo _t('SAVED_QUERIES') ?></a>
<?php 
            $breadcrumbs->pushCrumb(array('name'=>_t('SAVED_QUERIES'),'mod'=>'analysis','act'=>'search_query'),1);
            break;
        default: 
?>
<!--            <a href="<?php get_url('analysis','search',null,array('eid'=>null))?>"  class="active"><?php  echo _t('BASIC_SEARCH') ?></a>            -->
            <a href="<?php get_url('analysis','adv_search')?>" ><?php echo _t('ADVANCED_SEARCH') ?></a>
            <a href="<?php get_url('analysis','search_query',null)?>" ><?php echo _t('SAVED_QUERIES') ?></a>
<?php 
            $breadcrumbs->pushCrumb(array('name'=>_t('BASIC_SEARCH'),'mod'=>'analysis','act'=>'search'),1);
    }
?>
</div>
