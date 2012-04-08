<?php include_once('tabs.php')?>
<?php include_once('event_title.php') ?>
<?php
    include_once('card_list.php');
    draw_card_list('vp',$event_id);
?>
<div class="panel">
<?php include_once('vp_list_table.php');?>
<br />
<?php echo "<h3>"._t('EDIT_THIS_INVOLVEMENT')."</h3>&nbsp;";?>
<div class="form-container">
<form id="involvement" name="involvement" action='<?php echo get_url('events','edit_involvement',null,array('eid'=>$event_id,'inv_id'=>$_GET['inv_id']))?>' method='post' enctype='multipart/form-data'>
<?php
    $involvement_form = involvement_form('edit');
    popuate_formArray($involvement_form,$inv);
	$fields = shn_form_get_html_fields($involvement_form);
	place_form_elements($involvement_form , $fields);  
?>
	<center>
	<?php echo $fields['update'] ?>
	<a class="but" href="<?php echo get_url('events','vp_list',null,array('eid'=>$event_id,'inv_id'=>$_GET['inv_id'],'row'=>$_GET['row'],'type'=>'inv')) ?>"><?php echo _t('CANCEL')?></a> <span>&nbsp;</span>
    </center>
</form>
</div>
</div>
