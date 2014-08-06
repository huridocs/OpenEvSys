<?php 
  global $conf; 
  $request_sub_act = Reform::HtmlEncode($_REQUEST['sub_act']);
  $request_mt_select = Reform::HtmlEncode($_REQUEST['mt_select']);  
?>

<h2><?php echo _t('MICRO_THESAURI_CUSTOMIZATION') ?></h2>
<div class="form-container"> 
    <form class="form-horizontal"  action="<?php get_url('admin', 'mt_customization') ?>" method='get'>
        <fieldset>
            <input type="hidden" name="mod" value="admin" />
            <input type="hidden" name="act" value="mt_customization" />
            <?php $fields = shn_form_get_html_fields($customization_form, false); ?>
            <div class='control-group'><?php echo $fields['mt_select'] ?></div>
            <div class="control-group">
                <div class="controls"><button type="submit"  name="change_mt"  class="btn" ><i class="icon-ok"></i> <?php echo _t('SELECT') ?></button>
                </div></div>
        </fieldset>
    </form>
</div>



<?php if (isset($mt_select)) { ?>
    <h3><?php echo _t('MICRO_THESAURI_TERMS'); ?></h3>
    <?php

    function print_tab_attr($act, $mod = "a") {
        global $mt_select;
        if ($mod == "a") {
            ?>
            href="<?php get_url('admin', 'mt_customization', null, array('sub_act' => $act, 'mt_select' => $request_mt_select)) ?>";
            <?php
        } else {
            if ($act == $request_sub_act)
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
            </li>
            <li <?php print_tab_attr('order', "li"); ?>><a <?php print_tab_attr('order'); ?> ><?php echo _t('ORDER') ?></a>
            </li>
        </ul>
    </div>
    <div class="panel">
        <form class="form-horizontal"  action='<?php echo get_url('admin', 'mt_customization', null, array('sub_act' => $request_sub_act, 'mt_select' => $request_mt_select)) ?>' method='post'>
            <center>

                <button type="submit" name="update" class='btn  btn-primary'  ><i class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?></button>
                <?php
                if ($sub_act == 'label') {
                    ?>
                     <button name='' class="btn" onclick="add_new_mt();return false;" ><i class="icon-plus"></i> <?php echo _t('Add new term') ?></button>
                    
                    <?php
                }
                ?>
            </center>
            <?php if ($haschildren) { ?>
            <br/>
                <div class="alert alert-error">
                    <h3><?php echo _t('DO_YOU_WANT_TO_DELETE_THE_SELECTED_MICRO_THESAURUS_TERM_S___') ?></h3>
                    <?php if ($has_children) { ?>
            <?php echo _t("YOU_HAVE_ALSO_SELECTED_SOME_PARENT_TERM_S___IF_YOU_PROCEED_THE_CHILDRENS_OF_THOSE_TERMS_WILL_ALSO_BE_DELETED_") ?>
        <?php } ?>
                    <br/>
                    <center>
                        <button type='submit' class='btn btn-grey' name='delete_yes' ><i class="icon-trash"></i> <?php echo _t('DELETE') ?></button>
                        <button type='submit' class='btn' name='no' ><i class="icon-remove-circle"></i> <?php echo _t('CANCEL') ?></button>
                    </center>
                </div>       
            <?php } ?>
            
            <?php
            $file = 'mt_customization_' . $sub_act . '.php';
            if (file_exists(APPROOT . 'mod/admin/tpls/' . $file))
                include_once($file);
            else
                throw new shn404Exception();
            ?>
            <center>

                <button type="submit" name="update" class='btn  btn-primary'  ><i class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?></button>
               <?php
                if ($sub_act == 'label') {
                    ?>
                     <button name='' class="btn" onclick="add_new_mt();return false;" ><i class="icon-plus"></i> <?php echo _t('Add new term') ?></button>
                    
                    <?php
                }
                ?>
            </center>
        </form>
    </div>
<?php } ?>
