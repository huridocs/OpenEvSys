<?php include_once('tabs.php') ?>
<?php include_once('event_title.php')?>
<?php
    include_once('card_list.php');
    draw_card_list('vp',$event_id);
?>
<div class="panel">
<div class='flow'>
    <span class="over first"><?php echo _t('ADD_VICTIM')?></span>
    <span class="over"><?php echo _t('ADD_ACT')?></span>
    <span class="over"><?php echo _t('ADD_PERPETRATOR')?></span>
    <strong><?php echo _t('ADD_INVOLVEMENT')?></strong>
    <span><?php echo _t('FINISH')?></span>
</div>
<br />
<h2><?php echo _t('HOW_WAS_').' ['.$perpetrator->person_name.'] '._t('INVOLVED_IN').' ['.$act_name .'] ?' ?></h2>
<br />
<div class="form-container"> 
<form id="involvement" name="involvement" action='<?php echo get_url('events','add_involvement',null,array('eid'=>$event_id))?>' method='post' enctype='multipart/form-data'>
<?php $fields = shn_form_get_html_fields($involvement_form);?>
<?php place_form_elements($involvement_form , $fields);  ?>
<center>
    <a class="but" href="<?php   echo get_url('events','add_perpetrator',null,array('person_id'=>$_SESSION['vp']['perpetrator'])) ?>"><?php echo _t('PREVIOUS')?></a><span>&nbsp;&nbsp;</span>
    <?php echo $fields['more'];?>
    <a class="but" href="<?php   echo get_url('events','vp_list',null,array('eid'=>$event_id)) ?>"><?php echo _t('CANCEL')?></a><span>&nbsp;&nbsp;</span>    <?php echo $fields['finish'];?>
    
    
</center>
</form>
</div>
<br />
</div>
