<div id="browse_view">
<?php
    switch($active){ 
        case 'browse':
?>
            <a href="<?php get_url('docu','browse',null,null)?>" class='active'><?php echo _t('BROWSE_DOCUMENTS') ?></a>
            <a href="<?php get_url('docu','view_document')?>"><?php echo _t('VIEW_DOCUMENT') ?></a>
<?php 
            $_SESSION['bc']['top_tab']=_t('BROWSE_DOCUMENTS');
            $_SESSION['bc']['top_tab_url']=get_url('docu','browse',null,null,null,true); 
            break;
        case 'new':
?>
            <a href="<?php get_url('docu','browse',null,null)?>" ><?php echo _t('BROWSE_DOCUMENTS') ?></a>
            <a href="<?php get_url('docu','view_document',null)?>"><?php echo _t('VIEW_DOCUMENT') ?></a>
            <a href="<?php get_url('docu','new_document',null,null)?>" class='active'><?php echo _t('NEW_DOCUMENT') ?></a>
<?php 
            $_SESSION['bc']['top_tab']=_t('NEW_DOCUMENT');
            $_SESSION['bc']['top_tab_url']=get_url('docu','new_document',null,null,null,true); 
            break;    
        default: 
?>
            <a href="<?php get_url('docu','browse',null,null)?>" ><?php echo _t('BROWSE_DOCUMENTS') ?></a>
            <a href="<?php get_url('docu','view_document',null, null)?>"  class="active"><?php echo _t('VIEW_DOCUMENT') ?></a>
<?php 
            $_SESSION['bc']['top_tab']=_t('VIEW_DOCUMENT');
            $_SESSION['bc']['top_tab_url']=get_url('docu','view_document',null,null,null,true); 
    }
?>
</div>
