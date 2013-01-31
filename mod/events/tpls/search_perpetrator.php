
<?php include_once('event_title.php'); ?>

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
<h2><?php echo _t('WHO_IS_RESPONSIBLE_FOR_THE').' <em>"'.$act_name.'"</em> '._t('AGAINST').' <em>"'.$victim->name.'"</em> ?' ?></h2>
<div class="form-container"> 
<form class="form-horizontal"  action='<?php echo get_url('events','add_perpetrator','search_perpetrator',array('eid'=>$event_id))?>' method='post' enctype='multipart/form-data'>
<?php
	shn_form_person_search('events','add_perpetrator',null,array('cancel'=>'vp_list'));		
?>
</form>
</div>
<br />
<br />
</div>
