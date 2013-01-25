
<?php include_once('event_title.php'); ?>

<div class="panel">
<div class='flow'>
    <span class="over first"><?php echo _t('ADD_INTERVENING_PARTY')?></span>
    <strong><?php echo _t('ADD_INTERVENTION')?></strong>
    <span><?php echo _t('FINISH')?></span>
</div>
<br />
<h2><?php echo _t('WHAT_IS_THE_INTERVENTION_') ?></h2>
<br />
<div class="form-container"> 
<form class="form-horizontal"  id="intervention" name="intervention" action='<?php echo get_url('events','add_intv',null,array('eid'=>$event_id))?>' method='post' enctype='multipart/form-data'>
<?php	
    $fields = shn_form_get_html_fields($intervention_form);
    place_form_elements($intervention_form,$fields);
?>
<br />
<center>
	<a class="btn" href="<?php echo get_url('events','add_intv_party',null,array('person_id'=> $_SESSION['intv']['intv_party'])) ?>"><?php echo _t('PREVIOUS')?></a><span>&nbsp;&nbsp;</span>
    <?php // echo $fields['more'];?>    
    <a class="btn" href="<?php echo get_url('events','intv_list',null,array('eid'=>$event_id)) ?>"><?php echo _t('CANCEL')?></a><span>&nbsp;&nbsp;</span>
	<?php echo $fields['finish'];?>
</center>
</form>
</div>
<br style="clear:both" />
<br />
</div>
