
<?php include_once('event_title.php'); ?>

<div class="panel">
          <div class="fuelux">
        <div id="myWizard" class="wizard">
            <ul class="steps">
                <li class="complete">
                        <span class="badge  badge-success">1</span><?php echo _t('ADD_INTERVENING_PARTY') ?><span class="chevron"></span>
                   
                
                <li class="active"><span class="badge badge-info">2</span><?php echo _t('ADD_INTERVENTION') ?><span class="chevron"></span></li>
                 <li><span class="badge">3</span><?php echo _t('FINISH') ?><span class="chevron"></span></li>
            </ul>
           
        </div>
    </div>

<br />
<h2><?php echo _t('WHAT_IS_THE_INTERVENTION_') ?></h2>
<br />
<div class="form-container"> 
<form class="form-horizontal"  id="intervention" name="intervention" action='<?php echo get_url('events','add_intv',null,array('eid'=>$event_id))?>' method='post' enctype='multipart/form-data'>
<?php	
    $fields = shn_form_get_html_fields($intervention_form);
    place_form_elements($intervention_form,$fields);
?>
<br />
<center>
	<a class="btn" href="<?php echo get_url('events','add_intv_party',null,array('person_id'=> $_SESSION['intv']['intv_party'])) ?>"><?php echo _t('PREVIOUS')?></a><span>&nbsp;&nbsp;</span>
    <?php // echo $fields['more'];?>    
    <a class="btn" href="<?php echo get_url('events','intv_list',null,array('eid'=>$event_id)) ?>"><i class="icon-stop"></i> <?php echo _t('CANCEL')?></a><span>&nbsp;&nbsp;</span>
	<?php echo $fields['finish'];?>
</center>
</form>
</div>
<br style="clear:both" />
<br />
</div>
