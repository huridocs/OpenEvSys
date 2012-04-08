<?php include('tabs.php') ?>
<?php include_once('event_title.php'); ?>
<?php
    include_once('card_list.php');
    draw_card_list('vp',$event_id);
?>
<div class="panel">
<div class='flow'>
    <span class="over first"><?php echo _t('ADD_VICTIM')?></span>
    <span class="over"><?php echo _t('ADD_ACT')?></span>
    <strong><?php echo _t('ADD_PERPETRATOR')?></strong>
    <span><?php echo _t('ADD_INVOLVEMENT')?></span>
    <span><?php echo _t('FINISH')?></span>
</div>
<br />
<h2><?php echo _t('WHO_IS_RESPONSIBLE_FOR_THE').' <em>"'.$act_name.'"</em> '._t('AGAINST').' <em>"'.$victim->person_name.'"</em> ?' ?></h2>
<br />
<?php
//if a perpetrator is selected show perpetrator record
if(isset($perpetrator)){
    $person_form = person_form('view');
    popuate_formArray($person_form , $perpetrator);
    shn_form_get_html_labels($person_form , false);
}
?>
<br />
<a class="but" href="<?php echo get_url('events','add_perpetrator','new_perpetrator',array('eid'=>$event_id)) ?>"><?php echo _t('ADD_NEW')?></a><span>&nbsp;&nbsp;</span>
<a class="but" href="<?php echo get_url('events','add_perpetrator','search_perpetrator',array('eid'=>$event_id)) ?>"><?php echo _t('SEARCH_IN_DATABASE')?></a><span>&nbsp;&nbsp;</span>
<a class="but" href="<?php echo get_url('events','vp_list',null,array('eid'=>$event_id)) ?>"><?php echo _t('CANCEL')?></a><span>&nbsp;&nbsp;</span>
<?php if(isset($perpetrator)){ ?>
<a class="but" href="<?php echo get_url('events','add_involvement',null,array('eid'=>$event_id, 'perpetrator'=>$perpetrator->person_record_number)) ?>"><?php echo _t('CONTINUE')?></a><span>&nbsp;&nbsp;</span>
<?php } ?>
<br />
<br />
</div>
