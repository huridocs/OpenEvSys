
<?php include_once('event_title.php'); ?>

<div class="panel">
    <div class="fuelux">
        <div id="myWizard" class="wizard">
            <ul class="steps">
                <li class="complete">
                    <a href="<?php echo get_url('events', 'add_victim', null, array('person_id' => $_SESSION['vp']['victim'])) ?>">
                        <span class="badge badge-success">1</span><?php echo _t('ADD_VICTIM') ?><span class="chevron"></span>
                    </a></li>


                <li class="active"><span class="badge badge-info">2</span><?php echo _t('ADD_ACT') ?><span class="chevron"></span></li>
                <li><span class="badge ">3</span><?php echo _t('ADD_PERPETRATOR') ?><span class="chevron"></span></li>
                <li><span class="badge">4</span><?php echo _t('ADD_INVOLVEMENT') ?><span class="chevron"></span></li>
                <li><span class="badge">5</span><?php echo _t('FINISH') ?><span class="chevron"></span></li>
            </ul>

        </div>
    </div>

    <br />
    <h2><?php echo _t('WHAT_HAPPENED_TO') . ' "' . $victim->person_name . '" ?' ?></h2>
    <br />
    <div class="form-container"> 
        <form class="form-horizontal"  id="actform" name="actform" action='<?php echo get_url('events', 'add_act', null, array('eid' => $event_id)) ?>' method='post' enctype='multipart/form-data'>
             <div class="control-group">
                <div > 
                    <a class="btn" href="<?php echo get_url('events', 'add_victim', null, array('person_id' => $_SESSION['vp']['victim'])) ?>"><i class="icon-chevron-left"></i> <?php echo _t('BACK') ?></a>
                      <button type="submit" class="btn" name="save_without"  ><i class="icon-remove-circle"></i> <?php echo _t('Add Perpetrator later') ?></button>
                    <button type="submit" class="btn "  name='add_ad' ><i class="icon-plus "></i> <?php echo _t('ADD_ADDITIONAL_DETAILS') ?></button>
                    <button type="submit" class="btn btn-primary" name="save"  ><i class="icon-chevron-right icon-white"></i> <?php echo _t('NEXT') ?></button>
                </div></div> <input type="hidden" value="<?php echo $victim_dob; ?>" name="vdate_of_birth"/>
            <input type="hidden" value="<?php echo $victim_dob_type; ?>" name="vdob_type"/>
            <?php
            $act_form['age_at_time_of_victimisation']['extra_opts']['onclick'] = "getAge(this.form);";
            $fields = shn_form_get_html_fields($act_form);
            place_form_elements($act_form, $fields);
            ?>
            <input type="hidden" name="victim" value='<?php echo $victim->person_record_number ?>' />
            <div class="control-group">
                <div > 
                    <a class="btn" href="<?php echo get_url('events', 'add_victim', null, array('person_id' => $_SESSION['vp']['victim'])) ?>"><i class="icon-chevron-left"></i> <?php echo _t('BACK') ?></a>
                     <button type="submit" class="btn" name="save_without"  ><i class="icon-remove-circle"></i> <?php echo _t('Add Perpetrator later') ?></button>
                     <button type="submit" class="btn "  name='add_ad' ><i class="icon-plus "></i> <?php echo _t('ADD_ADDITIONAL_DETAILS') ?></button>
                    <button type="submit" class="btn btn-primary" name="save"  ><i class="icon-chevron-right icon-white"></i> <?php echo _t('NEXT') ?></button>
                </div></div>
        </form>
    </div>
</div>
