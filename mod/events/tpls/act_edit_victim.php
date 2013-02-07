
<?php include_once('event_title.php'); ?>

<div class="panel">
    <br />
    <?php include_once('vp_list_table.php'); ?>
    <br />
    <?php
    echo "<h3>" . _t('EDIT_THIS_VICTIM') . "</h3>&nbsp;";
    ?>
    <div class="form-container">
        <form class="form-horizontal"  id="person_form" name="person_form" action='<?php echo get_url('events', 'edit_victim', null, array('eid' => $event_id, 'act_id' => $_GET['act_id'])) ?>' method='post' enctype='multipart/form-data'>
            <div class="control-group">
                <div class="controls">
                    <a class="btn" href="<?php echo get_url('events', 'vp_list', null, array('eid' => $event_id, 'act_id' => $_GET['act_id'], 'row' => $_GET['row'], 'type' => 'victim')) ?>"><i class="icon-remove-circle"></i> <?php echo _t('CANCEL') ?></a> <span>&nbsp;</span>

                    <button type="submit" class="btn" name="update" ><i class="icon-ok"></i> <?php echo _t('SAVE') ?></button>
                </div>
            </div><?php
            place_form_elements($person_form, $fields);
            ?> 
            <div class="control-group">
                <div class="controls">
                    <a class="btn" href="<?php echo get_url('events', 'vp_list', null, array('eid' => $event_id, 'act_id' => $_GET['act_id'], 'row' => $_GET['row'], 'type' => 'victim')) ?>"><i class="icon-remove-circle"></i> <?php echo _t('CANCEL') ?></a> <span>&nbsp;</span>

                    <button type="submit" class="btn" name="update" ><i class="icon-ok"></i> <?php echo _t('SAVE') ?></button>
                </div>
            </div>

        </form>
    </div>
    <br />
</div>
