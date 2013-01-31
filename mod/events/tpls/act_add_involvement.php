
<?php include_once('event_title.php')?>

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
<h2><?php echo _t('HOW_WAS_').' ['.$perpetrator->person_name.'] '._t('INVOLVED_IN').' ['.$act_name .'] ?' ?></h2>
<br />
<div class="form-container"> 
<form class="form-horizontal"  id="involvement" name="involvement" action='<?php echo get_url('events','add_involvement',null,array('eid'=>$event_id))?>' method='post' enctype='multipart/form-data'>
<?php $fields = shn_form_get_html_fields($involvement_form);?>
<?php place_form_elements($involvement_form , $fields);  ?>
<center>
    <a class="btn" href="<?php   echo get_url('events','add_perpetrator',null,array('person_id'=>$_SESSION['vp']['perpetrator'])) ?>"><?php echo _t('PREVIOUS')?></a><span>&nbsp;&nbsp;</span>
    <?php echo $fields['more'];?>
    <a class="btn" href="<?php   echo get_url('events','vp_list',null,array('eid'=>$event_id)) ?>"><i class="icon-stop"></i> <?php echo _t('CANCEL')?></a><span>&nbsp;&nbsp;</span>    <?php echo $fields['finish'];?>
    
    
</center>
</form>
</div>
<br />
</div>
