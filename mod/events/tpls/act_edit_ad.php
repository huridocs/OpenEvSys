<?php 
    
    include_once('event_title.php'); 
   
?>
<div class="panel">
    <br />
    <?php include_once('vp_list_table.php') ?>
    <br />
    <?php 
        echo "<h3>"._t('ACT_DETAILS')."</h3>&nbsp;";
        $act_form = generate_formarray('act','view');
        popuate_formArray($act_form,$act);
        shn_form_get_html_labels($act_form,false);
        $act_form['update'] = array('type'=>'submit','label'=>_t('UPDATE'));
    ?>  
    <br /> 
    <div class="form-container"> 
        <form class="form-horizontal"  id="additional" name="additional" action='<?php echo get_url('events','edit_ad',null,array('act_id'=>$act->act_record_number))?>' method='post' enctype='multipart/form-data'>
        <?php
            echo "<h3>"._t('EDIT_ADDITIONAL_DETAILS')."</h3>&nbsp;";
            if(!isset($ad_type)){
                ?>
                <div>
                <?php shn_form_radio(_t('ADDITIONAL_DETAIL_TYPE'),'ad_type',array('options'=>$ad_types)); ?>
                <a class="btn" href="<?php echo get_url('events','vp_list',null,array('eid'=>$event_id,'act_id'=>$_GET['act_id'],'row'=>$_GET['row'],'type'=>'act')) ?>"><?php echo _t('CANCEL')?></a> <span>&nbsp;</span>
                <input type='submit' class='btn' value='<?php echo _t('CONTINUE') ?>' />
                </div>
                <br />
                <br />
                <?php
            }
            else
            {
                $ad_form = generate_formarray($ad_type,'edit');
                popuate_formArray($ad_form, $ad);
                $fields = shn_form_get_html_fields($ad_form);
                place_form_elements($ad_form,$fields) 
                ?>
                <input type="hidden" name="ad_type" value='<?php echo $ad_type ?>' />
                <br />
                <br />
                <center>
                <input type='submit' class='btn' value='<?php echo _t('UPDATE') ?>' name="update" />
                <a class="btn" href="<?php echo get_url('events','vp_list',null,array('eid'=>$event_id,'act_id'=>$_GET['act_id'],'row'=>$_GET['row'],'type'=>'act')) ?>"><?php echo _t('CANCEL')?></a> <span>&nbsp;</span>
                </center>
                <?php 
            }
        ?>
        </form>
    </div>
    <br />

</div>
