
<?php include_once('event_title.php') ?>

<div class="panel">
<?php include_once('vp_list_table.php');?>
<br />
<?php echo "<h3>"._t('EDIT_THIS_INVOLVEMENT')."</h3>&nbsp;";?>
<div class="form-container">
<form class="form-horizontal"  id="involvement" name="involvement" action='<?php echo get_url('events','edit_involvement',null,array('eid'=>$event_id,'inv_id'=>$_GET['inv_id']))?>' method='post' enctype='multipart/form-data'>
<?php
    $involvement_form = involvement_form('edit');
    popuate_formArray($involvement_form,$inv);
	$fields = shn_form_get_html_fields($involvement_form);
	place_form_elements($involvement_form , $fields);  
?>
    <div class="control-group">
                <div class="controls">

                    <button type="submit" class="btn" name="update" ><i class="icon-ok"></i> <?php echo _t('SAVE') ?></button>
                <a class="btn" href="<?php echo get_url('events','vp_list',null,array('eid'=>$event_id,'inv_id'=>$_GET['inv_id'],'row'=>$_GET['row'],'type'=>'inv')) ?>"><i class="icon-stop"></i> <?php echo _t('CANCEL')?></a> <span>&nbsp;</span>
     </div>
            </div>
	
</form>
</div>
</div>
