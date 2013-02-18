
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
<div class="control-group">
                <div >
	<a class="btn" href="<?php echo get_url('events','add_intv_party',null,array('person_id'=> $_SESSION['intv']['intv_party'])) ?>"><i class="icon-chevron-left"></i> <?php echo _t('BACK')?></a>
    <?php /* <button type="submit" name="more"  class="btn"><i class="icon-plus"></i> <?php echo _t('ADD_INTERVENTIONS')?></button>*/ ?>
    
    <a class="btn" href="<?php echo get_url('events','intv_list',null,array('eid'=>$event_id)) ?>"><i class="icon-remove-circle"></i> <?php echo _t('CANCEL')?></a>
  <button type="submit" name="finish"  class="btn btn-primary"><i class="icon-chevron-right icon-white"></i> <?php echo _t('FINISH')?></button> 
        
</div></div><?php	
    $fields = shn_form_get_html_fields($intervention_form);
    place_form_elements($intervention_form,$fields);
?>
<br />
 <div class="control-group">
                <div >
	<a class="btn" href="<?php echo get_url('events','add_intv_party',null,array('person_id'=> $_SESSION['intv']['intv_party'])) ?>"><i class="icon-chevron-left"></i> <?php echo _t('BACK')?></a>
    <?php /* <button type="submit" name="more"  class="btn"><i class="icon-plus"></i> <?php echo _t('ADD_INTERVENTIONS')?></button>*/ ?>
    
    <a class="btn" href="<?php echo get_url('events','intv_list',null,array('eid'=>$event_id)) ?>"><i class="icon-remove-circle"></i> <?php echo _t('CANCEL')?></a>
  <button type="submit" name="finish"  class="btn btn-primary"><i class="icon-chevron-right icon-white"></i> <?php echo _t('FINISH')?></button> 
        
</div></div>
</form>
</div>
<br style="clear:both" />
<br />
</div>
