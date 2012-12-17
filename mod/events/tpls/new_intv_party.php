<?php
    
    include_once('event_title.php');
?>
<div class="panel">
<div class='flow'>
    <strong class='first'><?php echo _t('ADD_INTERVENING_PARTY')?></strong>
    <span><?php echo _t('ADD_INTERVENTION')?></span>
    <span><?php echo _t('FINISH')?></span>
</div>
<br />
<h2><?php echo _t('WHO_IS_THE_INTERVENING_PARTY_') ?></h2>
<br />
<div class="form-container">
<form class="form-horizontal"  id="person_form" name="person_form" action='<?php echo get_url('events','add_intv_party','new_intv_party',array('eid'=>$event_id))?>' method='post' enctype='multipart/form-data'>
	<?php $person_form = person_form('new');?>
    <?php $fields = shn_form_get_html_fields($person_form);  ?>
    <?php $fields = place_form_elements($person_form,$fields); ?>
	<center>
	<a class="btn" href="<?php echo get_url('events','intv_list',null,array('eid'=>$event_id)) ?>"><?php echo _t('CANCEL')?></a><span>&nbsp;&nbsp;</span>
	<input type="submit" class="btn" value="<?php echo _t('CONTINUE') ?>" name='save'/>
	</center>
</form>
</div>
<br />
</div>
