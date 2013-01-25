
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
		<center>
		<?php echo $fields['update']; ?>
        <a class="btn" href="<?php echo get_url('events','intv_list',null,array('eid'=>$event_id,'intervention_id'=>$_GET['intervention_id'],'type'=>'intv_party')) ?>"><?php echo _t('CANCEL')?></a><span>&nbsp;&nbsp;</span>
        </center>
        </form>
    </div>
</div>
