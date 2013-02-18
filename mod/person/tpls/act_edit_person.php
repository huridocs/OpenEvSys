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
 <div class="control-group">
                <div >

                   <a class="btn" href="<?php echo get_url('person','person'); ?>"><i class="icon-remove-circle"></i> <?php echo _t('CANCEL'); ?></a>
	     <button type="submit" class="btn  btn-primary" name="update" ><i class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?></button>
                 </div>
            </div><?php	
	$fields = place_form_elements($person_form,$fields);
	  
?>
     <div class="control-group">
                <div >

                   <a class="btn" href="<?php echo get_url('person','person'); ?>"><i class="icon-remove-circle"></i> <?php echo _t('CANCEL'); ?></a>
	     <button type="submit" class="btn  btn-primary" name="update" ><i class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?></button>
                 </div>
            </div>
	
</form>
</div>
</div>
