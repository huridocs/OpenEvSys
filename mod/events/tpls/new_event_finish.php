<?php 
    include_once('event_title.php');
    include_once('card_list.php');
    draw_card_list('sum',$event->event_record_number);
?>
<div class="panel">
    <div class='flow'>
        <span class='first' ><?php echo _t('ADD_EVENT_INFORMATION')?></span>
        <strong class='active'><?php echo _t('FINISH')?></strong>
    </div>
    <br />
    <div class="notice">
        <strong><?php echo _t('FINISHED__YOU_CAN_DO_THE_FOLLOWING_')?></strong><br />
        <a href="<?php get_url('events','edit_event',null)?>" ><?php echo _t('CONTINUE_EDITING_THIS_EVENT_RECORD')?></a><br />
        <a href="<?php get_url('events','vp_list',null)?>"><?php echo _t('ADD_VICTIMS_AND_PERPETRATORS_TO_THIS_EVENT')?></a>
    </div>
    <br />
<?php
    $event_form = event_form('view');
    popuate_formArray($event_form , $event);
    shn_form_get_html_labels($event_form , false);
?>
    <br />
</div>
