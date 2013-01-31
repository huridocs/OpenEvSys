<?php  global $conf ?> 
<?php include_once('person_name.php')?>
<?php	
	include_once 'view_card_list.php';
	draw_card_list('pd',$pid);	
?>
<div class="panel">
<br />
<h3><?php echo _t('EDIT_THIS_PERSON'); ?></h3>
<div class="form-container"> 
<form class="form-horizontal"  name="person_form" id="person_form" action='<?php echo get_url('person','edit_person')?>' method='post' enctype='multipart/form-data'>
<?php	
	$fields = place_form_elements($person_form,$fields);
	  
?>
     <div class="control-group">
                <div class="controls">

                    <button type="submit" class="btn" name="update" ><i class="icon-ok"></i> <?php echo _t('SAVE') ?></button>
                   <a class="btn" href="<?php echo get_url('person','person'); ?>"><i class="icon-stop"></i> <?php echo _t('CANCEL'); ?></a>
	  </div>
            </div>
	
</form>
</div>
</div>
