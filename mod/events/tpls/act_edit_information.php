
<?php include_once('event_title.php') ?>

<div class="panel">
    <?php include_once('src_list_table.php'); ?>
    <br />
    <br />
    <h3><?php echo _t('EDIT_THIS_INFORMATION') . " [" . $event->event_title . "] "; ?></h3>    
    <div class="form-container"> 
        <form class="form-horizontal"  id="information" name="information" action='<?php echo get_url('events', 'edit_information', null, array('information_id' => $_GET['information_id'], 'eid' => $event_id)) ?>' method='post' enctype='multipart/form-data'>
             <div class="control-group">
                <div >

                    <a class="btn" href="<?php echo get_url('events', 'src_list', null, array('eid' => $event_id)) ?>"><i class="icon-remove-circle"></i> <?php echo _t('CANCEL') ?></a>
                    <button type="submit" class="btn btn-primary" name="update" ><i class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?></button>
                </div>
            </div><?php
            $information_form['update'] = array('type' => 'submit', 'label' => _t('SAVE'));
            $fields = shn_form_get_html_fields($information_form);
            place_form_elements($information_form, $fields);
            ?>
            <div class="control-group">
                <div >

                    <a class="btn" href="<?php echo get_url('events', 'src_list', null, array('eid' => $event_id)) ?>"><i class="icon-remove-circle"></i> <?php echo _t('CANCEL') ?></a>
                    <button type="submit" class="btn btn-primary" name="update" ><i class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?></button>
                </div>
            </div>

        </form>
    </div>
</div>
