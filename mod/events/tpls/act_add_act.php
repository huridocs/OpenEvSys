
<?php include_once('event_title.php'); ?>

<div class="panel">
    <div class='flow'>
        <span class='over first'><?php echo _t('ADD_VICTIM')?></span>
        <strong><?php echo _t('ADD_ACT')?></strong>
        <span><?php echo _t('ADD_PERPETRATOR')?></span>
        <span><?php echo _t('ADD_INVOLVEMENT')?></span>
        <span><?php echo _t('FINISH')?></span>
    </div>
    <br />
    <h2><?php echo _t('WHAT_HAPPENED_TO').' "'.$victim->person_name.'" ?' ?></h2>
    <br />
    <div class="form-container"> 
        <form class="form-horizontal"  id="actform" name="actform" action='<?php echo get_url('events','add_act',null,array('eid'=>$event_id))?>' method='post' enctype='multipart/form-data'>
        <input type="hidden" value="<?php echo $victim_dob; ?>" name="vdate_of_birth"/>
        <input type="hidden" value="<?php echo $victim_dob_type; ?>" name="vdob_type"/>
        <?php 
        	$act_form['age_at_time_of_victimisation']['extra_opts']['onclick'] = "getAge(this.form);";
        	$fields = shn_form_get_html_fields($act_form);
        	place_form_elements($act_form,$fields);
         ?>
        <input type="hidden" name="victim" value='<?php echo $victim->person_record_number ?>' />
        <center>
            <a class="btn" href="<?php echo get_url('events','add_victim',null,array('person_id'=> $_SESSION['vp']['victim'])) ?>"><?php echo _t('PREVIOUS')?></a><span>&nbsp;&nbsp;</span>
            <input type="submit" class="btn" value="<?php echo _t('ADD_ADDITIONAL_DETAILS') ?>" name='add_ad'/>
            <a class="btn" href="<?php echo get_url('events','vp_list',null,array('eid'=>$event_id)) ?>"><?php echo _t('CANCEL')?></a><span>&nbsp;&nbsp;</span>
        	<input type="submit" class="btn" name="save" value="<?php echo _t('CONTINUE') ?>" />
        </center>
        </form>
    </div>
</div>
