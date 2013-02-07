
<?php include_once('event_title.php') ?>

<div class="panel">
    <?php include_once('intv_list_table.php'); ?>
    <br />
    <br />
    <h3><?php echo _t('EDIT_THIS_INTERVENTION') ?></h3>
    <br />
    <div class="form-container"> 
        <form class="form-horizontal"  id="intervention" name="intervention" action='<?php echo get_url('events', 'edit_intv', null, array('intervention_id' => $_GET['intervention_id'])) ?>' method='post' enctype='multipart/form-data'>
            <div class="control-group">
                <div class="controls">

                    <a class="btn" href="<?php echo get_url('events', 'intv_list', null, array('eid' => $event_id, 'intervention_id' => $_GET['intervention_id'], 'type' => 'intv')) ?>"><i class="icon-remove-circle"></i> <?php echo _t('CANCEL') ?></a>
                     <button type="submit" class="btn btn-primary" name="update" ><i class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?></button>
               </div>
            </div> <?php
            $intervention_form['update'] = array('type' => 'submit', 'label' => _t('SAVE'));
            $fields = shn_form_get_html_fields($intervention_form);
            place_form_elements($intervention_form, $fields);
            ?>
            <br />
            <div class="control-group">
                <div class="controls">

                    <a class="btn" href="<?php echo get_url('events', 'intv_list', null, array('eid' => $event_id, 'intervention_id' => $_GET['intervention_id'], 'type' => 'intv')) ?>"><i class="icon-remove-circle"></i> <?php echo _t('CANCEL') ?></a>
                     <button type="submit" class="btn btn-primary" name="update" ><i class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?></button>
               </div>
            </div>

        </form>
    </div>
</div>
