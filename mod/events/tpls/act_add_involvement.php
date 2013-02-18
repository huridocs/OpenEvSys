
<?php include_once('event_title.php') ?>

<div class="panel">
    <div class="fuelux">
        <div id="myWizard" class="wizard">
            <ul class="steps">
                <li class="complete">
                    <span class="badge badge-success">1</span><?php echo _t('ADD_VICTIM') ?><span class="chevron"></span>
                </li>


                <li class="complete"><span class="badge badge-success">2</span><?php echo _t('ADD_ACT') ?><span class="chevron"></span></li>
                <li class="complete"><span class="badge badge-success">3</span><?php echo _t('ADD_PERPETRATOR') ?><span class="chevron"></span></li>
                <li class="active"><span class="badge badge-info">4</span><?php echo _t('ADD_INVOLVEMENT') ?><span class="chevron"></span></li>
                <li><span class="badge">5</span><?php echo _t('FINISH') ?><span class="chevron"></span></li>
            </ul>

        </div>
    </div>

    <br />
    <h2><?php echo _t('HOW_WAS_') . ' [' . $perpetrator->person_name . '] ' . _t('INVOLVED_IN') . ' [' . $act_name . '] ?' ?></h2>
    <br />
    <div class="form-container"> 
        <form class="form-horizontal"  id="involvement" name="involvement" action='<?php echo get_url('events', 'add_involvement', null, array('eid' => $event_id)) ?>' method='post' enctype='multipart/form-data'>
            <div class="control-group">
                <div >
                    <a class="btn" href="<?php echo get_url('events', 'add_perpetrator', null, array('person_id' => $_SESSION['vp']['perpetrator'])) ?>"><i class="icon-chevron-left"></i> <?php echo _t('BACK') ?></a>
                    <a class="btn" href="<?php echo get_url('events', 'vp_list', null, array('eid' => $event_id)) ?>"><i class="icon-remove-circle"></i> <?php echo _t('CANCEL') ?></a>
                    <button type="submit" name="more"  class="btn"><i class="icon-plus"></i> <?php echo _t('ADD_MORE_PERPETRATORS') ?></button> 

                    <button type="submit" name="finish"  class="btn btn-primary"><i class="icon-chevron-right icon-white"></i> <?php echo _t('FINISH') ?></button> 

                </div></div><?php $fields = shn_form_get_html_fields($involvement_form); ?>
            <?php place_form_elements($involvement_form, $fields); ?>
            <div class="control-group">
                <div >
                    <a class="btn" href="<?php echo get_url('events', 'add_perpetrator', null, array('person_id' => $_SESSION['vp']['perpetrator'])) ?>"><i class="icon-chevron-left"></i> <?php echo _t('BACK') ?></a>
                    <a class="btn" href="<?php echo get_url('events', 'vp_list', null, array('eid' => $event_id)) ?>"><i class="icon-remove-circle"></i> <?php echo _t('CANCEL') ?></a>
                    <button type="submit" name="more"  class="btn"><i class="icon-plus"></i> <?php echo _t('ADD_MORE_PERPETRATORS') ?></button> 

                    <button type="submit" name="finish"  class="btn btn-primary"><i class="icon-chevron-right icon-white"></i> <?php echo _t('FINISH') ?></button> 

                </div></div>
        </form>
    </div>
    <br />
</div>
