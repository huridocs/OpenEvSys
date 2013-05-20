
<?php include_once('event_title.php') ?>

<div class="panel">
    <div class="fuelux">
        <div id="myWizard" class="wizard">
            <ul class="steps">
                <li class="complete">
                    <span class="badge badge-success">1</span><?php echo _t('ADD_VICTIM') ?><span class="chevron"></span>


                <li class="complete"><span class="badge badge-success">2</span><?php echo _t('ADD_ACT') ?><span class="chevron"></span></li>
                <li class="active"><span class="badge badge-info">3</span><?php echo _t('ADD_PERPETRATOR') ?><span class="chevron"></span></li>
                <li><span class="badge">4</span><?php echo _t('ADD_INVOLVEMENT') ?><span class="chevron"></span></li>
                <li><span class="badge">5</span><?php echo _t('FINISH') ?><span class="chevron"></span></li>
            </ul>

        </div>
    </div>
    <br />
    <h2><?php echo _t('WHO_IS_RESPONSIBLE_FOR_THE') . ' <em>"' . $act_name . '"</em> ' . _t('AGAINST') . ' <em>"' . $victim->person_name . '"</em> ?' ?></h2>
    <br />
    <div class="form-container">
        <form class="form-horizontal"  id="person_form" name="person_form" action='<?php echo get_url('events', 'add_perpetrator', 'new_perpetrator', array('eid' => $event_id)) ?>' method='post' enctype='multipart/form-data'>
            <div class="control-group">
            <div > 


                <a class="btn" href="<?php echo get_url('events', 'vp_list', null, array('eid' => $event_id)) ?>"><i class="icon-remove-circle"></i> <?php echo _t('CANCEL') ?></a>
                <button type="submit" class="btn btn-primary"  name='save'><i class="icon-chevron-right icon-white"></i> <?php echo _t('NEXT') ?></button>
            </div></div>
                <?php $person_form = person_form('new'); ?>
            <?php $fields = shn_form_get_html_fields($person_form); ?>
            <?php $fields = place_form_elements($person_form, $fields); ?>
            <div class="control-group">
            <div > 


                <a class="btn" href="<?php echo get_url('events', 'vp_list', null, array('eid' => $event_id)) ?>"><i class="icon-remove-circle"></i> <?php echo _t('CANCEL') ?></a>
                <button type="submit" class="btn btn-primary"  name='save'><i class="icon-chevron-right icon-white"></i> <?php echo _t('NEXT') ?></button>
            </div></div>

</form>
</div>
</div>

