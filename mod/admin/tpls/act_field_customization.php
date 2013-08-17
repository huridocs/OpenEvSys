<?php global $conf; ?>
<h2><?php echo _t('FORM_CUSTOMIZATION') ?></h2>
<div class="form-container">
    <form class="form-horizontal"  action='<?php echo get_url('admin', 'field_customization') ?>' method='get'>
        <input type='hidden' name='mod' value='admin' />
        <input type='hidden' name='act' value='field_customization' />

        <?php $fields = shn_form_get_html_fields($customization_form, false); ?>
        <div class='control-group'><?php echo $fields['entity_select'] ?></div>
        <div class="control-group">
            <div class="controls"><button type="submit"  name="change_entity"  class="btn" ><i class="icon-ok"></i>  <?php echo _t('SELECT') ?></button>
            </div></div>



    </form>
</div>

<?php if (isset($entity_select)) { ?>
<h3><?php echo _t('CHANGE') . " [ "._t(strtoupper($entity_select))." ] " . _t('FORM'); ?></h3>
    <?php

    function print_tab_attr($act, $mod = "a") {
        global $entity_select;
        if ($mod == "a") {
            ?>
            href="<?php get_url('admin', 'field_customization', null, array('sub_act' => $act, 'entity_select' => $_REQUEST['entity_select'])) ?>";
            <?php
        } else {
            if ($act == $_REQUEST['sub_act'])
                echo " class='active'";
        }
    }
    ?>
    <br />
    <div>
        <ul class="nav nav-tabs tabnav">
            <li <?php print_tab_attr('label', "li"); ?> >
                <a <?php print_tab_attr('label'); ?> ><?php echo _t('LABELS') ?></a>
            </li>
             <li <?php print_tab_attr('cnotes', "li"); ?>><a <?php print_tab_attr('cnotes'); ?> ><?php echo _t('Details') ?></a>
            </li>
            <li <?php print_tab_attr('order', "li"); ?>><a <?php print_tab_attr('order'); ?> ><?php echo _t('ORDER') ?></a>
            </li>
            <li <?php print_tab_attr('visibility', "li"); ?>><a <?php print_tab_attr('visibility'); ?> ><?php echo _t('VISIBILITY') ?></a>
            </li>
            <li <?php print_tab_attr('validation', "li"); ?>><a <?php print_tab_attr('validation'); ?> ><?php echo _t('VALIDATION') ?></a>
            </li>
           <!-- <li <?php print_tab_attr('search', "li"); ?>><a <?php print_tab_attr('search'); ?> ><?php echo _t('SEARCH') ?></a>
            </li>-->
           <li <?php print_tab_attr('help', "li"); ?>><a <?php print_tab_attr('help'); ?> ><?php echo _t('HELP_TEXT') ?></a>
            </li>   </ul>
    </div>
    <div class="panel">
        <form class="form-horizontal"  action='<?php echo get_url('admin', 'field_customization', null, array('sub_act' => $_REQUEST['sub_act'], 'entity_select' => $entity_select)) ?>' method='post'>
           <center>
                <?php
                if(!$field_help_text){
                    ?>
                
             <button type="submit" name="update" class='btn  btn-primary'  ><i class="icon-ok icon-white"></i> <?php echo _t('UPDATE_FORM') ?></button>
                <?php
                }
                ?>
            </center> <?php
            if (isset($entity_form))
                $fields1 = shn_form_get_html_fields($entity_form);
            $file = 'field_customization_' . $sub_act . '.php';
            if (file_exists(APPROOT . 'mod/admin/tpls/' . $file))
                include_once($file);
            else
                throw new shn404Exception();
            ?>
            <center>
                <?php
                if(!$field_help_text){
                    ?>
                
             <button type="submit" name="update" class='btn  btn-primary'  ><i class="icon-ok icon-white"></i> <?php echo _t('UPDATE_FORM') ?></button>
                <?php
                }
                ?>
            </center>
            <?php echo $fields1['entity_select'] ?>    
        </form>
    </div>
<?php } ?>
