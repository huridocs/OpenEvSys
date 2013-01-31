<?php 
    
    include_once('event_title.php');
?>
<div class="panel">
      <div class="fuelux">
         <div id="myWizard" class="wizard">
            <ul class="steps">
                <li class="complete">
                        <span class="badge  badge-success">1</span><?php echo _t('ADD_CHAIN_OF_EVENTS') ?><span class="chevron"></span>
                   
                 <li class="active"><span class="badge  badge-info">2</span><?php echo _t('FINISH') ?><span class="chevron"></span></li>
            </ul>
           
        </div></div>

    <br />
<?php	 
    shn_form_get_html_labels($chain_of_events_form,false);
?>
<br />
    <div class='alert alert-info'> <button type="button" class="close" data-dismiss="alert">×</button> 
        <strong><?php echo _t('FINISHED__YOU_CAN_DO_THE_FOLLOWING')?></strong><br />
        <a href="<?php get_url('events','edit_coe',null,array('eid'=>$event_id,'coeid'=>$coeid))?>" class="btn btn-primary"><i class="icon-plus icon-white"></i> <?php echo _t('CONTINUE_EDITING_THIS_CHAIN_OF_EVENTS_RECORD')?></a><br />        
    </div>
    
</div>
