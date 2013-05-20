<?php
include_once('event_title.php');
?>
<div class="panel">
    <div class="fuelux">
        <div id="myWizard" class="wizard">
            <ul class="steps">
                <li class="active">
                    <span class="badge badge-info">1</span><?php echo _t('ADD_CHAIN_OF_EVENTS') ?><span class="chevron"></span>

                <li><span class="badge">2</span><?php echo _t('FINISH') ?><span class="chevron"></span></li>
            </ul>

        </div>
    </div>

    <br />    
    <div class="form-container"> 
        <form class="form-horizontal"  action='<?php echo get_url('events', 'add_coe', null, array('eid' => $_GET['eid'], 'search_type' => 'event')) ?>' method='post' enctype='multipart/form-data'>            
            <div class="control-group">
                <div >


                    <a class="btn" href="<?php echo get_url('events', 'coe_list'); ?>"><i class="icon-remove-circle"></i> <?php echo _t('CANCEL'); ?></a>
                    <button type="submit" class="btn btn-primary" name="save" ><i class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?></button>
                </div>
            </div><?php
$fields = shn_form_get_html_fields($chain_of_events_form);
$fields = place_form_elements($chain_of_events_form, $fields);
?>		
            <div class="control-group">
                <div >


                    <a class="btn" href="<?php echo get_url('events', 'coe_list'); ?>"><i class="icon-remove-circle"></i> <?php echo _t('CANCEL'); ?></a>
                    <button type="submit" class="btn btn-primary" name="save" ><i class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?></button>
                </div>
            </div>


        </form>       
    </div>
</div>
