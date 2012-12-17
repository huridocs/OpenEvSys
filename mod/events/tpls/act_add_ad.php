
<?php include_once('event_title.php'); ?>
<?php
     set_url_args('act_id',$act->act_record_number);
?>
<div class="panel">
    <div class='flow'>
        <span class='over first'><?php echo _t('ADD_VICTIM')?></span>
        <strong><?php echo _t('ADD_ACT')?></strong>
        <span><?php echo _t('ADD_PERPETRATOR')?></span>
        <span><?php echo _t('ADD_INVOLVEMENT')?></span>
        <span><?php echo _t('FINISH')?></span>
    </div>
    <br />
    <h2><?php echo _t('ADD_ADDITIONAL_DETAILS_FOR').' "'.$act_name.'" '._t('ACT').'.' ?></h2>
    <br />
    <div class="form-container"> 
        <form class="form-horizontal"  id="additional" name="additional" action='<?php echo get_url('events','add_ad',null,array('act_id'=>$act->act_record_number))?>' method='post' enctype='multipart/form-data'>
        <?php
            if(!isset($type)){
                shn_form_radio(_t('ADDITIONAL_DETAIL_TYPE'),'type',array('options'=>$types));
        ?>
                <center>
                <a class="btn" href="<?php echo get_url('events','vp_list',null,array('eid'=>$event_id)) ?>"><?php echo _t('CANCEL')?></a><span>&nbsp;&nbsp;</span>
                <input type="submit" class="btn" name='set_type' value="<?php echo _t('CONTINUE') ?>" />
                </center>
        <?php
            }
            else
            {
        ?>
                <?php $ad_form = generate_formarray($type,'new'); ?>
                <?php $fields = shn_form_get_html_fields($ad_form);?>
                <?php place_form_elements($ad_form,$fields) ?>
                <input type="hidden" name="type" value='<?php echo $type ?>' />

                <center>
                <a class="btn" href="<?php echo get_url('events','add_ad',null,array('eid'=>$event_id)) ?>"><?php echo _t('CHANGE_ADDITIONAL_DETAIL_TYPE')?></a><span>&nbsp;&nbsp;</span>
                <a class="btn" href="<?php echo get_url('events','vp_list',null,array('eid'=>$event_id)) ?>"><?php echo _t('CANCEL')?></a><span>&nbsp;&nbsp;</span>
                <input type="submit" class="btn" name='save' value="<?php echo _t('CONTINUE') ?>" />
                </center>
        <?php 
            }
        ?>
        </form>
    </div>
</div>
