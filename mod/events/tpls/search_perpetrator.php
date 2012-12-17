
<?php include_once('event_title.php'); ?>

<div class="panel">
<div class='flow'>
    <span class="over first"><?php echo _t('ADD_VICTIM')?></span>
    <span class="over"><?php echo _t('ADD_ACT')?></span>
    <strong><?php echo _t('ADD_PERPETRATOR')?></strong>
    <span><?php echo _t('ADD_INVOLVEMENT')?></span>
    <span><?php echo _t('FINISH')?></span>
</div>
<br />
<h2><?php echo _t('WHO_IS_RESPONSIBLE_FOR_THE').' <em>"'.$act_name.'"</em> '._t('AGAINST').' <em>"'.$victim->name.'"</em> ?' ?></h2>
<div class="form-container"> 
<form class="form-horizontal"  action='<?php echo get_url('events','add_perpetrator','search_perpetrator',array('eid'=>$event_id))?>' method='post' enctype='multipart/form-data'>
<?php
	shn_form_person_search('events','add_perpetrator',null,array('cancel'=>'vp_list'));		
?>
</form>
</div>
<br />
<br />
</div>
