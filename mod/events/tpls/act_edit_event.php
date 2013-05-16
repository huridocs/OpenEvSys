<?php
global $conf;

include_once('event_title.php');
?>
<div class="panel">
    <h3><?php echo _t('EDIT_EVENT_SUMMARY') ?></h3>
    <div class="form-container"> 
        <form class="form-horizontal"  id="event_form" name="event_form" action='<?php echo get_url('events', 'edit_event', null, array('eid' => $event_id)) ?>' method='post' enctype='multipart/form-data'>
            <div class="control-group">
                <div >
                    <a class="btn" href="<?php echo get_url('events', 'get_event'); ?>"><i class="icon-remove-circle"></i> <?php echo _t('CANCEL'); ?></a>

                    <button type="submit" class="btn btn-primary" name="save" ><i class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?></button>
                    
                </div>
            </div> <?php $fields = shn_form_get_html_fields($event_form); ?>
            <?php $fields = place_form_elements($event_form, $fields); ?>
            <div class="control-group">
                <div >

 <a class="btn" href="<?php echo get_url('events', 'get_event'); ?>"><i class="icon-remove-circle"></i> <?php echo _t('CANCEL'); ?></a>
<button type="submit" class="btn btn-primary" name="save" ><i class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?></button>
                    
                </div>
            </div> 
            

        </form>
    </div>
</div>
