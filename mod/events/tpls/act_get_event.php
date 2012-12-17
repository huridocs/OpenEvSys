<?php 

include_once('event_title.php');

?>
<div class="panel">
    <?php if( acl_is_entity_allowed_boolean('event' , 'update')){?>
    <a class="btn" href="<?php echo get_url('events','edit_event') ?>">
            <i class="icon-edit"></i> <?php echo _t('EDIT_THIS_EVENT')?></a>
    <?php }?>
    <?php if( acl_is_entity_allowed_boolean('event' , 'delete')){?>
    <a class="btn" href="<?php echo get_url('events','delete_event') ?>">
        <i class="icon-trash"></i> <?php echo _t('DELETE_THIS_EVENT')?></a>
    <?php }?>
    <a class="btn" href="<?php echo get_url('events','print') ?>">
        <i class="icon-print"></i> <?php echo _t('PRINT_THIS_EVENT')?></a>
        
    	<br />
    <br />
    <?php shn_form_get_html_labels($event_form, false);?>
<?php
echo '<br/>';
include_once('contextual_data.php');
//_shn_get_incident_records();
//echo '<br/>';
//_shn_get_victim_records();
//echo '<br/>';
_shn_get_coe_records($event_id);
?>
</div>
