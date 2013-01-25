
<?php include_once('event_title.php')?>

<div class="panel">
<div class='flow'>
    <span class="over first"><?php echo _t('ADD_VICTIM')?></span>
    <span class="over"><?php echo _t('ADD_ACT')?></span>
    <strong><?php echo _t('ADD_PERPETRATOR')?></strong>
    <span><?php echo _t('ADD_INVOLVEMENT')?></span>
    <span><?php echo _t('FINISH')?></span>
</div>
<br />
<h2><?php echo _t('WHO_IS_RESPONSIBLE_FOR_THE').' <em>"'.$act_name.'"</em> '._t('AGAINST').' <em>"'.$victim->person_name.'"</em> ?' ?></h2>
<br />
    <div class="form-container">
        <form class="form-horizontal"  id="person_form" name="person_form" action='<?php echo get_url('events','add_perpetrator','new_perpetrator',array('eid'=>$event_id))?>' method='post' enctype='multipart/form-data'>
            <?php $person_form = person_form('new');?>
            <?php $fields = shn_form_get_html_fields($person_form);  ?>
            <?php $fields = place_form_elements($person_form,$fields); ?>
            <center>
            <a class="btn" href="<?php echo get_url('events','vp_list',null,array('eid'=>$event_id)) ?>"><?php echo _t('CANCEL')?></a>
			<input type="submit" class="btn" value="<?php echo _t('CONTINUE') ?>" name='save'/>
			<span>&nbsp;&nbsp;</span>
            
            </center>
        </form>
    </div>
</div>

