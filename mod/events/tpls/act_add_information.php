
<?php include_once('event_title.php'); ?>

<div class="panel">
    <div class="fuelux">
        <div id="myWizard" class="wizard">
            <ul class="steps">
                <li class="complete">
                        <span class="badge badge-success">1</span><?php echo _t('ADD_SOURCE') ?><span class="chevron"></span>
                   
                
                <li  class="active"><span class="badge  badge-info ">2</span><?php echo _t('ADD_INFORMATION') ?><span class="chevron"></span></li>
                 <li><span class="badge">3</span><?php echo _t('FINISH') ?><span class="chevron"></span></li>
            </ul>
           
        </div>
    </div>
      
    <br />
    <h3><?php echo _t('INFORMATION_ABOUT'). "[". $event->event_title ."]"; ?></h3>
    <br />
    <div class="form-container"> 
        <form class="form-horizontal"  id="information" name="information" action='<?php echo get_url('events','add_information',null)?>' method='post' enctype='multipart/form-data'>
         <div class="control-group">
                <div class="controls">
 <a class="btn" href="<?php echo get_url('events','add_source',null,array('person_id'=> $_SESSION['src']['source'])) ?>"><i class="icon-chevron-left"></i> <?php echo _t('BACK')?></a>
           <a class="btn" href="<?php echo get_url('events','add_source',null) ?>"><i class="icon-remove-circle"></i> <?php echo _t('CANCEL')?></a>
           <button type="submit" name="finish"  class="btn btn-primary"><i class="icon-chevron-right icon-white"></i> <?php echo _t('FINISH')?></button> 
        </div></div><?php            
            $fields = shn_form_get_html_fields($information_form);
            place_form_elements($information_form,$fields);
        ?>
        
        <div class="control-group">
                <div class="controls">
 <a class="btn" href="<?php echo get_url('events','add_source',null,array('person_id'=> $_SESSION['src']['source'])) ?>"><i class="icon-chevron-left"></i> <?php echo _t('BACK')?></a>
           <a class="btn" href="<?php echo get_url('events','add_source',null) ?>"><i class="icon-remove-circle"></i> <?php echo _t('CANCEL')?></a>
           <button type="submit" name="finish"  class="btn btn-primary"><i class="icon-chevron-right icon-white"></i> <?php echo _t('FINISH')?></button> 
        </div></div>
        </form>
    </div>
</div>
