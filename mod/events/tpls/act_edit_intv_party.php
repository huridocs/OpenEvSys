
<?php include_once('event_title.php')?>

<div class="panel">
	<?php include_once('intv_list_table.php'); ?>
	<br />
	<br />
    <h3><?php echo _t('EDIT_THIS_INTERVENING_PARTY') ?></h3>
    <br />
    <div class="form-container"> 
        <form class="form-horizontal"  id="person_form" name="person_form" action='<?php echo get_url('events','edit_intv_party',null,array('intervention_id'=>$_GET['intervention_id']))?>' method='post' enctype='multipart/form-data'>
    <?php		
        place_form_elements($person_form,$fields); 
		
	?>
            <div class="control-group">
                <div class="controls">

                    <button type="submit" class="btn" name="update" ><i class="icon-ok"></i> <?php echo _t('SAVE') ?></button>
                  <a class="btn" href="<?php echo get_url('events','intv_list',null,array('eid'=>$event_id,'intervention_id'=>$_GET['intervention_id'],'type'=>'intv_party')) ?>"><i class="icon-stop"></i> <?php echo _t('CANCEL')?></a><span>&nbsp;&nbsp;</span>
         </div>
            </div>

	
        </form>
    </div>
</div>
