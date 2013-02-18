
<?php include_once('event_title.php'); ?>


<div class="panel">
    <div class="fuelux">
        <div id="myWizard" class="wizard">
            <ul class="steps">
                <li class="active">
                    <span class="badge badge-info">1</span><?php echo _t('ADD_INTERVENING_PARTY') ?><span class="chevron"></span>


                <li><span class="badge ">2</span><?php echo _t('ADD_INTERVENTION') ?><span class="chevron"></span></li>
                <li><span class="badge">3</span><?php echo _t('FINISH') ?><span class="chevron"></span></li>
            </ul>

        </div>
    </div>
    <br />
    <h2><?php echo _t('WHO_IS_THE_INTERVENING_PARTY_') ?></h2>
    <br />
    <?php
    if (isset($intv_party)) {
        ?>
        <div class="control-group">
            <div > 
                <a class="btn" href="<?php echo get_url('events', 'intv_list', null, array('eid' => $event_id)) ?>"><i class="icon-remove-circle"></i> <?php echo _t('CANCEL') ?></a>

                <?php if (acl_is_entity_allowed_boolean('person', 'create')) {// if create is ever changed for this please update acl_person_entity_is_allowed() ?>    
                    <a class="btn " href="<?php echo get_url('events', 'add_intv_party', 'new_intv_party', array('eid' => $event_id)) ?>"><i class="icon-plus "></i> <?php echo _t('ADD_NEW') ?></a>
                <?php } ?>
                <?php if (acl_is_entity_allowed_boolean('person', 'read')) {// if read is ever changed for this please update acl_person_entity_is_allowed() ?>    
                    <a class="btn<?php
            if (!isset($intv_party)) {
                echo "btn-primary";
            }
                    ?>" href="<?php echo get_url('events', 'add_intv_party', 'search_intv_party', array('eid' => $event_id)) ?>"><i class="icon-search <?php
               if (!isset($intv_party)) {
                   echo "icon-white";
               }
                    ?>"></i> <?php echo _t('SEARCH_IN_DATABASE') ?></a>
                    <?php } ?>
                    <?php if (isset($intv_party)) { ?>
                    <a class="btn btn-primary" href="<?php echo get_url('events', 'add_intv', null, array('eid' => $event_id)) ?>"><i class="icon-chevron-right icon-white"></i> <?php echo _t('NEXT') ?></a>
                <?php } ?>

            </div></div>
        <?php
        $person_form = person_form('view');
        popuate_formArray($person_form, $intv_party);
        shn_form_get_html_labels($person_form, false);
    }
    ?>
    <div class="control-group">
        <div > 
            <a class="btn" href="<?php echo get_url('events', 'intv_list', null, array('eid' => $event_id)) ?>"><i class="icon-remove-circle"></i> <?php echo _t('CANCEL') ?></a>
          
            <?php if (acl_is_entity_allowed_boolean('person', 'create')) {// if create is ever changed for this please update acl_person_entity_is_allowed() ?>    
                <a class="btn " href="<?php echo get_url('events', 'add_intv_party', 'new_intv_party', array('eid' => $event_id)) ?>"><i class="icon-plus "></i> <?php echo _t('ADD_NEW') ?></a>
                <?php } ?>
  <?php if (acl_is_entity_allowed_boolean('person', 'read')) {// if read is ever changed for this please update acl_person_entity_is_allowed()   ?>    
                <a class="btn <?php
            if (!isset($intv_party)) {
                echo "btn-primary";
            }
                ?>" href="<?php echo get_url('events', 'add_intv_party', 'search_intv_party', array('eid' => $event_id)) ?>"><i class="icon-search <?php
               if (!isset($intv_party)) {
                   echo "icon-white";
               }
                ?>"></i> <?php echo _t('SEARCH_IN_DATABASE') ?></a>
            <?php } ?>
            <?php if (isset($intv_party)) { ?>
                <a class="btn btn-primary" href="<?php echo get_url('events', 'add_intv', null, array('eid' => $event_id)) ?>"><i class="icon-chevron-right icon-white"></i> <?php echo _t('NEXT') ?></a>
            <?php } ?>

        </div></div>
</div>
