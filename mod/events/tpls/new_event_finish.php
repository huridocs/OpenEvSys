<?php
include_once('event_title.php');
?>
<div class="panel">
    <div class="fuelux">
        <div id="myWizard" class="wizard">
            <ul class="steps">
                <li class="complete">
                    <span class="badge  badge-success">1</span><?php echo _t('ADD_EVENT_INFORMATION') ?><span class="chevron"></span>

                <li class="active"><span class="badge  badge-info">2</span><?php echo _t('FINISH') ?><span class="chevron"></span></li>
            </ul>

        </div></div>

    <br />
    <div class='alert alert-info'> <button type="button" class="close" data-dismiss="alert">×</button> 
        <strong><?php echo _t('FINISHED__YOU_CAN_DO_THE_FOLLOWING_') ?></strong><br /><br />
        <div class="control-group">
            <div class="controls">

                <a href="<?php get_url('events', 'edit_event', null) ?>" class="btn"><i class="icon-edit"></i> <?php echo _t('CONTINUE_EDITING_THIS_EVENT_RECORD') ?></a>
                <a href="<?php get_url('events', 'vp_list', null) ?>" class="btn btn-primary"><i class="icon-plus icon-white"></i> <?php echo _t('ADD_VICTIMS_AND_PERPETRATORS_TO_THIS_EVENT') ?></a>
            </div></div> </div>

    <?php
    $event_form = event_form('view');
    popuate_formArray($event_form, $event);
    shn_form_get_html_labels($event_form, false);
    ?>
    <br />
</div>
