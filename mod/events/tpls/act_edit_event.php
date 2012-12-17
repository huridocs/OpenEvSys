<?php global $conf; 

include_once('event_title.php');
?>
<div class="panel">
    <h3><?php echo _t('EDIT_EVENT_SUMMARY')?></h3>
    <div class="form-container"> 
        <form class="form-horizontal"  id="event_form" name="event_form" action='<?php echo get_url('events','edit_event',null,array('eid'=>$event_id))?>' method='post' enctype='multipart/form-data'>
        <?php $fields = shn_form_get_html_fields($event_form); ?>
        <?php $fields = place_form_elements($event_form , $fields); ?>
        <center><?php  echo $fields['cancel'];echo $fields['save'];?></center>
        </form>
    </div>
</div>
