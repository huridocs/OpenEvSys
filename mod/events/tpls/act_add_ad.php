
<?php include_once('event_title.php'); ?>
<?php
set_url_args('act_id', $act->act_record_number);
?>
<div class="panel">
    <div class="fuelux">
        <div id="myWizard" class="wizard">
            <ul class="steps">
                <li class="complete">
                    <span class="badge badge-success">1</span><?php echo _t('ADD_VICTIM') ?><span class="chevron"></span>
                </li>


                <li class="active"><span class="badge badge-info">2</span><?php echo _t('ADD_ACT') ?><span class="chevron"></span></li>
                <li><span class="badge ">3</span><?php echo _t('ADD_PERPETRATOR') ?><span class="chevron"></span></li>
                <li><span class="badge">4</span><?php echo _t('ADD_INVOLVEMENT') ?><span class="chevron"></span></li>
                <li><span class="badge">5</span><?php echo _t('FINISH') ?><span class="chevron"></span></li>
            </ul>

        </div>
    </div>
    <br />
    <h2><?php echo _t('ADD_ADDITIONAL_DETAILS_FOR') . ' "' . $act_name . '" ' . _t('ACT') . '.' ?></h2>
    <br />
    <div class="form-container"> 
        <form class="form-horizontal"  id="additional" name="additional" action='<?php echo get_url('events', 'add_ad', null, array('act_id' => $act->act_record_number)) ?>' method='post' enctype='multipart/form-data'>
            <?php
            if (!isset($type)) {
                shn_form_radio(_t('ADDITIONAL_DETAIL_TYPE'), 'type', array('options' => $types));
                ?>
            <br>
                <div class="control-group">
                    <div class="controls"> 
                        <a class="btn" href="<?php echo get_url('events', 'vp_list', null, array('eid' => $event_id)) ?>"><i class="icon-stop"></i> <?php echo _t('CANCEL') ?></a><span>&nbsp;&nbsp;</span>
                        <button type="submit" class="btn" name='set_type'  ><?php echo _t('CONTINUE') ?></button>
                    </div></div>
                <?php
            } else {
                ?>
                <?php $ad_form = generate_formarray($type, 'new'); ?>
                <?php $fields = shn_form_get_html_fields($ad_form); ?>
                <?php place_form_elements($ad_form, $fields) ?>
                <input type="hidden" name="type" value='<?php echo $type ?>' />

               <div class="control-group">
                    <div class="controls"> 
                     <a class="btn btn-info" href="<?php echo get_url('events', 'add_ad', null, array('eid' => $event_id)) ?>"><i class="icon-edit icon-white"></i> <?php echo _t('CHANGE_ADDITIONAL_DETAIL_TYPE') ?></a>
                    <a class="btn" href="<?php echo get_url('events', 'vp_list', null, array('eid' => $event_id)) ?>"><i class="icon-stop"></i> <?php echo _t('CANCEL') ?></a>
                    <button type="submit" class="btn" name='save' ><?php echo _t('CONTINUE') ?></button>
                </div></div>
    <?php
}
?>
        </form>
    </div>
</div>
