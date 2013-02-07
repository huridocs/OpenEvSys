<?php
include_once('event_title.php');
?>
<div class="panel">
    <br />
    <?php include_once('vp_list_table.php') ?>
    <br />
    <h2><?php echo _t('ACT_DETAILS') ?>
        <?php
        $act_form = generate_formarray('act', 'view');
        popuate_formArray($act_form, $act);
        shn_form_get_html_labels($act_form, false);
        $act_form['update'] = array('type' => 'submit', 'label' => _t('SAVE'));
        ?>  
        <br /> 
        <div class="form-container"> 
            <form class="form-horizontal"  id="additional" name="additional" action='<?php echo get_url('events', 'edit_ad', null, array('act_id' => $act->act_record_number)) ?>' method='post' enctype='multipart/form-data'>
                <?php
                echo "<h3>" . _t('EDIT_ADDITIONAL_DETAILS') . "</h3>&nbsp;";
                if (!isset($ad_type)) {
                    ?>
                    <div>
                        <?php shn_form_radio(_t('ADDITIONAL_DETAIL_TYPE'), 'ad_type', array('options' => $ad_types)); ?>
                        <div class="control-group">
                            <div class="controls"><a class="btn" href="<?php echo get_url('events', 'vp_list', null, array('eid' => $event_id, 'act_id' => $_GET['act_id'], 'row' => $_GET['row'], 'type' => 'act')) ?>"><i class="icon-remove-circle"></i> <?php echo _t('CANCEL') ?></a> 
                                <button type='submit' class='btn btn-primary' ><i class="icon-chevron-right icon-white"></i> <?php echo _t('NEXT') ?></button>
                            </div></div></div>

                    <?php
                } else {
                    $ad_form = generate_formarray($ad_type, 'edit');
                    popuate_formArray($ad_form, $ad);
                    $fields = shn_form_get_html_fields($ad_form);
                    place_form_elements($ad_form, $fields)
                    ?>
                    <input type="hidden" name="ad_type" value='<?php echo $ad_type ?>' />
                    <div class="control-group">
                        <div class="controls">
                            <a class="btn" href="<?php echo get_url('events', 'vp_list', null, array('eid' => $event_id, 'act_id' => $_GET['act_id'], 'row' => $_GET['row'], 'type' => 'act')) ?>"><i class="icon-remove-circle"></i> <?php echo _t('CANCEL') ?></a> 
                            <button  type='submit' class='btn btn-primary' name="update" ><i class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?></button>
                        </div></div>
                    <?php
                }
                ?>
            </form>
        </div>
        <br />

</div>
