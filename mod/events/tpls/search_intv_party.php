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
<form class="form-horizontal"  action='<?php echo get_url('events','add_intv_party','search_intv_party',array('eid'=>$event_id))?>' method='post' enctype='multipart/form-data'>
<?php
	shn_form_person_search('events','add_intv_party',null,array('cancel'=>'intv_list'));		
?>
</form>
</div>
<br />
</div>
