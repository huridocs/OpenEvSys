<?php include('tabs.php') ?>
<?php include_once('event_title.php')?>
<?php
    include_once('card_list.php');
    draw_card_list('vp',$event_id);
?>
<div class="panel">
<div class='flow'>
    <strong class='first'><?php echo _t('ADD_VICTIM')?></strong>
    <span><?php echo _t('ADD_ACT')?></span>
    <span><?php echo _t('ADD_PERPETRATOR')?></span>
    <span><?php echo _t('ADD_INVOLVEMENT')?></span>
    <span><?php echo _t('FINISH')?></span>
</div>
<br />
<h2><?php echo _t('WHO_IS_THE_VICTIM__') ?></h2>
<br />
<div class="form-container"> 
<form action='<?php echo get_url('events','add_victim','search_victim',array('eid'=>$event_id))?>' method='post' enctype='multipart/form-data'>
<?php
	shn_form_person_search('events','add_victim',null,array('cancel'=>'vp_list'));		
?>
</form>
</div>
<br />
<br />
</div>
