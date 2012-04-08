<?php include_once('tabs.php')?>
<?php include_once('event_title.php')?>
<?php
    include_once('card_list.php');
    draw_card_list('src',$event_id);
?>
<div class="panel">
<div class='flow'>
    <strong class='first'><?php echo _t('ADD_SOURCE')?></strong>
    <span><?php echo _t('ADD_INFORMATION')?></span>
    <span><?php echo _t('FINISH')?></span>
</div>
<br />

<h2><?php echo _t('WHO_IS_THE_SOURCE_') ?></h2>
<br />
<div class="form-container"> 
<form action='<?php echo get_url('events','add_source','search_source',array('eid'=>$event_id))?>' method='post' enctype='multipart/form-data'>
<?php
	shn_form_person_search('events','add_source',null,array('cancel'=>'src_list'));		
?>
</form>
</div>
<br />
</div>
