
<?php include_once('event_title.php') ?>

<div class="panel">

    <br />
    <div class="form-container"> 
        <form class="form-horizontal"  action='<?php echo get_url('events', 'edit_coe', null, array('eid' => $_GET['eid'], 'coeid' => $_GET['coeid'], 'search_type' => 'event')) ?>' method='post' enctype='multipart/form-data'>
            <?php
            $chain_of_events_form['update'] = array('type' => 'submit', 'label' => _t('UPDATE'));
            $fields = shn_form_get_html_fields($chain_of_events_form);
            include 'chain_of_event_form.php';
            ?>  
            <div class="control-group">
                <div class="controls">

                    <button type="submit" class="btn" name="update" ><i class="icon-ok"></i> <?php echo _t('SAVE') ?></button>
                    <a class="btn" href="<?php echo get_url('events', 'coe_list'); ?>"><i class="icon-stop"></i> <?php echo _t('CANCEL'); ?></a>
                </div>
            </div>

        </form> 
        <br />
    </div>
</div>
