<?php 
include_once('tabs.php');
include_once('event_title.php');
include_once('card_list.php');
draw_card_list('sum',$event_id); 
?>
<div class="panel">
    <?php if( acl_is_entity_allowed_boolean('event' , 'update')){?>
    	<a class="but" href="<?php echo get_url('events','edit_event') ?>"><img src="<?php echo data_uri(APPROOT.'www/res/img/gtk-edit.png','image/png') ?>"> <?php echo _t('EDIT_THIS_EVENT')?></a>
    <?php }?>
    <?php if( acl_is_entity_allowed_boolean('event' , 'delete')){?>
    <a class="but" href="<?php echo get_url('events','delete_event') ?>"><img src="<?php echo data_uri(APPROOT.'www/res/img/edit-delete.png','image/png') ?>"> <?php echo _t('DELETE_THIS_EVENT')?></a>
    <?php }?>
    <a class="but" href="<?php echo get_url('events','print') ?>"><img src="<?php echo data_uri(APPROOT.'www/res/img/document-print.png','image/png') ?>"> <?php echo _t('PRINT_THIS_EVENT')?></a>
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
