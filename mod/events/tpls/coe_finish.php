<?php 
    include_once('tabs.php');
    include_once('event_title.php');
    include_once('card_list.php');
    draw_card_list('coe',$event_id);
?>
<div class="panel">
    <div class='flow'>
        <span class="first over" ><?php echo _t('ADD_EVENT_INFORMATION')?></span>
        <strong class='active'><?php echo _t('FINISH')?></strong>
    </div>
    <br />
<?php	 
    shn_form_get_html_labels($chain_of_events_form,false);
?>
<br />
    <div class="notice">
        <strong><?php echo _t('FINISHED__YOU_CAN_DO_THE_FOLLOWING')?></strong><br />
        <a href="<?php get_url('events','edit_coe',null,array('eid'=>$event_id,'coeid'=>$coeid))?>" ><?php echo _t('CONTINUE_EDITING_THIS_CHAIN_OF_EVENTS_RECORD')?></a><br />        
    </div>
    <br />
</div>
