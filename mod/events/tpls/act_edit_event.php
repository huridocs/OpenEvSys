<?php global $conf; 
include_once('tabs.php');
include_once('event_title.php');
include_once('card_list.php');
draw_card_list('sum',$event_id); 
?>
<div class="panel">
    <h3><?php echo _t('EDIT_EVENT_SUMMARY')?></h3>
    <div class="form-container"> 
        <form id="event_form" name="event_form" action='<?php echo get_url('events','edit_event',null,array('eid'=>$event_id))?>' method='post' enctype='multipart/form-data'>
        <?php $fields = shn_form_get_html_fields($event_form); ?>
        <?php $fields = place_form_elements($event_form , $fields); ?>
        <center><?php  echo $fields['cancel'];echo $fields['save'];?></center>
        </form>
    </div>
</div>
